<?php
require_once '_class/Image_transfer.php';
$image_transfer = new Image_transfer;

$item_id = $_POST['item'];

try
{   
   $delete_item = $image_transfer->delete_item($item_id);
   $delete_folder = $image_transfer->delete_folder($item_id);
}
catch (Exception $e)
{
   $return = array();
   $return['status']['delete_item'] = $delete_item;
   $return['status']['delete_folder'] = $delete_folder;

   echo json_encode($return);
}


$return = array();
$return['status']['delete_item'] = $delete_item;
$return['status']['delete_folder'] = $delete_folder;

echo json_encode($return);