<?php 

require_once 'Token.php';

class IMG_uploader {

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

				$upload_path = sprintf('./gallery/' . sprintf($path) . '/' . $file_group);

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
			    ))	{
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
					            $token->random_text(),
					            ($key + 1)
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
				    echo "Image upload error: " . $e->getMessage();
					}
				}
			}
		}
	}
}