<!doctype html>
<?php
   require_once './dimple/dimple.php';
   $d = new dimple( "./content" );
?>
<html lang="en">
   <head>
      <title>Dimple &nbsp;|&nbsp; CMS</title>
      <meta charset="utf-8">
      <meta http-equiv="content-type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no">
      <meta name="description" content="">
      <meta name="author" content="Will Sayin">
      <link href="css/style.css" rel="stylesheet">
      <link href="css/dimple.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
      <script src="js/index.js"></script>
   </head>
   <body onload='markup()'>
      <div id='page'>
         <div id='banner'>
            <div id='title'><a href='https://sayinnet.org/dimple'><h1>dimple CMS</h1></a></div>
         </div><!--banner-->
         <div id='content'>
            <div id='content-main'>
               <?php
                  $d->run();
               ?>
            </div><!--content-main-->
            <div id='content-side' style='float:right'>
               <div id='alltags'>
                  <h6>Tags</h6>
                  <?php echo tagsToSearch( $d->allTags() ); ?>
               </div>
            </div>
         </div><!--content-->
         <div id='footer'>
            <div id='footer-content'>
               Copyright &copy; Will Sayin 2013 - <?php echo date("Y");?><br>
               <a href='https://sayinnet.org'>Homepage</a> | <a href='https://www.github.com/flwftw/'>Github</a> | <a href='https://www.linkedin.com/in/william-sayin/'>LinkedIn</a>
            </div>
         </div><!--footer-->
      </div><!--page-->
   </body>
</html>
