<?php 
header('Content-Type: text/html; charset=utf-8'); 
require_once '_class/Database.php';
$db = new Database;

		
		echo "琝琝 is great！";

		$query = 'INSERT INTO items (quality, weight, region, period, year, description, details, price, imgsource)
								VALUES (:quality, :weight, :region, :period, :year, :description, :details, :price, :imgsource)';

			$db->query($query);

			$db->bind(':quality',		''	 	);
			$db->bind(':weight',		''	 	);
			$db->bind(':region',		''	 	);
			$db->bind(':period',		''	 	);
			$db->bind(':year',			'' 		);
			$db->bind(':description',	"琝琝 is great！"	);
			$db->bind(':details',			'' 	);
			$db->bind(':price',			 	''	);
			$db->bind(':imgsource',		''	);
			
			$db->execute();


		$query = 'SELECT *
							FROM items';

		$db->query($query);
		$all_records = $db->all();


		?>
		<pre>
		<?php
		print_r($all_records);

		?>

		</pre>
		<?php





?>