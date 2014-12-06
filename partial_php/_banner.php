<?php
echo <<<BANNER
<div id="header-container">           
   <header role="banner">
      <div id="curlysquare">
         <div id="bars"></div>
         <div id="left-ear">
            <div id="le-1"></div>
            <div id="le-2"></div>
            <div id="le-3"></div>
         </div>
         <div id="right-ear">
            <div id="re-1"></div>
            <div id="re-2"></div>
            <div id="re-3"></div>
         </div>
      </div>
      <div id="logo">
         <p>雪薇</br>古董</br></p>
      </div>

      <nav role="navigation">
         <ul>
            <li>画廊<a href="./gallery.php"></a>
               <ul>
                  <li>
                     <h3>画廊<a href="./gallery.php"></a></h3>
                  </li>
                  <li>银器<a href="./silverware"></a></li>
                  <li>木制品<a href="./wood"></a></li>
                  <li>瓷器<a href="./porcelain"></a></li>
               </ul>
            </li>
            <li>關於雪薇古董<a href="/about"></a></li>
         </ul>
      </nav>
      <figure id="crown">
         <img src="crown.png" alt="crown">
      </figure>
   </header>
</div>

BANNER;
