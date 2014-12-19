<?php
require_once 'Token.php';
require_once 'Database.php';
require_once 'vendor/wideimage/WideImage.php';

// Path to gallery
define('GALLERY', '.' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR);

class Image_transfer {

   public function save_images($item_id) 
   {
      if (count($_FILES) > 0)
      { 
         $upload_path = sprintf(GALLERY . sprintf($item_id) . '/');

         if (!empty($_FILES['pictures'])) 
         {
            try
            {           
               // Undefined | $_FILES Corruption Attack
               // If this request falls under any of them, treat it invalid.
               if (count($_FILES['pictures']['tmp_name']) != 
                  count($_FILES['pictures']['error']) )
               {
                  throw new RuntimeException('Invalid parameters.');
               }

               // Check $_FILES['upfile']['error'] value.
               foreach ($_FILES['pictures']['error'] as $error) 
               {
                  switch ($error)
                  {
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
               foreach ($_FILES['pictures']['size'] as $filesize)
               {
                  if ($filesize > 2097152)
                  {
                     throw new RuntimeException('Exceeded filesize limit.');
                  }
               }

               // Check filetype 
               $finfo = new finfo(FILEINFO_MIME_TYPE);
               foreach ($_FILES['pictures']['tmp_name'] as $file) 
               {
                  if (false === $ext = array_search(
                    $finfo->file($file),
                    array(
                     'jpg' => 'image/jpeg'
                    ),
                    true
               )) {
                     throw new RuntimeException('Invalid file format.');
                  }
               }

               // Load images into WideImage 
               $images = array();
               $images = WideImage::loadFromUpload('pictures');
               $token = new Token;

               // Iterate over the three image sizes: L , M , S 
               for ($i=0; $i < 3; $i++) 
               { 
                  switch ($i) 
                  {
                     case 0:
                        $image_size = 'large';
                        $image_width = 1920;
                        $image_height = 1080;
                        break;
                     case 1:
                        $image_size = 'medium';
                        $image_width = 300;
                        $image_height = 600;
                        break;
                     case 2:
                        $image_size = 'small';
                        $image_width = 160;
                        $image_height = 400;
                        break;
                     default:
                        # code...
                        break;
                  }

                  // Create directories
                  if (!mkdir($upload_path.$image_size, 0777, true))
                  {
                     throw new RuntimeException('Failed to create directory.');
                  }

                  if (!is_writable($upload_path.$image_size))
                  {
                     throw new RuntimeException('You cannot upload to the specified directory, please CHMOD it to 777.');
                  }
                  
                  // Resize and move files to dir
                  foreach ($images as $key => $file)
                  {
                     if (!$resized = $images[$key]->resizeDown($image_width, $image_height))
                     {
                        throw new RuntimeException('Failed to resize uploaded file.');
                     }
                     
                     $random_string = $token->random_text();
                     $resized->saveToFile(
                        sprintf($upload_path . '%s/%s_%s_%s.jpg',
                           $image_size,
                           ($key + 1),
                           $item_id,
                           $random_string
                        )
                     );

                     if (!file_exists(
                        sprintf($upload_path . '%s/%s_%s_%s.jpg',
                           $image_size,
                           ($key + 1),
                           $item_id,
                           $random_string
                        )
                     )
                     )
                     {
                        throw new RuntimeException('Failed to move uploaded file.');
                     }
                  }
               }

               echo "</br> pictures uploaded successfully.";
            } 
            catch (RuntimeException $e) 
            {
               echo "</br>Image upload error: " . $e->getMessage();
            }
         }
      }
   }

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
}