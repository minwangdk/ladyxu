<?php
require_once '_class/Image_transfer.php';
$image_transfer = new Image_transfer;

$item_id = $_POST['item'];
$image = $_POST['image'];
$delete = $image_transfer->delete_image($item_id, $image);

// Callback returns confirmation and new image order
$return = array();
$return['status'] = $delete;

echo json_encode($return);
?>