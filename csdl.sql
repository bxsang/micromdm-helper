-- CREATE TABLE Devices (
--     UDID VARCHAR(max) PRIMARY KEY,
--     DeviceName VARCHAR(max),
--     BuildVersion VARCHAR(max),
--     ModelName VARCHAR(max),
-- 	Model VARCHAR(max),
-- 	OSVersion VARCHAR(max),
-- 	ProductName VARCHAR(max),
-- 	SerialNumber VARCHAR(max),
-- 	DeviceCapacity FLOAT,
-- 	AvailableDeviceCapacity FLOAT,
-- 	BatteryLevel FLOAT,
-- 	CellularTechnology INT,
-- 	IMEI VARCHAR(max),
-- 	MEID VARCHAR(max),
-- 	ModemFirmwareVersion VARCHAR(max),
-- 	IsSupervised BOOLEAN;
-- 	IsDeviceLocatorServiceEnabled BOOLEAN;
-- 	IsActivationLockEnabled BOOLEAN;
-- 	IsDoNotDisturbInEffect BOOLEAN;
-- 	DeviceID;
-- 	EASDeviceIdentifier;
-- 	IsCloudBackupEnabled;
-- 	OSUpdateSettings;
-- 	LocalHostName;
-- 	HostName;
-- 	SystemIntegrityProtectionEnabled;
-- 	AppAnalyticsEnabled;
-- 	IsMDMLostModeEnabled;
-- 	AwaitingConfiguration;
-- 	MaximumResidentUsers;
-- 	BluetoothMAC;
-- 	CarrierSettingsVersion;
-- 	CurrentCarrierNetwork;
-- 	CurrentMCC;
-- 	CurrentMNC;
-- 	DataRoamingEnabled;
-- 	DiagnosticSubmissionEnabled;
-- 	ICCID;
-- 	IsMultiUser;
-- 	IsNetworkTethered;
-- 	IsRoaming;
-- 	iTunesStoreAccountHash;
-- 	iTunesStoreAccountIsActive;
-- 	LastCloudBackupDate;
-- 	MDMOptions;
-- 	PersonalHotspotEnabled;
-- 	PhoneNumber;
-- 	PushToken;
-- 	SIMCarrierNetwork;
-- 	SIMMCC;
-- 	SIMMNC;
-- 	SubscriberCarrierNetwork;
-- 	SubscriberMCC;
-- 	SubscriberMNC;
-- 	VoiceRoamingEnabled;
-- 	WiFiMAC;
-- 	EthernetMAC ;
-- 	Active;
-- 	Profiles;				// []DeviceProfile
-- 	Commands;				// []Command
-- 	Certificates;			// []Certificate
-- 	InstallApplications;	// []DeviceInstallApplication
-- 	SecurityInfo;			// SecurityInfo 
-- 	ProfileList;			// []ProfileList
-- 	UpdatedAt;
-- 	AuthenticateRecieved = false;
-- 	TokenUpdateRecieved = false;
-- 	InitialTasksRun = false;
-- 	Erase = false;
-- 	Lock = false;
-- 	UnlockPin;
-- 	TempUnlockPin;
-- 	LastInfoRequested;
-- 	NextPush;
-- 	LastScheduledPush;
-- 	LastCheckedIn;
-- )

CREATE TABLE Devices (
    UDID VARCHAR(100) PRIMARY KEY,
    DeviceName VARCHAR(10000),
    ModelName VARCHAR(100),
	Model VARCHAR(100),
    SerialNumber VARCHAR(100),
    OSVersion VARCHAR(100),
    BuildVersion VARCHAR(100),
    UpdateAt INT
    AuthenticateRecieved BOOLEAN,
    TokenUpdateRecieved BOOLEAN,
    DetailInfomation TEXT,
    InstalledApplicationList TEXT,
    ProfileList TEXT,
    ProvisioningProfileList TEXT,
    CertificateList TEXT,
    Users TEXT
)

CREATE TABLE Commands (
    CommandUUID VARCHAR(100) PRIMARY KEY,
    Status VARCHAR(100),
    DeviceUDID VARCHAR(100),
    RequestType VARCHAR(100),
    ErrorString VARCHAR(100),
    AttemptCount INT,
    UpdatedAt INT
)
