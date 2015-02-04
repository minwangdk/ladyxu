<?php
require_once 'Token.php';
require_once 'Database.php';
require_once 'vendor/wideimage/WideImage.php';
require_once 'Item_transfer.php';
require_once '_filepath.php';

class Image_transfer extends Item_transfer{

   public function save_images($item_id) 
   {
      if (count($_FILES) > 0 && !empty($_FILES['pictures'])) 
      {
         $upload_path = sprintf(GALLERY . sprintf($item_id) . '/');
         $result = array();
         $msg = array();
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

               // Check if already exists
               if (file_exists($upload_path.$image_size))
               {
                  // Count files
                  $fi = new FilesystemIterator($upload_path.$image_size, FilesystemIterator::SKIP_DOTS);
                  $exist_img = iterator_count($fi);
               }
               else
               {
                  // Create directories
                  $exist_img = 0;
                  if (!mkdir($upload_path.$image_size, 0777, true))
                  {
                     throw new RuntimeException('Failed to create directory.');
                  }
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
                        ($key + $exist_img + 1),
                        $item_id,
                        $random_string
                     )
                  );

                  if (!file_exists(
                     sprintf($upload_path . '%s/%s_%s_%s.jpg',
                        $image_size,
                        ($key + $exist_img + 1),
                        $item_id,
                        $random_string
                     )
                )){
                     throw new RuntimeException('Failed to move uploaded file.');
                  }
               }
            }

            $msg[] = "Pictures uploaded successfully.";
         } 
         catch (RuntimeException $e) 
         {
            $msg[] = "Image upload error: {$e->getMessage()}";
         }

         $result['msg'] = $msg;
         return $result;
      }      
   }

   public function sort_img_order($item_id)
   {
      $item_info = $this->single_item($item_id);

      // First place, second place etc.
      foreach ($_POST['order'] as $place => $old_img_number)
      {
         foreach ($item_info['filepath'] as $size => $images)
         {
            $path = $images[$old_img_number];
            $path_parts = pathinfo($path);

            $bn = $path_parts['basename'];
            $bn_pieces = explode("_", $bn, 2);
            //filename
            $fn = $bn_pieces[1];

            //rename to new place + old filename
            if(!rename($path,
               sprintf($path_parts['dirname'] . DIRECTORY_SEPARATOR . '%s_%s',
                  $place + 1,
                  $fn
               )
            ))
            {
               return "Failed to rename image: $path";
            }
         }
      }

      return "Images sorted and renamed.";            
   }

   public function delete_image($item_id, $img_num)
   {

      // Delete
      $item_info = $this->single_item($item_id);

      foreach ($item_info['filepath'] as $size => $images)
      {
         if (file_exists($images[$img_num]))
         { 
            if (!unlink($images[$img_num]))
            {
               return "Failed to delete image: {$images[$img_num]}";
            }
         }
      }

      // Sort & Rename 
      $item_info = $this->single_item($item_id);

      foreach ($item_info['filepath'] as $size => $images)
      {
         foreach ($images as $key => $path)
         {
            $path_parts = pathinfo($path);
            $bn = $path_parts['basename'];
            $bn_pieces = explode("_", $bn, 2);
            //filename
            $fn = $bn_pieces[1];

            //rename to new place + old filename
            if (!rename($path,
               sprintf($path_parts['dirname'] . DIRECTORY_SEPARATOR . '%s_%s',
                  ($key + 1),
                  $fn
               )
            ))
            {
               return "Failed to rename image: {$path}";
            }
         }
      }
      return "Image deleted, images sorted and renamed.";
   }

   public function delete_folder($item_id)
   {      
      $dir = GALLERY . $item_id;

      try
      {         
         $files = new RecursiveIteratorIterator(
             new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
             RecursiveIteratorIterator::CHILD_FIRST
         );

         foreach ($files as $fileinfo)
         {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');

            if ( !$todo($fileinfo->getRealPath()) )
            {
               throw new RuntimeException('Failed to delete file.');
            }
         }

         if (!rmdir($dir))
         {
            throw new RuntimeException('Failed to delete folder.');
         }
      }
      catch (RuntimeException $e) 
      {
         $msg[] = "Image upload error: {$e->getMessage()}";
         $result['msg'] = $msg;
         return $result;
      }

      $msg[] = "Image folder deleted.";
      $result['msg'] = $msg;
      return $result;
   }
}