<?php
require_once './_class/Database.php';
require_once './_class/Image_transfer.php';

$display = new Image_transfer;
// $items = $display->display_items();

//fetch_items (this many from last, this many back)
$catalogue = $display->fetch_items(1, 20);
$output = '
   <table id="item_table">
      <tbody>';
$items_per_row = 4;
$item_counter = 1;

foreach ($catalogue as $key => $value) {
   $description   = $catalogue[$key]['description'];
   $price         = $catalogue[$key]['price'];
   $status        = '';
   switch ($catalogue[$key]['status'])
   {
      case 'IN STORE':
         $status = '发售';
         $status_color = '#34B015';
         break;

      case 'SOLD':
         $status = '出售';
         $status_color = '#FB7D00';
         break;
      
      default:
         $status = $catalogue[$key]['status'];
         break;
   }

   //get first thumb of each item
   if (!empty($catalogue[$key]['filepath']))
   {
      $thumb = $catalogue[$key]['filepath']['thumbs'][0];
      $item_id = $catalogue[$key]['id'];
      $path = "./gallery/{$item_id}/thumbs/";
      $thumb_path = $path . $thumb;
   }
   else
   {
      $thumb_path = './img/no_img.jpg';
   }

   if ($item_counter === 1)
   {     
      $output .= '
         <tr>';
   }

   $output .= '
            <td>
               <a href="./item.php?id='.$catalogue[$key]['id'].'">
                  <img src="'.$thumb_path.'" alt="">
                  <div class="item-info">
                     <p class="description">'.$description.'</p>
                     <p class="price" style="text-align: right;">'.$price.'元</p>
                     <p class="status" style="position: absolute; bottom: 0px; color:'.$status_color.';">'.$status.'</p>
                  </div>
               </a>
            </td>';

   if ($item_counter === $items_per_row) 
   {
      $output .= '
         </tr>';

      $item_counter = 1;
   } 
   else
   {
      $item_counter += 1;      
   }
}

if ($item_counter !== $items_per_row)
{
   $output .= '
         </tr>';
}


$output .= '
      </tbody>
   </table>';

echo <<<ITEMS
$output
ITEMS;



?>
<pre>
<?php
print_r($catalogue);
?>
</pre>