<?php
require_once './partial_php/_header.php';
require_once './_class/Item_transfer.php';
require_once './_class/Item_page.php';
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

  <link rel="stylesheet" href="stylesheets/item.css">
  <script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
<?php
require_once './partial_php/_banner.php';

// Put item data in $item array
$item_transfer = new Item_transfer;
$item_id = $_GET['id'];
$item = $item_transfer->single_item($item_id);
$item_page = new Item_page;

// Build page sections
$medium_image_box = $item_page->medium_image_box($item);
$item_info_box = $item_page->item_info_box($item);
$item_buttons = $item_page->item_buttons();

$output = "
<section id='single_item'>   
   {$medium_image_box}   
   {$item_info_box}
   {$item_buttons}
</section>";

echo <<<ITEMS
$output
ITEMS;


?>
<!-- 
<pre>
<?php
// print_r($);
?>
</pre>
 -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>       

<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

<script src="js/vendor/jssor.js"></script>
<script src="js/vendor/jssor.slider.js"></script>
<script>
jQuery(document).ready(function ($) {

    var _SlideshowTransitions = [
      {$Duration:700,$Opacity:2,$Brother:{$Duration:1000,$Opacity:2}}
    ];

    var options = { 
      $AutoPlay: true,
      $FillMode: 1,
      $SlideshowOptions: {
          $Class: $JssorSlideshowRunner$,
          $Transitions: _SlideshowTransitions,
          $TransitionsOrder: 1,
          $ShowLink: true
      }
    };
    var jssor_slider1 = new $JssorSlider$('slider1_container', options);
});
</script>

</body>
</html>


