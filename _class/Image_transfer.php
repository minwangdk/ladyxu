<?php
require_once 'Token.php';
require_once 'Database.php';

// Path to gallery
define('GALLERY', '.' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR);

class Image_transfer {

   public function save_files($path) 
   {
      if (count($_FILES) > 0)
      { 
         for ($i=0; $i < 2; $i++) { 
            switch ($i) {
               case '0':
                  $file_group = 'thumbs';
                  $max_filesize = 30720;
                  break;

               case '1':
                  $file_group = 'pictures';
                  $max_filesize = 2097152;
                  break;
            }

            $upload_path = sprintf(GALLERY . sprintf($path) . '/' . $file_group);

            // Error detection
            if (!empty($_FILES[$file_group])) 
            {
               try
               {           
                  // Undefined | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (count($_FILES[$file_group]['tmp_name']) != 
                     count($_FILES[$file_group]['error']) )
                {
                 throw new RuntimeException('Invalid parameters.');
                }

                  // Check $_FILES['upfile']['error'] value.
                  foreach ($_FILES[$file_group]['error'] as $error) 
                  {
                   switch ($error) {
                       case UPLOAD_ERR_OK:
                           break;
                       case UPLOAD_ERR_NO_FILE:
                           throw new RuntimeException('No file sent.');
                       case UPLOAD_ERR_INI_SIZE:
                       case UPLOAD_ERR_FORM_SIZE:
                           throw new RuntimeException('Exceeded filesize limit.');
                       default:
                           throw new RuntimeException('Unknown errors.');
                   }
                  }

                  // Check filesize
                  foreach ($_FILES[$file_group]['size'] as $filesize)
                  {
                     if ($filesize > $max_filesize)
                     {
                        {
                       throw new RuntimeException('Exceeded filesize limit.');
                      }
                     }
                  }

                // Check filetype 
                  $finfo = new finfo(FILEINFO_MIME_TYPE);
                foreach ($_FILES[$file_group]['tmp_name'] as $file) 
                  {
                     if (false === $ext = array_search(
                       $finfo->file($file),
                       array(
                        'jpg' => 'image/jpeg'
                       ),
                       true
             ))   {
                  throw new RuntimeException('Invalid file format.');
                  }
                  }

                  // Create directories
                  if (!mkdir($upload_path, 0777, true))
                  {
                     throw new RuntimeException('Failed to create directory.');
                  }

                  if (!is_writable($upload_path))
                  {
                     throw new RuntimeException('You cannot upload to the specified directory, please CHMOD it to 777.');
                  }

                  // Move files to dir
                  $token = new Token;
                  foreach ($_FILES[$file_group]['tmp_name'] as $key => $file)
                  {
                     if (!move_uploaded_file(
                     $file,
                     sprintf($upload_path . '/%s_%s.jpg',
                           ($key + 1),
                           $token->random_text()
                     )
                  )) {
                        throw new RuntimeException('Failed to move uploaded file.');
                     }
                  }

                  switch ($file_group) {
                     case 'thumbs':
                        $img_group = 'Thumbs';
                        break;
                     
                     case 'pictures':
                        $img_group = 'Pictures';
                        break;                     
                  }
                  echo "</br>" . $img_group . " uploaded successfully.";
               } 
               catch (RuntimeException $e) 
               {
                echo "</br>Image upload error: " . $e->getMessage();
               }
            }
         }
      }
   }

   public function dir_to_array($dir) { 

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
}