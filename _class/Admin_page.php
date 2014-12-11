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
              <option value="980">.980 Mexico 1930 - 1945</option>
              <option value="958">.958 Britannia</option>
				  <option value="950">.950 French 1st standard</option>
				  <option value="925">.925 Sterling</option>
				  <option value="900">.900 Coin silver</option>
				  <option value="850">.850 Continental</option>
				  <option value="835">.835 Belgian</option>
              <option value="833">.833 Dutch</option>
				  <option value="830">.830 Scandinavian</option>
				  <option value="800">.800 German</option>
              <option value="750">.750 Swiss</option>
				  <option value="silver">Silver</option>
				  <option value="new">.100 镍银 New-silver</option>
				  <option value="plated">.001 镀银 Silver-plated</option>
				  <option value="other">其他的 Other</option>
				</select>
			</label>
		</p>

		<p>
			<label for="region">
				<span>地区</br>Region:</span>
				<select id="region" name="region" >
					<option selected disabled></option>
				  <option value="Denmark">丹麦 Denmark</option>
				  <option value="England">英国 England</option>
				  <option value="Germany">德国 Germany</option>
				  <option value="Europe">欧洲 Europe</option>
				  <option value="America">美国 America</option>
				  <option value="China">中国 China</option>
				  <option value="Japan">日本 Japan</option>
				  <option value="SE Asia">东南亚 SE Asia</option>
				  <option value="Other">其他的 Other</option>
				</select>
			</label>
		</p>


		<p>
			<label for="period">
			<span>年代</br>Era:</span>
				<select id="period" name="period">
					<option selected></option>
				  <option value="Before 1700">1700之前 Before 1700</option>              
              <option value="18th century">18世纪 (1701-1800)</option>
				  <option value="Early 18th">18世纪初</option>
				  <option value="Mid 18th">18世纪中叶</option>
              <option value="Late 18th">18世纪末</option>
				  <option value="19th century">19世纪 (1801-1900)</option>
              <option value="Early 19th">19世纪初</option>
              <option value="Mid 19th">19世纪中叶</option>
              <option value="Late 19th">19世纪末</option>
              <option value="20th century">20世纪 (1901-2000)</option>
              <option value="Early 20th">20世纪初</option>
              <option value="Mid 20th">20世纪中叶</option>
              <option value="Late 20th">20世纪末</option>
				  <option value="New">新的 New</option>
				</select>
			</label>
		
			<label for="year">
            <span>年(yyyy)</br>Year:</span>
				<input type="text" id="year" name="year" maxlength="4" placeholder='比如: 1888'/>
			</label>
		</p>

		<p>
			<label for="weight">
				<span>重量（克）</br>Weight(grams):</span>
				<input type="text" id="weight" name="weight" />
			</label>
		</p>

      <p>
         <label for="dimensions">
            <span>尺寸（公分）</br>Dimensions(cm):</span>
            <input type="text" id="dimensions" name="dimensions" placeholder="10 x 15 x 8.5" />
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
			$path = GALLERY . $item_id;

			if (file_exists($path)) 
			{		
				$images = $dir->dir_to_array($path);

				echo <<<ITEM_BEGIN
					<div class="pics">
ITEM_BEGIN;

				foreach ($images['thumbs'] as $filename) 
				{
					$filepath = $filename;

					echo <<<IMAGES
						<img class='thumbs' src="$filepath" alt="" height="38">	
IMAGES;
				}

				foreach ($images['pictures'] as $filename) 
				{
					$filepath = $filename;
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
				'imgsource',
            'dimensions'
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