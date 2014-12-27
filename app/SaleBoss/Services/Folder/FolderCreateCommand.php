<?php namespace SaleBoss\Services\Folder;

class FolderCreateCommand {

    public $parent_id;

    public $name;

    public $creator_id;

    public $for_type;

    public $for_id;

    /**
     * @param $parent_id
     * @param $name
     * @param $creator_id
     * @param $for_type
     * @param $for_id
     */
    function __construct($parent_id, $name, $creator_id, $for_type, $for_id)
    {

        $this->parent_id = $parent_id;
        $this->name = $name;
        $this->creator_id = $creator_id;
        $this->for_type = $for_type;
        $this->for_id = $for_id;
    }
}