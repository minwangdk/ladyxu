<?php
require_once 'Token.php';
require_once 'Database.php';
require_once '_filepath.php';


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

   public function fetch_items($first = NULL, $many = NULL) //get an array with details and image filepath from item $first to this many $many.
   {
      if ($first === NULL)
      {
         $first = 1;
      }

      if ($many === NULL)
      {
         $many = 10;
      }

      $db = new Database;
      $first_entry = $first - 1;
      $query = "(SELECT * FROM items ORDER BY created DESC LIMIT {$first_entry},{$many}) ORDER BY created DESC";
      $db->query($query);
      $db->execute();
      
      $result = $db->all();      
      
      foreach ($result as $key => $value)
      {
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
            'price',
            'status'
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

         $msg[] = "Updated item number: {$item_id} in database.";

      }
      catch(PDOException $e)
      {
        $msg[] = $e->getMessage();
      }
   
      if ($db->error) 
      {
        $msg[] = "DB error: {$db->error}";
      }

      $return['item_id'] = $item_id;
      $return['msg'] = $msg;
      return $return;
   }

   public function delete_item($item_id)
   {
      $return = array();
      $msg = array();

      try
      {
         $db = new Database;

         $query =   "DELETE FROM items
                     WHERE id = :id";

         $db->query($query);
         $db->bind(":id", $item_id);
         $db->execute();
         $success = $db->row_count();
      }
      catch(PDOException $e)
      {
         $msg[] = $e->getMessage();
         $return['item_id'] = $item_id;
         $return['msg'] = $msg;
         return $return;
      }
   
      if ($db->error) 
      {
        $msg[] = "DB error: {$db->error}";
      }

      if ($success >= 0)
      {
         $msg[] = "Item #{$item_id} deleted from DB.";
      }

      $return['item_id'] = $item_id;
      $return['msg'] = $msg;
      return $return;
   }
}