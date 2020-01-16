<?php

include 'classes/dimple-Post.php';

class Dimple
{
   protected $entries;
   function __construct( $dir = "./content/" )
   {
      //load all the blog posts
      $this->entries = array();
      if( is_dir( $dir ) )
      {
         if( ( $handle = opendir( $dir ) ) !== false )
         {
            while( ( $file = readdir( $handle ) ) !== false )
            {
               if( $file[0] == '.' ) //Skip hidden files and ./.. directories
                  continue;
               try
               {
                  $this->entries[] = new post( $file );
               }
               catch( Exception $e )
               {
                  error_log( "Exception [dimple Blog] : " . $e->getMessage() . "\n" );
                  continue;
               }
            }
         }
         closedir( $handle );
      }
      else
      {
         throw new Exception( "Directory $dir does not exist." );
      }

   }

   protected function render404Page()
   {
      echo <<<HTML
      <div id='dimple-output'>
            <div class='dimple-page post-full'>
               <p class='dimple-page dimple-content'>
HTML;
      include './content/.404.md';
      echo <<<HTML
               </p>
            </div>
         </div>
HTML;

   }

   function allTags() //List all tags of all posts and remove duplicates
   {
      $count = count( $this->entries );
      $tags = [];
      for( $i = 0; $i < $count; $i++ )
      {
         $tags = array_merge( $tags, $this->entries[$i]->getTags() );
      }
      //lowercase all tags
      $tags = array_map( function ( $tag ) { return strtolower( $tag ); }, $tags );
      $tags = array_unique( $tags, SORT_STRING );

      usort( $tags, function( $a, $b ) { return strcasecmp( $a, $b ); } );

      $count = count( $tags );
      for( $i = 0; $i < $count; $i++ )
      {
         $tagRet .=  "<a class ='dimple-page' href='?tags=" . urlencode( $tags[$i] ) . "'>{$tags[$i]}</a>";
         if( $i < $count - 1 )
            $tagRet .= ", ";
      }
      return $tagRet;
   }

   protected function index( $array, $offset = 0, $tag )
   {
      if( $array === NULL )
         $array = $this->entries;
      //sort the posts by 'publishDate' meta-data
      usort( $array, function ($a, $b){
         if( strtotime( $a->getDate() ) < strtotime( $b->getDate() ) )
         {
            return 1;
         }
         else if( strtotime( $a->getDate() ) > strtotime( $b->getDate() ) )
         {
            return -1;
         }
         else
            return 0;
      } );

      $arraySize = count( $array );

      if( $offset > $arraySize ) //Can't list past the total number of entries
         $offset = 0;


      while( $offset != $arraySize )
      {
         $entry = $array[$offset];
         if( ( count( $tag ) > 0 && in_array( $tag, $entry->getTags() ) == true ) || count( $tag ) == 0 )
            echo $entry->printBrief();
         $offset++;
      }
   }

   protected function view( $post )
   {
      $count = count( $this->entries );

      for( $i = 0; $i < $count; $i++ )
      {
         $entry = $this->entries[$i];
         if( !strcasecmp( $post, $entry->getAccessName() ) )
         {
            echo $entry->printFull();
            return;
         }
      }
      $this->render404Page();//Can't find the specified post
   }

   function run()
   {
      if( isset( $_GET['view'] ) )
      {
         $this->view( $_GET['view'] );
      }
      else
      {
         $this->index( NULL, isset( $_GET['index'] ) ? $_GET['index'] : 0, $_GET['tags'] ); //If all else fails, just list the entries
      }
   }
}

?>
