<?php
//put in common.php
header('Content-Type: text/html; charset=utf-8'); 

// no cache
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '_class/Database.php';
require_once '_class/Admin_page.php';
$admin = new Admin_page;

// AUTH
$display_admin = FALSE;

// Password authentication
if (count($_POST) > 0)
{
  if (!empty($_POST['password'])) 
  {
  	if ($admin->login()) 
  	{
  		$display_admin = TRUE;
  	}
  	else
  	{
  		echo "Wrong password";
  	}
  }
}

// Cookie authentication
if (count($_COOKIE) > 0 && !empty($_COOKIE['token']))
{
	$db = new Database;

	$query = 'SELECT token, last_access
						FROM admin';

	$db->query($query);

	$token = $db->single();

	if ($_COOKIE['token'] === $token['token'] && 
			$token['last_access'] > (time() - 86400) ) 
	{
		$display_admin = TRUE;
	}
}

// Display
if ($display_admin)
{
	echo $admin->display_admin();

	if (!empty($_POST['submit'])) {
		$admin->submit_item();
	}
} 
else
{
	echo $admin->display_login();
}

if (count($_FILES) > 0)
{
		
?> 
	<pre>
<?php
	print_r($_FILES) 
?>			
		</pre>
<?php

// echo "tempname array: " . count($_FILES['thumbs']['tmp_name']);
// echo "error array: " . count($_FILES['thumbs']['error']);

}
?>

</body>
</html>

