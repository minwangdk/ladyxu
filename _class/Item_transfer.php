<?php
require_once 'Token.php';
require_once 'Database.php';
require_once 'Image_transfer.php';

class Item_transfer {

   public function fetch_items($first = 1, $many = 10) //get an array with details and image filepath from item $first to this many $many.
   {
      $img = new Image_transfer;
      $db = new Database;
      $query = "(SELECT * FROM items ORDER BY created DESC LIMIT ".($first - 1).",{$many}) ORDER BY created DESC";
      $db->query($query);
      $db->execute();
      
      $result = $db->all();      
      
      foreach ($result as $key => $value) {
         $item_id = $result[$key]['id'];
         $path = GALLERY . $item_id;
         
         if (file_exists($path)) 
         {  
            $result[$key]['filepath'] = $img->dir_to_array($path);
         }
      }

      return $result;
   }

   public function single_item($item_id)
   {
      $img = new Image_transfer;
      $db = new Database;
      $query = '  SELECT * 
                  FROM items
                  WHERE id = :id ';
      $db->query($query);
      $db->bind(':id', $item_id);
      $db->execute();
      $result = $db->single(); 

      $path = GALLERY . $item_id;
               
      if (file_exists($path)) 
      {           
         $result['filepath'] = $img->dir_to_array($path);
      } 

      return $result; 
   }

   public function submit_item()
   {
      $db = new Database;
      $index = array(
            'quality',
            'region',
            'period',
            'year',
            'description',
            'details',
            'price'
      );

      $valid_index = array();

      foreach ($_POST as $key => $value)
      {
         if (!empty($value) && in_array($key, $index)) 
         {
            $valid_index[$key] = $value; 
         }
         elseif ($key === 'submit') {            
         }
         else
         {
            echo '</br>Missing index: ' . $key;  
         }
      }

      $index_concat = '';
      $index_colon = '';
      $number_of_valids = count($valid_index);
      $counter = 1;
      foreach ($valid_index as $key => $value)
      {
         if ($counter ===  $number_of_valids)
         {
            $index_concat .= $key;
            $index_colon .= ':' . $key;
         }
         else
         {
            $index_concat .= $key . ', ';
            $index_colon .= ':' . $key . ', ';
            $counter ++;
         }
      }

      try
      {
         $query = 'INSERT INTO items (' .$index_concat.')VALUES ('.$index_colon.')';

         $db->query($query);

         foreach ($valid_index as $key => $value)
         {           
            $db->bind(':' . $key, $valid_index[$key]);                     
         }

         $db->execute();
         $lastID = $db->lastID();

         echo '</br>Saved item number: "'.$lastID.'" to database.';
      }
      catch(PDOException $e)
      {
        echo $e->getMessage();
      }
   
      if ($db->error) 
      {
        echo "DB error: " . $db->error;
      }
      
      $img = new Image_transfer;
      $img->save_images($lastID);
   }
}