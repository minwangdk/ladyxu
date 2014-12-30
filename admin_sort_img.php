<?php
require_once '_class/Image_transfer.php';
$image_transfer = new Image_transfer;

$item_id = $_POST['item'];
$sort = $image_transfer->sort_img_order($item_id);

// Callback returns confirmation and new image order
$return = array();
$return['order'] = $_POST['order'];
$return['status'] = $sort;

echo json_encode($return);