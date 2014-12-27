<?php namespace SaleBoss\Services\Letter;

class LetterStoreCommand {

    public $user;

    public $subject;

    public $message;

    public $path;

    public $folder;

    public $file;

	/**
     * @param $user
     * @param $subject
     * @param $message
     * @param $folder
     * @param $path
     * @param $file
     */
    function __construct($user, $subject, $message, $folder, $path, $file)
    {
        $this->user = $user;

        $this->subject = $subject;

        $this->message = $message;

        $this->folder = $folder;

        $this->path = $path;

        $this->file = $file;
    }
}