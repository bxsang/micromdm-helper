<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once('types/device.php');
include_once('types/command.php');
include_once("plist.php");

$data = json_decode(file_get_contents("php://input"), true);

function decodePayload($data) {
    $raw_payload = new DOMDocument();
    $raw_payload->loadXML(base64_decode($data));
    foreach($raw_payload->documentElement->childNodes as $child) {
        if ($child->nodeName != "#text") {
            $payload = parsePlist($child);
            break;
        }
    }
    return $payload;
}

function handleAuthenticate($data) {
    $device = new Device();
    $device->UDID = $data['checkin_event']['udid'];
    $payload = decodePayload($data['checkin_event']['raw_payload']);

    $device->DeviceName = $payload->DeviceName;
    $device->Model = $payload->Model;
    $device->ModelName = $payload->ModelName;
    $device->SerialNumber = $payload->SerialNumber;
    $device->OSVersion = $payload->OSVersion;
    $device->BuildVersion = $payload->BuildVersion;
    $device->UpdatedAt = time();
    $device->AuthenticateRecieved = 1;

    $device->saveToDB();
}

function handleTokenUpdate($data) {
    $device = new Device();
    $device->UDID = $data['checkin_event']['udid'];
    $device->TokenUpdateRecieved = 1;
    $device->UpdatedAt = time();
    $device->updateDB();
}

function handleConnect($data) {
    $device = new Device();
    $command = new Command();
    $payload = decodePayload($data['acknowledge_event']['raw_payload']);

    $command->CommandUUID = $data['acknowledge_event']['command_uuid'];
    $command->Status = $data['acknowledge_event']['status'];
    $command->DeviceUDID = $data['acknowledge_event']['udid'];
    $update_time = time();
    $command->UpdatedAt = $update_time;
    $command->update();
    
    $device->UDID = $data['acknowledge_event']['udid'];
    if ($payload->QueryResponses) {
        $device->DetailInfomation = serialize($payload->QueryResponses);
    } elseif ($payload->InstalledApplicationList) {
        $device->InstalledApplicationList = serialize($payload->InstalledApplicationList);
    } elseif ($payload->ProfileList) {
        $device->ProfileList = serialize($payload->ProfileList);
    } elseif ($payload->ProvisioningProfileList) {
        $device->ProvisioningProfileList = serialize($payload->ProvisioningProfileList);
    } elseif ($payload->CertificateList) {
        $device->CertificateList = serialize($payload->CertificateList);
    } elseif ($payload->Users) {    // iPad only
        $device->Users = serialize($payload->Users);
    }
    $device->UpdatedAt = $update_time;
    $device->updateDB();
}

function handleCheckOut($data) {
    $device = new Device();
    $device->UDID = $data['checkin_event']['udid'];
    $device->deleteFromDB();
}

switch ($data['topic']) {
    case 'mdm.Authenticate':
        handleAuthenticate($data);
        break;
    case 'mdm.TokenUpdate':
        handleTokenUpdate($data);
        break;
    case 'mdm.Connect':
        handleConnect($data);
        break;
    case 'mdm.CheckOut':
        handleCheckOut($data);
        break;
    default:
        # code...
        break;
}

?>
