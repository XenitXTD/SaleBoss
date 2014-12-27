<?php namespace SaleBoss\Repositories;

interface LetterRepositoryInterface {

    public function getList($folderId);

    public function getMyInbox($userId, $userGroupId);

    public function getMyOutbox($userId, $userGroupId);

    public function addLetter($subject, $message, $userId, $folder, $path, $file, $pathLog);

    public function findById($id);

    public function setAsDone($user, $letterId, $destinationId);

    public function setAsBack($user, $letterId, $destinationId);

    public function findByIdAndDestination($id, $toId);

    public function getSearch($input);
}