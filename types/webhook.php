<?php

class PostPayload {
    public $Topic;
    public $EventID;
    public $CheckinEvent;
    public $AcknowledgeEvent;

    public function __construct($Topic, $EventID, $CheckinEvent, $AcknowledgeEvent) {
        $this->Topic = $Topic;
        $this->EventID = $EventID;
        $this->CheckinEvent = $CheckinEvent;
        $this->AcknowledgeEvent = $AcknowledgeEvent;
    }
}

class CheckinEvent {
    public $UDID;
    public $Params;
    public $RawPayload;

    public function __construct($UDID, $Params, $RawPayload) {
        $this->UDID = $UDID;
        $this->Params = $Params;
        $this->RawPayload = $RawPayload;
    }
}

class AcknowledgeEvent {
    public $UDID;
    public $Params;
    public $RawPayload;
    public $CommandUUID;
    public $Status;

    public function __construct($UDID, $Params, $RawPayload, $CommandUUID, $Status) {
        $this->UDID = $UDID;
        $this->Params = $Params;
        $this->RawPayload = $RawPayload;
        $this->CommandUUID = $CommandUUID;
        $this->Status = $Status;
    }
}

?>
