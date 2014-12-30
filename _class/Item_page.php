<?php

class Item_page
{
   public function medium_image_box($item)
   {
      $medium_img_box = '<div id="medium_img_box">';

      foreach ($item['filepath']['medium'] as $value)
      {
         $medium_img_box .=   "\n      <img src='".$value."' alt='' />";
      }

      $medium_img_box .=   "\n   </div>";

      return $medium_img_box;
   }

   public function item_info_box($item)
   {
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
         switch ($item['period'])
         {
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
      $price = $item['price'] . '元';
      // $status = '';
      // $status_color = '';
      // switch ($item['status']) {
      //    case 'IN STORE':
      //       $status = '出售';
      //       $status_color = 'color: #23970F;';
      //       break;
      //    case 'SOLD':
      //       $status = '出售';
      //       $status_color = 'color: #A20409;';
      //       break;   
      //    default:
      //       # code...
      //       break;
      // };

      $item_info_box =
   "<div id='item_info'>
      <div class='tag_hole'>
         <div class='tag_hole_hole'></div>
      </div>
      <h3>{$title}</h3> 
      <p id='item_id'>{$id}</p>
      <div id='specification'>
         <p><span>{$region}</span><span>{$age}</span><span id='quality'>{$quality}</span></p>
      </div>
      <div id='item_details'>
         <p>{$details}</p>
      </div>  
      <div id='bottom_line'>
         <p id='price'>{$price}</span></p>
      </div>    
   </div>";

      return $item_info_box;
   }

   public function item_buttons()
   {
      $item_buttons =
      "<div id='item_buttons'>
      <a href='buy.php' style='text-decoration: none;'>
         <div class='button_container'>        
            <button class='item_button' name='buy'>购买这个批</button>
            <div class='light'></div>
         </div>
      </a>
      <div class='button_container'>
         <button class='item_button' name='wish'>保存为以后考虑</button>
         <div class='light'></div>
      </div>
   </div>";

      return $item_buttons;
   }
}



