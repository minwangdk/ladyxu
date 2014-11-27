<?php 
require_once '_admin_password.php';
require_once '_class/Database.php';
require_once '_class/Token.php';
require_once '_class/IMG_uploader.php';


class Admin_page 
{
	
	private $admin_password = ADMIN_PASSWORD;

	public function display_login()
	{
		return <<<LOGIN_FORM
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin Page</title>
 	<link rel="stylesheet" href="stylesheets/admin.css">
</head>
<body>
	<form action="" method="post">
		<div>
			<input type="password" name="password" />
		</div>
	    
	 	<div class="button">
	        <button type="submit">Login</button>
	    </div>
	</form>
LOGIN_FORM;
	}

	public function login()
	{
		$db = new Database;

		// Attempts checker
		$query = 'SELECT login_attempts, lockout_time
							FROM admin';

		$db->query($query);

		$logins = $db->single();

		if ($logins['login_attempts'] > 2 && $logins['lockout_time'] > (time() - 600) ) {
			echo 'Come back in 10 minutes.';
			return FALSE;
		}

		// Password checker		
		if ($_POST['password'] === $this->admin_password)
		{
			try 
			{
				$token_gen = new Token;
				$token = $token_gen->random_text(); 
				$token_gen->set_token($token);
				setcookie("token", $token, time()+86400, '/');
			}
			catch(Exception $e)
	    {
        echo $e->getMessage();
	    }

			return TRUE;
		}
		else
		{
			$query = '  UPDATE admin
                  SET login_attempts = login_attempts + 1, lockout_time = IF(login_attempts > 2, :time, lockout_time)     ';
      $db->query($query);
      $db->bind(':time', time());
      $db->execute();
			return FALSE;
		}

	}

	public function display_admin() 
	{
		return <<<FORM_MARKUP
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin Page</title>
 	<link rel="stylesheet" href="stylesheets/admin.css">
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data">
		<h1>New Item</h1>

		<section>
			<h2>Specification</h2>

			<p>
				<label for="quality">
				<span>Quality:</span>
					<select id="quality" name="quality" >
						<option selected disabled></option>
					  <option value="999">Fine .999</option>
					  <option value="925">Sterling .925</option>
					  <option value="argent">Argentium .925+</option>
					  <option value="coin">Coin silver .900</option>
					  <option value="silver">Silver</option>
					  <option value="new">New-silver / Silver-filled 5-10%</option>
					  <option value="plated">Silver-plated</option>
					  <option value="other">Bali, Thai or Mexican Silver</option>
					  <option value="unknown">-unknown-</option>
					</select>
				</label>
			</p>

			<p>
				<label for="region">
					<span>Region:</span>
					<select id="region" name="region" >
						<option selected disabled></option>
					  <option value="dk">Denmark</option>
					  <option value="eng">England</option>
					  <option value="eu">Europe</option>
					  <option value="usa">USA</option>
					  <option value="other">Other</option>
					  <option value="unknown">-unknown-</option>
					</select>
				</label>
			</p>


			<p>
				<label for="period">
				<span>Timeperiod:</span>
					<select id="period" name="period">
						<option selected></option>
					  <option value="Before 1700">Before 1700</option>
					  <option value="Early 1700">Early 1700</option>
					  <option value="18th century">18th century</option>
					  <option value="Late 1700">Late 1700</option>
					  <option value="Early 1800">Early 1800</option>
					  <option value="19th century">19th century</option>
					  <option value="Late 1800">Late 1800</option>
					  <option value="Early 1900">Early 1900</option>
					  <option value="20th century">20th century</option>
					  <option value="Late 1900">Late 1900</option>
					  <option value="new">New</option>	
					  <option value="unknown">-unknown-</option>
					</select>
				</label>
			
				<label for="year">
					<input type="text" id="year" name="year" maxlength="4" placeholder=' "1888"'/>
				</label>
			</p>

			<p>
				<label for="weight">
					<span>Weight (grams):</span>
					<input type="text" id="weight" name="weight" />
				</label>
			</p>

			<p>
				<label for="price">
					<span>Price (RMB):</span>
					<input type="text" id="price" name="price" />
				</label>
			</p>
		</section>

		<section>
			<h2>Description</h2>

			<p>
				<label for="description">
					<span>Main description:</span>
					<textarea id="description" name="description" required></textarea>
					<strong><abbr title="required">*</abbr></strong>
				</label>
			</p>

			<p>
				<label for="details">
					<span>Details:</span>
					<textarea id="details" name="details"></textarea>
				</label>
			</p>		
		</section>

		<section>
			<h2>Photos</h2>

			<p>
				<label for="imgsource">
					<span>Imgsource:</span>
					<select id="imgsource" name="imgsource" required>
						<option selected></option>
					  <option value="Bruun-Rasmussen">Bruun-Rasmussen</option>
					  <option value="Lauritz">Lauritz</option>
					  <option value="self">Self</option>
					  <option value="other">Other</option>
					  <option value="unknown">-unknown-</option>
					</select>
					<strong><abbr title="required">*</abbr></strong>
				</label>
			</p>

			<p>
				<label for="thumbs">
					<span>Thumbnails:</span>
					<input type="file" multiple name="thumbs[]" id="thumbs" accept=".jpg,.jpeg"/>
				</label>
			</p>

			<p>
				<label for="pictures">
					<span>Pictures:</span>
					<input type="file" multiple name="pictures[]" id="pictures" accept=".jpg,.jpeg"/>
				</label>
			</p>

		</section>
		  
		<section>
			<p>
				<button type="submit" name="submit" value="1">Create item</button>
				<button type="reset">Reset</button>
			</p>
		</section>
	</form>
FORM_MARKUP;
	}

	public function submit_item()
	{
		$db = new Database;
		$img = new IMG_uploader;

		try
		{
			$query = 'INSERT INTO items (quality, weight, region, period, year, description, details, price, imgsource)
								VALUES (:quality, :weight, :region, :period, :year, :description, :details, :price, :imgsource)';

			$db->query($query);

			$db->bind(':quality',			 	$_POST['quality']);
			$db->bind(':weight',			 	$_POST['weight']);
			$db->bind(':region',			 	$_POST['region']);
			$db->bind(':period',			 	$_POST['period']);
			$db->bind(':year',			 		$_POST['year']);
			$db->bind(':description',		$_POST['description']);
			$db->bind(':details',			 	$_POST['details']);
			$db->bind(':price',			 		$_POST['price']);
			$db->bind(':imgsource',			$_POST['imgsource']);

			$db->execute();
			$lastID = $db->lastID();

			echo 'Saved item number: "'.$lastID.'" to database.';
		}
		catch(PDOException $e)
    {
        echo $e->getMessage();
    }

    if ($db->error) 
    {
    	echo "DB error: " . $db->error;
    }

    $img->save_files($lastID);

	}

}