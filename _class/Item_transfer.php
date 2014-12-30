<?php
require_once 'Token.php';
require_once 'Database.php';

class Item_transfer {

   public function dir_to_array($dir) 
   {
      $result = array(); 

      $cdir = scandir($dir); 
      foreach ($cdir as $key => $value) 
      { 
         if (!in_array($value,array(".",".."))) 
         { 
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
            { 
               $result[$value] = $this->dir_to_array($dir . DIRECTORY_SEPARATOR . $value); 
            } 
            else 
            { 
               $result[] = $dir. DIRECTORY_SEPARATOR .$value; 
            } 
         } 
      }       
      return $result; 
   }

   public function fetch_items($first = 1, $many = 10) //get an array with details and image filepath from item $first to this many $many.
   {
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
            $result[$key]['filepath'] = $this->dir_to_array($path);
         }
      }

      return $result;
   }

   public function single_item($item_id)
   {
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
         $result['filepath'] = $this->dir_to_array($path);
      } 

      return $result; 
   }

   public function submit_item()
   {
      $return = array();
      $msg = array();

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
         elseif ($key === 'submit')
         {            
         }
         else
         {
            $msg[] = "Missing index: $key";  
         }
      }

      $index_concat = '';
      $index_colon = '';

      foreach ($valid_index as $key => $value)
      {         
         $index_concat .= $key;
         $index_colon .= ':' . $key;
         
         end($valid_index);
         if ($key !== key($valid_index))
         {
            $index_concat .= ', ';
            $index_colon .= ', ';
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
         $last_id = $db->last_id();

         $msg[] = "Saved item number: {$last_id} to database.";
      }
      catch(PDOException $e)
      {
        $msg[] = $e->getMessage();
      }
   
      if ($db->error) 
      {
        $msg[] = "DB error: {$db->error}";
      }

      $return['item_id'] = $last_id;
      $return['msg'] = $msg;
      return $return;
   }

   public function update_item($item_id)
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
         elseif ($key === 'submit')
         {            
         }
         else
         {
            echo '</br>Missing index: ' . $key;  
         }
      }
      
      try
      {
         $update = '';
         foreach ($valid_index as $key => $value)
         {
            $update .= "{$key} = :{$key}";

            end($valid_index);
            if ($key !== key($valid_index))
            {
               $update .= ", ";
            }
         }

         $query =   "UPDATE items
                     SET $update
                     WHERE id = :id";

         $db->query($query);

         foreach ($valid_index as $key => $value)
         {           
            $db->bind(':' . $key, $value);                     
         }

         $db->bind(":id", $item_id);
         $db->execute();

         echo '</br>Updated item number: "'.$item_id.'" in database.';

         return $item_id;
      }
      catch(PDOException $e)
      {
        echo $e->getMessage();
      }
   
      if ($db->error) 
      {
        echo "DB error: " . $db->error;
      }
      
   }
}