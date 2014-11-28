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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Admin Page</title>
 	<link rel="stylesheet" href="stylesheets/admin.css">
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data">
		<h1>新项目 New Item</h1>

		<section>
			<h2>规范 Specification</h2>

			<p>
				<label for="quality">
				<span>质量</br>Quality:</span>
					<select id="quality" name="quality" >
						<option selected disabled></option>
					  <option value="999">.999 Fine</option>
					  <option value="950">.950 French 1st standard</option>
					  <option value="925">.925 Sterling</option>
					  <option value="900">.900 Coin silver</option>
					  <option value="850">.850 Continental</option>
					  <option value="830">.830 Scandinavian</option>
					  <option value="800">.800 German</option>
					  <option value="silver">Silver</option>
					  <option value="new">.100 New-silver</option>
					  <option value="plated">.001 Silver-plated</option>
					  <option value="other">Bali, Thai or Mexican Silver</option>
					  <option value="unknown">-unknown-</option>
					</select>
				</label>
			</p>

			<p>
				<label for="region">
					<span>地区</br>Region:</span>
					<select id="region" name="region" >
						<option selected disabled></option>
					  <option value="Denmark">Denmark</option>
					  <option value="England">England</option>
					  <option value="Germany">Germany</option>
					  <option value="Europe">Europe</option>
					  <option value="America">America</option>
					  <option value="China">China</option>
					  <option value="Japan">Japan</option>
					  <option value="SE Asia">SE Asia</option>
					  <option value="Other">Other</option>
					  <option value="unknown">-unknown-</option>
					</select>
				</label>
			</p>


			<p>
				<label for="period">
				<span>时代</br>Era:</span>
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
					  <option value="New">New</option>	
					  <option value="unknown">-unknown-</option>
					</select>
				</label>
			
				<label for="year">
					<input type="text" id="year" name="year" maxlength="4" placeholder=' "1888"'/>
				</label>
			</p>

			<p>
				<label for="weight">
					<span>重量（克）</br>Weight(grams):</span>
					<input type="text" id="weight" name="weight" />
				</label>
			</p>

			<p>
				<label for="price">
					<span>现价（元）</br>Price(Rmb):</span>
					<input type="text" id="price" name="price" />
				</label>
			</p>
		</section>

		<section>
			<h2>描述 Description</h2>

			<p>
				<label for="description">
					<span>主要描写</br>Main description:</span>
					<textarea id="description" name="description" required></textarea>
					<strong><abbr title="required">*</abbr></strong>
				</label>
			</p>

			<p>
				<label for="details">
					<span>详细信息</br>Details:</span>
					<textarea id="details" name="details"></textarea>
				</label>
			</p>		
		</section>

		<section>
			<h2>照片 Photos</h2>

			<p>
				<label for="imgsource">
					<span>照片来源 Imgsource:</span>
					<select id="imgsource" name="imgsource" required>
						<option selected></option>
					  <option value="br">Bruun-Rasmussen</option>
					  <option value="lau">Lauritz</option>
					  <option value="self">Self</option>
					  <option value="other">Other</option>
					  <option value="unknown">-unknown-</option>
					</select>
					<strong><abbr title="required">*</abbr></strong>
				</label>
			</p>

			<p>
				<label for="thumbs">
					<span>缩略图 Thumbnails:</span>
					<input type="file" multiple name="thumbs[]" id="thumbs" accept=".jpg,.jpeg"/>
				</label>
			</p>

			<p>
				<label for="pictures">
					<span>图片 Pictures:</span>
					<input type="file" multiple name="pictures[]" id="pictures" accept=".jpg,.jpeg"/>
				</label>
			</p>

		</section>
		  
		<section id='form-buttons'>
			<p>
				<button type="submit" name="submit" value="1"><p>创建项目 Create item</p></button>
				<button type="reset"><p>复位 Reset</p></button>
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