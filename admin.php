<?php
require_once 'partial_php/_header.php';
require_once '_class/Database.php';
require_once '_class/Admin_page.php';
require_once '_class/Token.php';
require_once '_class/Item_transfer.php';
require_once '_class/Image_transfer.php';

$admin = new Admin_page;
$item = new Item_transfer;
$img = new Image_transfer;

// Authentication
$display_admin = FALSE;

// Password authentication
if (count($_POST) > 0 && !empty($_POST['password']))
{
	if ($admin->login()) 
	{
		$display_admin = TRUE;

		//set session
		$_SESSION["login"] = TRUE;

		//set cookie
		$token_gen = new Token;
		$token = $token_gen->random_text(); 
		$token_gen->set_token($token);
		setcookie("token", $token, time()+86400, '/');
		
	}
	else
	{
		echo "Wrong password";
	}  
}

// Session authentication
if ($display_admin === FALSE && !empty($_SESSION["login"]) && $_SESSION["login"] === TRUE) {
	$display_admin = TRUE;
}

// Cookie authentication
if ($display_admin === FALSE && count($_COOKIE) > 0 && !empty($_COOKIE['token']))
{
	$db = new Database;

	$query = 'SELECT token, last_access
						FROM admin';

	$db->query($query);
	$db->execute();
	$token = $db->single();

	if ($_COOKIE['token'] === $token['token'] && 
			$token['last_access'] > (time() - 7 * 86400) )
	{
		$display_admin = TRUE;
		//set session
		$_SESSION["login"] = TRUE;
	}
}

//Header
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
	<meta http-equiv="pragma" content="no-cache" />
	<title>Admin Page</title>
	<link rel="stylesheet" href="stylesheets/admin.css">
	<script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
<?php
require_once './partial_php/_banner.php';

$item_id = NULL;
$output = '';
$msg = array();
// Display
if ($display_admin)
{
	if (!empty($_POST['submit']))
   {
      switch ($_POST['submit'])
      {
         case 'new':
            $submit_result = $item->submit_item();
            $msg = array_merge($msg, $submit_result['msg']);
            $item_id = $submit_result['item_id'];

            $save_img_result = $img->save_images($item_id);
            $msg = array_merge($msg, $save_img_result['msg']);
            break;
         
         default:
            if (is_numeric( $_POST['submit'] ))
            {
               $item_id = $_POST['submit'];
               $update_result = $item->update_item($item_id);
               $item_id = $update_result['item_id'];
               $msg = array_merge($msg, $update_result['msg']);

               $save_img_result = $img->save_images($item_id);
               $msg = array_merge($msg, $save_img_result['msg']);
            }
            break;
      }

	}
   else
   {
      if (!empty($_GET['item']))
      {
         $item_id = $_GET['item'];
      }
   }
   
   // New item form
   $output .= $admin->display_form();

   if (!empty($item_id))
   {
      // Hidden edit form
      $output .= $admin->display_form($item_id);
      // Item
      $output .= $admin->display_item($item_id);

   }

   // Display item chooser
   $output .= $admin->display_item_chooser($item_id);

   // Messages
   foreach ($msg as $key => $value)
   {
      $output .= "</br>{$value}";
   }
} 
else
{
	$output .= $admin->display_login();
}

$output .= "
<div id='debug'></div>";

echo <<<ADMIN
$output
ADMIN;


?>

<!-- JS for 'Confirm Navigation' -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>    
<script src="js/vendor/jquery-ui.min.js"></script>  

<script src="js/plugins.js"></script>
<script src="js/admin.js"></script>

</body>
</html>

