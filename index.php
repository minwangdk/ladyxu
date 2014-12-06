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

    <link rel="stylesheet" href="stylesheets/index.css">
    <script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
    <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Add your site or application content here -->
<?php
require_once './partial_php/_banner.php';
?>
    
    

    <div id="slider1_container" style="position: relative; margin: 0 auto;
    top: 100px; left: 0px; width: 1300px; height: 500px; overflow: hidden;">
      <!-- Slides Container -->
      <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1300px;
            height: 500px; overflow: hidden;">
          <div><img u="image" src="img/plates.jpg" /></div>
          <div><img u="image" src="img/porcelain.jpg" /></div>
          <div><img u="image" src="img/pot.jpg" /></div>
          <div><img u="image" src="img/table.jpg" /></div>
          <div><img u="image" src="img/vase.jpg" /></div>
      </div>
    </div>
   
































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

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <!--
    <script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create','UA-XXXXX-X');ga('send','pageview');
    </script>
    -->

</body>     
</html>
