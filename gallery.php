<?php
require_once './partial_php/_header.php';
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

  <link rel="stylesheet" href="stylesheets/gallery.css">
  <script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>

<body>
   

<?php
require_once './partial_php/_banner.php';
require_once './partial_php/_category_bar.php';
?>

<section id='item-browser'>   
   <div id='controls'>
      <div id="pages"></div>
   </div>
   <?php
   require_once './partial_php/_item_display.php';
   ?>
</section>



</body>
</html>


