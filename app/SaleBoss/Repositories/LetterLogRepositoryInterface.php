<?php namespace SaleBoss\Repositories;

use SaleBoss\Models\User;

interface LetterLogRepositoryInterface {

    public function addLetterLog(User $user, $letterId, $destinationId, $logType, $message);

    public function getList($letterId, $pathId);
}