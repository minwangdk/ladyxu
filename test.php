<?php

require_once '_class/Database.php';

?>

<form action=""  method="post" enctype="multipart/form-data">
    <p>
         <label for="pics">
            <span>图片 Pictures:</span>
            <input type="file" multiple name="pics[]" id="pictures" accept=".jpg,.jpeg"/>
         </label>
      </p>

      <p>
         <button type="submit" name="submit" value="1"><p>创建项目 Create item</p></button>
         
      </p>


</form>

<?php
$item_id = 299;
$db = new Database;
      $query = "  SELECT COUNT(*)
                  FROM items
                  WHERE id > $item_id ";
      $db->query($query);
      $db->execute();
      $result = $db->single();
      $count = $result['COUNT(*)'];

// pre and print
?>
<pre>
<?php
print_r($count);
?>
</pre>
<?php





?>