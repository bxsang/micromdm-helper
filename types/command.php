<?php
include_once('db.php');

class Command {
    public $CommandUUID;
	public $Status;
	public $DeviceUDID;
	public $RequestType;
	public $ErrorString;
	public $UpdatedAt;

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

	public function send() {
		// to be implemented
	}

	public function get() {
		$db = new DB();
		$command = $db->query('SELECT * FROM Commands WHERE CommandUUID = ?', $this->CommandUUID)->fetchArray();
		foreach ($command as $key => $value) {
			$this->$key = $value;
		}
	}

	public function save() {
		$db = new DB();
		$command = $this->toArray();

		$exist = $db->query('SELECT * FROM Commands WHERE CommandUUID = ?', $this->CommandUUID);
		if ($exist->numRows() >= 1) {
			die('Command exists');
		} else {
			$db->query('INSERT INTO Commands ('.implode(',',array_keys($command)).')'.' VALUES ('.rtrim(str_repeat('?,', sizeof($command)), ',').')', array_values($command));
			echo $db->affectedRows();
		}
	}

	public function update() {
		$db = new DB();
		$command = $this->toArray();

		$value = array_values($command);
		array_push($value, $this->CommandUUID);

		$db->query('UPDATE Commands SET '.implode('=?,', array_keys($command)).'=? WHERE CommandUUID = ?', $value);
		echo $db->affectedRows();
	}
}

class CommandPayload {
    public $UDID;
    public $RequestType;
	public $Payload;
	public $Queries;
	public $Identifier;
	public $ManifestURL;
	public $Pin;
}

class CommandResponse {
    public $CommandUUID;
    public $command;
}

?>
