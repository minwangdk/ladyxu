<?php
require_once './partial_php/_header.php';
require_once './_class/Item_transfer.php';
require_once './_class/Gallery_page.php';
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
$item_transfer = new Item_transfer;
$gallery_page = new Gallery_page;

// Load items into $items. Fetch_items (this many from last, this many back)
$items = $item_transfer->fetch_items(1, 20);

$controls = $gallery_page->controls();
// Configure table
$items_per_row = 4;
$item_browser = $gallery_page->item_browser($items, $items_per_row);


$output = "
<section id='item-browser'>
{$controls}
{$item_browser}
</section>";

echo <<<ITEMS
$output
ITEMS;



?>

</body>
</html>


