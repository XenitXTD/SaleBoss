<?php namespace SaleBoss\Services\Letter;

class LetterActionsCommand {

    public $user;

    public $letterId;

    public $actionType;

    public $destinationId;

    public $logMessage;

    /**
     * @param      $user
     * @param      $letterId
     * @param      $actionType
     * @param null $destinationId
     * @param      $logMessage
     * @internal param $message
     */
    function __construct($user, $letterId, $actionType, $destinationId = null, $logMessage)
    {
        $this->user = $user;
        $this->letterId = $letterId;
        $this->actionType = $actionType;
        $this->destinationId = $destinationId;
        $this->logMessage = $logMessage;
    }
}