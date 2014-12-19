<?php
require_once './_class/Database.php';
require_once './_class/Item_transfer.php';

class Gallery_page
{
   public function controls()
   {
      $controls = 
  "   <div id='controls'>
      <div id='pages'></div>
   </div>";

      return $controls;
   }

   public function item_browser($items, $items_per_row)
   {
      $data_cells = array();

      // Put items/data cells in array $data_cells
      foreach ($items as $key => $value) {
         $item_link = './item.php?id="{$items[$key]["id"]}';
         $description   = $items[$key]['description'];
         $price         = $items[$key]['price'];
         
         //get first thumb of each item
         if (!empty($items[$key]['filepath']['small'][0]))
         {
            $thumb_path = $items[$key]['filepath']['small'][0];
            $item_id = $items[$key]['id'];
         }
         else // "No-Image picture"
         {
            $thumb_path = "./img/no_img.jpg";
         }

         $data_cells[] = "
            <td>
               <a href='{$item_link}'>
                  <img src='{$thumb_path}' alt=''>
                  <div class='item-info'>
                     <p class='description'>{$description}</p>
                     <p class='price' style='text-align: right;'>{$price}元</p>
                  </div>
               </a>
            </td>";
      }

      $table_data = '';
      $item_counter = 0;
      foreach ($data_cells as $value)
      {
         if ($item_counter === 1)
         {     
            $table_data .= "\n         <tr>";
         }

         if ($item_counter === 0)
         {     
            $table_data .= "        <tr>";
            $item_counter = 1;
         }

         $table_data .= $value;

         if ($item_counter === $items_per_row) 
         {
            $table_data .= "
         </tr>";
            $item_counter = 1;
         } 
         else
         {
            $item_counter += 1;      
         }
      }

      if ($item_counter !== 1)
      {
         $table_data .= "
         </tr>";
      }

      $item_browser = 
  "   <table id='item_table'>
      <tbody>
{$table_data}
      </tbody>
   </table>";

      return $item_browser;
   }
}