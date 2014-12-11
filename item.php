<?php
require_once './partial_php/_header.php';
require_once './_class/Database.php';
require_once './_class/Image_transfer.php';
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
$item_id = $_GET['id'];
$item_fetcher = new Image_transfer;
$item = $item_fetcher->single_item($item_id);

// Thumbs box
$thumbs = '';
foreach ($item['filepath']['thumbs'] as $value) {
   $thumbs .= '
      <img src="'.$value.'" alt="" />';
}

// Item info box
$title = $item['description'];
$id = '批号 <strong>' . $item['id'] . '</strong>';
$region = '';
switch ($item['region']) 
{
   case 'Denmark':
      $region = '丹麦';
      break;
   case 'England':
      $region = '英国';
      break;
   case 'Germany':
      $region = '德国';
      break;
   case 'Europe':
      $region = '欧洲';
      break;
   case 'America':
      $region = '美国';
      break;
   case 'China':
      $region = '中国';
      break;
   case 'Japan':
      $region = '日本';
      break;
   case 'SE Asia':
      $region = '东南亚';
      break;
   case 'Other':
      $region = '挂零其他';
      break;
   case 'unknown':
      $region = '';
      break;
   default:
      # code...
      break;
}

$age = '';
if (!empty($item['year'])) 
{
   $age = $item['year'];
}
else
{   
   switch ($item['period']) {
      case 'Before 1700':
         $age = '18世纪之前';
         break;
      case '18th century':
         $age = '十八世纪';
         break;
      case 'Early 18th':
         $age = '十八世纪初';
         break;
      case 'Mid 18th':
         $age = '十八世纪中叶';
         break;
      case 'Late 18th':
         $age = '十八世纪末';
         break;
      case '19th century':
         $age = '十九世纪';
         break;
      case 'Early 19th':
         $age = '十九世纪初';
         break;
      case 'Mid 19th':
         $age = '十九世纪中叶';
         break;
      case 'Late 19th':
         $age = '十九世纪末';
         break;
      case '20th century':
         $age = '二十世纪';
         break;
      case 'Early 20th':
         $age = '二十世纪初';
         break;
      case 'Mid 20th':
         $age = '二十世纪中叶';
         break;
      case 'Late 20th':
         $age = '二十世纪末';
         break;
      case 'New':
         $age = '新的';
         break;
      default:
         # code...
         break;
   }
}

$quality = '';
switch ($item['quality']) 
{   
   case '999':
      $quality = '999';
      break;
   case '980':
      $quality = '980';
      break;
   case '958':
      $quality = 'Britannia 958';
      break;
   case '950':
      $quality = 'French 1st standard 950';
      break;
   case '925':
      $quality = 'sterling 925';
      break;
   case '900':
      $quality = '900';
      break;
   case '850':
      $quality = 'Continental 850';
      break;
   case '835':
      $quality = '835';
      break;
   case '833':
      $quality = '833';
      break;
   case '830':
      $quality = 'Scandinavian 830';
      break;
   case '800':
      $quality = 'German 800';
      break;
   case '750':
      $quality = '750';
      break;
   case 'silver':
      $quality = '银';
      break;
   case 'new':
      $quality = '新银';
      break;
   case 'plated':
      $quality = '镀银';
      break;
   case 'other':
      $quality = '';
      break;
   default:
      # code...
      break;

}

$details = $item['details'];
$weight_and_dimensions = $item['dimensions'] .' 公分</br>'. $item['weight'] . ' 克 ';
$price = $item['price'] . '元';
$status = '';
$status_color = '';
switch ($item['status']) {
   case 'IN STORE':
      $status = '出售';
      $status_color = 'color: #23970F;';
      break;
   case 'SOLD':
      $status = '出售';
      $status_color = 'color: #A20409;';
      break;   
   default:
      # code...
      break;
};

$output = '
<section id="single_item">
   <div id="thumbs">  
      '.$thumbs.'
   </div>
   <div id="item_info">
      <div class="tag_hole">
         <div class="tag_hole_hole"></div>
      </div>
      <h3>'.$title.'</h3> 
      <p id="item_id">'.$id.'</p>
      <div id="specification">
         <p><span>'.$region.'</span><span>'.$age.'</span><span id="quality">'.$quality.'</span></p>
      </div>
      <div id="details"><p>'.$details.'</p></div>  
      <div id="bottom_line">
         <p id="price_and_status"><span id="price">'.$price.'</span><span id="status" style="'.$status_color.'">'.$status.'</span></p>
         <p id="weight_and_d">'.$weight_and_dimensions.'</p>
      </div>    
   </div>
   <div id="item_buttons">
      <a href="buy.php" style="text-decoration: none;">
         <div class="button_container">        
            <button class="item_button" name="buy">购买这个批</button>
            <div class="light"></div>
         </div>
      </a>
         <div class="button_container">
            <button class="item_button" name="wish">保存为以后考虑</button>
            <div class="light"></div>
         </div>
   </div>
</section>
';


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


