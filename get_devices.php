<?php
include_once('types/device.php');
header("Content-Type: application/json; charset=utf-8");

$device = new Device();
if (isset($_GET['udid'])) {
    $device->UDID = $_GET['udid'];
    $device->getFromDB();
    echo json_encode($device);
} else {
    echo json_encode($device->getAllFromDB());
}

?>
