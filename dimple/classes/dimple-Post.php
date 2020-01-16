<?php
class Post
{
   protected $author;
   protected $publishDate;
   protected $title;
   protected $accessName;
   protected $tags;
   protected $encoding;
   protected $content;
   protected $hidden;

   function __construct( $file )
   {
      if( ( $c = fopen( "./content/" . $file, "r" ) ) == false )
      {
         throw new Exception( "File $file does not exist." );
      }

      /* Parse meta-data */
      $line = chop( fgets( $c ) );
      if( strcasecmp( $line, "@meta-start" ) ) //All files start with meta-data
      {
         //File does not have meta-data, abort loading.
         fclose( $c );
         throw new Exception( "File $file does not have meta-data." );
      }

      while( !feof( $c ) )
      {
         $meta = explode( " ", fgets( $c ), 2 ); //Lop off the first word for processing

         if( !strcasecmp( $meta[0], "@author" ) )
         {
            $this->author = trim($meta[1]);
         }
         else if( !strcasecmp( $meta[0], "@publish-date" ) )
         {
            $this->publishDate = strtotime( $meta[1] );
         }
         else if( !strcasecmp( $meta[0], "@title" ) )
         {
            $this->title = trim( $meta[1] );
         }
         else if( !strcasecmp( $meta[0], "@access-name" ) )
         {
            //the url name for the blog post. Shouldn't have spaces or any other craziness.
            $this->accessName = trim( $meta[1] );
         }
         else if( !strcasecmp( $meta[0], "@tags" ) )
         {
            //comma separated list of tags, remove all spaces to make url's happy
            $this->tags = explode( ",", preg_replace('/\s+/', '', $meta[1] ) );
         }
         else if( !strcasecmp( $meta[0], "@encoding" ) ) 
         {
            //I know markdown/plaintext are technically not 'encodings' but that's what I'm calling it.
            //current options are markdown or plaintext
            $this->encoding = trim( $meta[1] );
         }
         else if( !strcasecmp( $meta[0], "@end" ) )
         {
            break;
         }
         else
         {
            //ignore unknown tags
            error_log( "Error [dimple Blog] : Unknown meta-data key $meta[0].\n" );
         }
      }

      //Check to make sure mandatory markdown is present
      if(  $this->accessName == NULL )
      {
         fclose( $c );
         throw new Exception( "File $file is missing required meta-data." );
      }

      /* Read in the rest of the file (The post contents) */
      $this->content = file_get_contents( "./content/" . $file, 0, NULL, ftell( $c ) );

      fclose( $c );
   }

   function getContents() { return $this->content; }
   function getDate() { return date( "Y-m-d", $this->publishDate ); }
   function getTitle() { return $this->title; }
   function getAccessName() { return $this->accessName; }
   function getTags() { return $this->tags; }
   function getAuthor() { return $this->author; }
   function isHidden() { if( $this->hidden == true ) return true; else return false; }
   function getEncoding() { return $this->encoding; }

   function printFull()
   {
      $tags = $this->getTags();
      $count = count( $tags );
      for( $i = 0; $i < $count; $i++ )
      {
         $tagsHTML .=  "<a class ='dimple-page' href='?tags=" . urlencode( $tags[$i] ) . "'>{$tags[$i]}</a>";
         if( $i < $count - 1 )
            $tagsHTML .= ", ";
      }

      return <<<HTML
         <div id='dimple-output'>
            <div class='dimple-page post-full'>
               <h1 class='dimple-page'>{$this->getTitle()}</h1> &nbsp;<span style='font-size:.75em'><strong>By </strong>{$this->getAuthor()} <strong>&nbsp;|&nbsp;</strong> {$this->getDate()} <strong>&nbsp;|&nbsp;</strong> {$tagsHTML}<br></span><br>
               <pre class='dimple-page dimple-content-{$this->getEncoding()}'>
                  {$this->getContents()}
               </pre>
               <hr class='dimple-page'>
            </div>
         </div>
HTML;
   }

   function printBrief()
   {
      $tags = $this->getTags();
      $count = count( $tags );
      for( $i = 0; $i < $count; $i++ )
      {
         $tagsHTML .=  "<a class ='dimple-page' href='?tags=" . urlencode( $tags[$i] ) . "'>{$tags[$i]}</a>";
         if( $i < $count - 1 )
            $tagsHTML .= ", ";
      }
      $brief = explode( "\n", $this->getContents() );
      $brief = implode( "\n", array_slice( $brief, 0, 8 ) ); //Only print the first 6 lines of the post

      return <<<HTML
         <div id='dimple-output'>
            <div class='dimple-page post-brief'>
               <a href='?view={$this->getAccessName()}'><h1 class='dimple-page'>{$this->getTitle()}</h1></a> &nbsp;<span style='font-size:.75em'><strong>By </strong>{$this->getAuthor()} <strong>&nbsp;|&nbsp;</strong> {$this->getDate()} <strong>&nbsp;|&nbsp;</strong> {$tagsHTML}<br></span><br>
               <pre class='dimple-page dimple-content-{$this->getEncoding()}'>
                  {$brief}
               </pre>
               <a class='dimple-page' href='?view={$this->getAccessName()}'>Read more...</a>
               <hr class='dimple-page'>
            </div>
         </div>
HTML;
   }

}
