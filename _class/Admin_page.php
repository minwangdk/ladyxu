<?php 
require_once '_admin_password.php';
require_once '_class/Database.php';
require_once '_class/Item_transfer.php';
require_once '_class/Item_page.php';
require_once '_class/Token.php';

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

   public function display_form($item_id = NULL) 
   {
      //quality
      $q925 = '';
      $q830 = '';
      $q800 = '';
      $q999 = '';
      $q980 = '';
      $q958 = '';
      $q950 = '';
      $q925 = '';
      $q900 = '';
      $q850 = '';
      $q835 = '';
      $q833 = '';
      $q830 = '';
      $q800 = '';
      $q750 = '';
      $qsilver = '';
      $qnew = '';
      $qplated = '';
      $qother = '';
      $qselected = 'selected';
      
      //region
      $rDenmark = '';
      $rEngland = '';
      $rGermany = '';
      $rEurope = '';
      $rAmerica = '';
      $rChina = '';
      $rJapan = '';
      $rSE_Asia = '';
      $rOther = '';
      $rselected = 'selected';

      //period
      $pBefore_1700 = '';
      $p18th_century = '';
      $pEarly_18th = '';
      $pMid_18th = '';
      $pLate_18th = '';
      $p19th_century = '';
      $pEarly_19th = '';
      $pMid_19th = '';
      $pLate_19th = '';
      $p20th_century = '';
      $pEarly_20th = '';
      $pMid_20th = '';
      $pLate_20th = '';
      $pNew = '';
      $pselected = 'selected';

      //status
      $sINSTORE = '';
      $sSOLD = '';
      $sselected = 'selected';

      $year = '';
      $price = '';
      $description = '';
      $details = '';

      $secondary_button = '<button type="reset" name="reset"><p>复位 Reset</p></button>';
      $status_input = '';

      $submit = 'new';
      $submit_label = '创建项目 Create item';
      $title = '新项目 New Item';
      $created_date = '';

      // Display form for updating item
      if (isset($item_id) && is_numeric($item_id))
      {
         $item = new Item_transfer;
         $item_info = $item->single_item($item_id);

         if (!empty($item_info['quality']))
         {
            $quality = "q" . $item_info['quality'];
            $$quality = 'selected';
            $qselected = '';
         }

         if (!empty($item_info['region']))
         {
            $region = "r" . $item_info['region'];
            $$region = 'selected';
            $rselected = '';
         }

         if (!empty($item_info['period']))
         {
            $period = "p" . $item_info['period'];
            $$period = 'selected';
            $pselected = '';
         }

         if (!empty($item_info['status']))
         {
            $status = "s" . $item_info['status'];
            $$status = 'selected';
            $sselected = '';
         }

         $year =        $item_info['year'];
         $price =       $item_info['price'];
         $description = $item_info['description'];
         $details =     $item_info['details'];

         $secondary_button = "<button type='button' name='delete' ><p>删除 Delete</p></button>";
         $status_input = "
         <p>
            <label for='status'>
               <span>状态</br>Status:</span>
               <select id='status' name='status' >
                  <option $sselected disabled></option>
                  <option $sINSTORE value='INSTORE'>库存 IN STORE</option>
                  <option $sSOLD value='SOLD'>卖出 SOLD</option>                  
               </select>
            </label>
         </p>
         ";

         $submit = $item_id;
         $submit_label = '修改 Edit item';
         $title = "修改项目 Edit Item #{$item_id}";
         $created_date = $item_info['created'];
      }

      return <<<FORM_MARKUP
<div id='new_item_form'>
   <form name='newItem' action="" method="post" enctype="multipart/form-data">
      <h1>$title</h1>

      <section>
         <h3>规范 Specification {$created_date}</h3>
         <p>
            <label for="quality">
            <span>纯度</br>Quality:</span>
               <select id="quality" name="quality" >
                  <option $q925 value="925">.925 Sterling</option>
                  <option $q830 value="830">.830 Scandinavian</option>
                  <option $q800 value="800">.800 German</option>
                  <option disabled $qselected></option>
                  <option $q999 value="999">.999 Fine</option>
                  <option $q980 value="980">.980 Mexico 1930 - 1945</option>
                  <option $q958 value="958">.958 Britannia</option>
                  <option $q950 value="950">.950 French 1st standard</option>
                  <option $q925 value="925">.925 Sterling</option>
                  <option $q900 value="900">.900 Coin silver</option>
                  <option $q850 value="850">.850 Continental</option>
                  <option $q835 value="835">.835 Belgian</option>
                  <option $q833 value="833">.833 Dutch</option>
                  <option $q830 value="830">.830 Scandinavian</option>
                  <option $q800 value="800">.800 German</option>
                  <option $q750 value="750">.750 Swiss</option>
                  <option $qsilver value="silver">Silver</option>
                  <option $qnew value="new">.100 镍银 New-silver</option>
                  <option $qplated value="plated">.001 镀银 Silver-plated</option>
                  <option $qother value="other">其他的 Other</option>
               </select>
            </label>
         </p>
         <p>
            <label for="region">
               <span>地区</br>Region:</span>
               <select id="region" name="region" >
                  <option $rselected disabled></option>
                  <option $rDenmark value="Denmark">丹麦 Denmark</option>
                  <option $rEngland value="England">英国 England</option>
                  <option $rGermany value="Germany">德国 Germany</option>
                  <option $rEurope value="Europe">欧洲 Europe</option>
                  <option $rAmerica value="America">美国 America</option>
                  <option $rChina value="China">中国 China</option>
                  <option $rJapan value="Japan">日本 Japan</option>
                  <option $rSE_Asia value="SE_Asia">东南亚 SE Asia</option>
                  <option $rOther value="Other">其他的 Other</option>
               </select>
            </label>
         </p>
         <p>
            <label for="period">
            <span>年代</br>Era:</span>
               <select id="period" name="period">
                  <option $pselected></option>
                 <option $pBefore_1700 value="Before_1700">1700之前 Before 1700</option>              
                 <option $p18th_century value="18th_century">18世纪 (1701-1800)</option>
                 <option $pEarly_18th value="Early_18th">18世纪初</option>
                 <option $pMid_18th value="Mid_18th">18世纪中叶</option>
                 <option $pLate_18th value="Late_18th">18世纪末</option>
                 <option $p19th_century value="19th_century">19世纪 (1801-1900)</option>
                 <option $pEarly_19th value="Early_19th">19世纪初</option>
                 <option $pMid_19th value="Mid_19th">19世纪中叶</option>
                 <option $pLate_19th value="Late_19th">19世纪末</option>
                 <option $p20th_century value="20th_century">20世纪 (1901-2000)</option>
                 <option $pEarly_20th value="Early_20th">20世纪初</option>
                 <option $pMid_20th value="Mid_20th">20世纪中叶</option>
                 <option $pLate_20th value="Late_20th">20世纪末</option>
                 <option $pNew value="New">新的 New</option>
               </select>
            </label>
         
            <label for="year">
               <span>年(yyyy)</br>Year:</span>
               <input value='$year' type="text" id="year" name="year" maxlength="4" placeholder='比如: 1888'/>
            </label>
         </p>
         <p>
            <label for="price">
               <span>现价（元）</br>Price(Rmb):</span>
               <input value='$price' type="text" id="price" name="price" />
            </label>
         </p>
         $status_input
      </section>
      <section>
         <h3>描述 Description</h3>

         <p>
            <label for="description">
               <span>主要描写</br>Main description:</span>
               <textarea id="description" name="description" required>$description</textarea>
               <strong><abbr title="required">*</abbr></strong>
            </label>
         </p>

         <p>
            <label for="details">
               <span>详细信息</br>Details:</span>
               <textarea id="details" name="details">$details</textarea>
            </label>
         </p>     
      </section>
      <section>
         <h3>照片 Photos</h3>

         <p>
            <label for="pictures">
               <span>图片 Pictures:</span>
               <input type="file" multiple name="pictures[]" id="pictures" accept=".jpg,.jpeg"/>
            </label>
         </p>
      </section>        
      <section id='form-buttons'>
         <p>
            <button type="submit" name="submit" value="$submit"><p>$submit_label</p></button>
            $secondary_button
         </p>
      </section>
   </form>
</div>
FORM_MARKUP;
   }

   public function display_item($item_id) 
   {      
      $item_transfer = new Item_transfer;
      $item_page = new Item_page;

      $item_info = $item_transfer->single_item($item_id);
      $item_description = $item_page->item_info_box($item_info);

      $display_item = "
<div id='selected_item' data-item_id='$item_id'>";

      $small_img_box = "
   <ul id='small_img_box'>";

      foreach ($item_info['filepath']['small'] as $key => $value)
      {
         $order = $key + 1;
         $small_img_box .=   "
         <li class='small_img' id='order_$key'>
            <img src='".$value."' alt='' />
            <div class='img_order' id='ol_$key'>$order</div>
         </li>";
      }

      $small_img_box .=   "\n   </ul>\n   ";
      $display_item .= $small_img_box . $item_description . "
</div>";

      $display_item .= ''; //button markup for "edit item"


      // print_r(pathinfo($item_info['filepath']['small'][0]));
      return $display_item;
   }

   // $per_page is items per page 
   public function display_item_chooser($item_id = NULL, $per_page = NULL)
   {  
      if ($per_page === NULL)
      {
         $per_page = 20;
      }

      $start = NULL;
      $item_transfer = new Item_transfer;

      // how many rows are newer than $item_id ?
      if (!empty($item_id) && is_numeric($item_id))
      {
         $db = new Database;
         $query = "  SELECT COUNT(*)
                     FROM items
                     WHERE id > $item_id ";
         $db->query($query);
         $db->execute();
         $result = $db->single();
         $count = $result['COUNT(*)'];


         // if newer rows > 0, then set start to the newest row, maximum half of items per page
         if ($count > 0) // if current item is not the newest
         {
            if ($count < ($per_page / 2)) // if the number of newer items is few, then start from the newest
            {
               $start = NULL;
            }
            else // else just start from a few items newer than current (- 3 means 4th newer item, making current item be number 5)
            {
               $start = $count - ($perpage / 2 - 2);
            }
         }
         else
         {
            $start = NULL;
         }
      }
      else
      {
         $start = NULL;
      }

      // 4 items before, 5 items after
      $items = $item_transfer->fetch_items($start, $per_page);

      
      // display table

      $data_cells = array();
      $items_per_row = 6;

      // Put items/data cells in array $data_cells
      foreach ($items as $key => $value)
      {
         $item_link = "./admin.php?item={$items[$key]['id']}";
         $description   = $items[$key]['description'];
         $price         = $items[$key]['price'];
         
         //get first thumb of each item
         if (!empty($items[$key]['filepath']['small'][0]))
         {
            $thumb_path = $items[$key]['filepath']['small'][0];
            $item_id = $items[$key]['id'];
         }
         else // "No-Image picture"
         {
            $thumb_path = "./img/no_img.jpg";
         }

         $data_cells[] = "
            <td>
               <a href='{$item_link}'>
                  <img src='{$thumb_path}' alt=''>
               </a>
               <div class='item_number'>{$item_id}</div>
            </td>";
      }

      $table_data = '';
      $item_counter = 0;
      foreach ($data_cells as $value)
      {
         if ($item_counter === 1)
         {     
            $table_data .= "\n         <tr>";
         }

         if ($item_counter === 0)
         {     
            $table_data .= "<tr>";
            $item_counter = 1;
         }

         $table_data .= $value;

         if ($item_counter === $items_per_row) 
         {
            $table_data .= "
         </tr>";
            $item_counter = 1;
         } 
         else
         {
            $item_counter += 1;      
         }
      }

      if ($item_counter !== 1)
      {
         $table_data .= "
         </tr>";
      }

      $item_chooser = 
  "<table id='item_table'>
      <tbody>
         {$table_data}
      </tbody>
   </table>";

      // get the first picture of each item_id
      // place item picture in grid with item_id overlay

      // next page, prev page

      // input box for getting item by id
      // use $item_id by default to switch to certain page

      $display_item_chooser = "
<section id='item_chooser'>   
   {$item_chooser}
</section>";

      return $display_item_chooser;
   }
}