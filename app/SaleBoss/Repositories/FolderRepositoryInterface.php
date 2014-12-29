<?php namespace SaleBoss\Repositories;

interface FolderRepositoryInterface {

    public function getList($model, $userGroupId);

    public function getById($folderId);

    public function addFolder($parent_id, $name, $creator_id, $for_type, $for_id);

    public function addFolderItem($name, $creator_id, $for_id, $description, $file);

    public function getItemsList($userId, $folderId);

    public function getItemById($itemId);

    public function getSearch($inputs, $userId);

    public function update($id, $input);

    public function delete($id);

}