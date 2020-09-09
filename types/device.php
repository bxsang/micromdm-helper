<?php
include_once('db.php');

// class Device {
//     public $DeviceName;
//     public $BuildVersion;
//     public $ModelName;
// 	public $Model;
// 	public $OSVersion;
// 	public $ProductName;
// 	public $SerialNumber;
// 	public $DeviceCapacity;
// 	public $AvailableDeviceCapacity;
// 	/**
// 	 * float
// 	 * between 0.0 and 1.0 or -1.0 if battery level cannot be determined
// 	 */
// 	public $BatteryLevel;	# iOS
// 	/**
// 	 * int
// 	 * 0: none
// 	 * 1: GSM
// 	 * 2: CDMA
// 	 * 3: both
// 	 */
// 	public $CellularTechnology;	# iOS
// 	public $IMEI;	# iOS
// 	public $MEID;	# iOS
// 	public $ModemFirmwareVersion;	# iOS
// 	public $IsSupervised;	# iOS
// 	public $IsDeviceLocatorServiceEnabled;	# iOS
// 	public $IsActivationLockEnabled;	# iOS
// 	public $IsDoNotDisturbInEffect;	# iOS
// 	public $DeviceID;	# Apple TV
// 	public $EASDeviceIdentifier;	# iOS
// 	public $IsCloudBackupEnabled;	# iOS
// 	public $OSUpdateSettings = [
// 		"AutoCheckEnabled" => false,
//         "AutomaticAppInstallationEnabled" => false,
//         "AutomaticOSInstallationEnabled" => false,
//         "AutomaticSecurityUpdatesEnabled" => false,
//         "BackgroundDownloadEnabled" => false,
//         "CatalogURL" => "",
//         "IsDefaultCatalog" => false,
//         "PerformPeriodicCheck" => false,
//         "PreviousScanDate" => false,
//         "PreviousScanResult" => "0"
// 	];
// 	public $LocalHostName;	# macOS
// 	public $HostName;	# macOS
// 	public $SystemIntegrityProtectionEnabled;	# macOS
// 	public $ActiveManagedUsers;	# macOS
// 	public $IsMDMLostModeEnabled;	# iOS
// 	public $MaximumResidentUsers;	# iOS
// 	public $AwaitingConfiguration;
// 	public $BluetoothMAC;
// 	public $CarrierSettingsVersion;
// 	public $CurrentCarrierNetwork;
// 	public $CurrentMCC;
// 	public $CurrentMNC;
// 	public $DataRoamingEnabled;
// 	public $DiagnosticSubmissionEnabled;
// 	public $ICCID;
// 	public $IsMultiUser;
// 	public $IsNetworkTethered;
// 	public $IsRoaming;
// 	public $iTunesStoreAccountHash;
// 	public $iTunesStoreAccountIsActive;
// 	public $LastCloudBackupDate;
// 	public $MDMOptions;
// 	public $PersonalHotspotEnabled;
// 	public $PhoneNumber;
// 	public $PushToken;
// 	public $SIMCarrierNetwork;
// 	public $SIMMCC;
// 	public $SIMMNC;
// 	public $SubscriberCarrierNetwork;
// 	public $SubscriberMCC;
// 	public $SubscriberMNC;
// 	public $VoiceRoamingEnabled;
// 	public $WiFiMAC;
// 	public $EthernetMAC ;
// 	public $UDID;
// 	public $Active;
// 	public $Profiles;				// []DeviceProfile
// 	public $Commands;				// []Command
// 	public $Certificates;			// []Certificate
// 	public $InstallApplications;	// []DeviceInstallApplication
// 	public $SecurityInfo;			// SecurityInfo 
// 	public $ProfileList;			// []ProfileList
// 	public $UpdatedAt;
// 	public $AuthenticateRecieved = false;
// 	public $TokenUpdateRecieved = false;
// 	public $InitialTasksRun = false;
// 	public $Erase = false;
// 	public $Lock = false;
// 	public $UnlockPin;
// 	public $TempUnlockPin;
// 	public $LastInfoRequested;
// 	public $NextPush;
// 	public $LastScheduledPush;
// 	public $LastCheckedIn;
// }

class Device {
	public $UDID;
	public $DeviceName;
	public $ModelName;
	public $Model;
	public $SerialNumber;
	public $OSVersion;
	public $BuildVersion;
	public $UpdatedAt;
	public $AuthenticateRecieved;	# 0, 1
	public $TokenUpdateRecieved;	# 0, 1
	public $DetailInfomation;
	public $InstalledApplicationList;
	public $ProfileList;
	public $ProvisioningProfileList;
	public $CertificateList;
	public $Users;

	public function unsetEmptyElement($arr) {
		foreach ($arr as $key => $value) {
			if (empty($value)) {
				unset($arr[$key]);
			}
		}
		return $arr;
	}

	// https://stackoverflow.com/a/41851452
	public function toArray() {
		$a = array();
		foreach ($this as $k => $v) {
			$a[$k] = (is_array($v) || is_object($v)) ? objectToArray($v): $v;
		}
	
		return $this->unsetEmptyElement($a);
	}

	public function getFromDB() {
		$db = new DB();
		$device = $db->query('SELECT * FROM Devices WHERE UDID = ?', $this->UDID)->fetchArray();
		$arr = ['DetailInfomation', 'InstalledApplicationList', 'ProfileList', 'ProvisioningProfileList', 'CertificateList', 'Users'];
		foreach ($device as $key => $value) {
			if (in_array($key, $arr)) {
				$this->$key = unserialize($value);
			} else {
				$this->$key = $value;
			}
		}
	}

	public function getAllFromDB() {
		$result = [];
		$db = new DB();
		$devices = $db->query('SELECT * FROM Devices')->fetchAll();
		$arr = ['DetailInfomation', 'InstalledApplicationList', 'ProfileList', 'ProvisioningProfileList', 'CertificateList', 'Users'];
		foreach ($devices as $device) {
			foreach ($arr as $ar) {
				$device[$ar] = unserialize($device[$ar]);
			}
			array_push($result, $device);
		}
		return $result;
	}

	public function saveToDB() {
		$db = new DB();
		$device = $this->toArray();
		
		$exist = $db->query('SELECT * FROM Devices WHERE UDID = ?', $this->UDID);
		if ($exist->numRows() >= 1) {
			die('Device exists');
		} else {
			$db->query('INSERT INTO Devices ('.implode(',',array_keys($device)).')'.' VALUES ('.rtrim(str_repeat('?,', sizeof($device)), ',').')', array_values($device));
			echo $db->affectedRows();
		}
	}

	public function updateDB() {
		$db = new DB();
		$device = $this->toArray();

		$value = array_values($device);
		array_push($value, $this->UDID);

		$db->query('UPDATE Devices SET '.implode('=?,', array_keys($device)).'=? WHERE UDID = ?', $value);
		echo $db->affectedRows();
	}

	public function deleteFromDB() {
		$db = new DB();
		$db->query('DELETE FROM Devices WHERE UDID = ?', $this->UDID);
		echo $db->affectedRows();
	}
}

?>
