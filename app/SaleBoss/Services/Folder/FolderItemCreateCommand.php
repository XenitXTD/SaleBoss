<?php namespace SaleBoss\Services\Folder;

class FolderItemCreateCommand {

    public $parent_id;

    public $name;

    public $creator_id;

    public $for_type;

    public $for_id;

    public $description;

    public $file;

    /**
     * @param $name
     * @param $creator_id
     * @param $for_id
     * @param $description
     * @param $file
     * @internal param $parent_id
     * @internal param $for_type
     */
    function __construct($name, $creator_id, $for_id, $description, $file)
    {
        $this->name = $name;
        $this->creator_id = $creator_id;
        $this->for_id = $for_id;
        $this->description = $description;
        $this->file = $file;
    }
}