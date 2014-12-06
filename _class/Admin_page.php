<?php 
require_once '_admin_password.php';
require_once '_class/Database.php';
require_once '_class/Image_transfer.php';


class Admin_page 
{	
	private $admin_password = ADMIN_PASSWORD;

	public function display_login()
	{
		return <<<LOGIN_FORM
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
<form name='newItem' action="" method="post" enctype="multipart/form-data">
	<h1>新项目 New Item</h1>

	<section>
		<h2>规范 Specification</h2>

		<p>
			<label for="quality">
			<span>纯度</br>Quality:</span>
				<select id="quality" name="quality" >
					<option selected disabled></option>
				  <option value="999">.999 Fine</option>
				  <option value="950">.950 French 1st standard</option>
				  <option value="930">.930 Argentium</option>
				  <option value="925">.925 Sterling</option>
				  <option value="900">.900 Coin silver</option>
				  <option value="850">.850 Continental</option>
				  <option value="835">.835 Belgian</option>
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
			<span>年代</br>Era:</span>
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
			<button type="reset" name="reset" onclick="return confirm('您确重置吗? Please confirm or cancel the reset.')"><p>复位 Reset</p></button>
		</p>
	</section>
</form>
FORM_MARKUP;
	}

	public function display_latest_items() 
	{
		//retrieve last 5 records
		echo <<<RECORDS_BEGIN
			<div id='records'>
RECORDS_BEGIN;
		$db = new Database;
		$query = '(SELECT * FROM items ORDER BY id DESC LIMIT 5) ORDER BY id DESC';
		$db->query($query);
		$db->execute();

		$dir = new Image_transfer;
		while ($result = $db->single()) 
		{
			$item_id = $result['id'];
			$path = './gallery/' . $item_id;

			if (file_exists($path)) 
			{		
				$images = $dir->dir_to_array($path);

				echo <<<ITEM_BEGIN
					<div class="pics">
ITEM_BEGIN;

				foreach ($images['thumbs'] as $filename) 
				{
					$filepath = $path . '/thumbs/' . $filename;

					echo <<<IMAGES
						<img class='thumbs' src="$filepath" alt="" height="38">	
IMAGES;
				}

				foreach ($images['pictures'] as $filename) 
				{
					$filepath = $path . '/pictures/' . $filename;
					echo <<<IMAGES
						<img class='pictures' src="$filepath" alt="" height="75">	
IMAGES;
				}

				echo <<<ITEM_END
				</div>
ITEM_END;
			}	
			else
			{
				echo <<<ITEM_BEGIN
					<div class="pics">
ITEM_BEGIN;

				echo <<<NOIMAGE
					<img class='pictures' src="./img/no_img.jpg" alt="" height="75">	
NOIMAGE;

				echo <<<ITEM_END
					</div>
ITEM_END;
			}

			echo <<<DETAILS_BEGIN
				<div class="details">
				<pre>
DETAILS_BEGIN;

			print_r($result);

			echo <<<DETAILS_END
				</pre>
			</div>
DETAILS_END;
			}

			echo <<<RECORDS_END
				</div>
RECORDS_END;
	}

	public function submit_item()
	{
		$db = new Database;
		$img = new Image_transfer;

		$index = array(
				'quality',
				'weight',
				'region',
				'period',
				'year',
				'description',
				'details',
				'price',
				'imgsource'
		);

		$index_concat = '';
		foreach ($index as $key => $value)
		{
			if (!empty($_POST[$value]))
			{
				if ($key === count($index) - 1)
				{
					$index_concat .= $value;
				}
				else
				{
					$index_concat .= $value . ',';
				}
			}
		}

		$index_colon = '';
		foreach ($index as $key => $value) 
		{
			if (!empty($_POST[$value]))
			{
				if ($key === count($index) - 1)
				{
					$index_colon .= ':' . $value;
				}
				else
				{
					$index_colon .= ':' . $value . ',';
				}
			}
		}

		try
		{
			$query = 'INSERT INTO items (' .$index_concat.')
					   VALUES ('.$index_colon.')';

			$db->query($query);

			foreach ($index as $key => $value)
			{
				if (!empty($_POST[$value]))
				{
					$db->bind(':' . $value , $_POST[$value]);
				}
				else
				{
					echo '</br>Missing index: ' . $value;
				}
			}

			$db->execute();
			$lastID = $db->lastID();

			echo '</br>Saved item number: "'.$lastID.'" to database.';
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