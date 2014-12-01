<?php
require_once 'partial_php/_header.php';
require_once '_class/Database.php';
require_once '_class/Admin_page.php';
require_once '_class/Token.php';
require_once '_class/Image_transfer.php';
$admin = new Admin_page;

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
	 	<link rel="stylesheet" href="stylesheets/admin-1-12-2014.css">
	 	<script src="js/vendor/modernizr-2.6.2.min.js"></script>
	</head>
<body>
<?php

// Display
if ($display_admin)
{
	echo $admin->display_admin();
	
	if (!empty($_POST['submit'])) {
		$admin->submit_item();
	}

	$admin->display_latest_items();
} 
else
{
	echo $admin->display_login();
}

?>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>      

<script src="js/plugins.js"></script>
<script src="js/admin.js"></script>

</body>
</html>

