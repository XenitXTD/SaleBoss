<?php namespace SaleBoss\Repositories;

use SaleBoss\Models\User;

interface TaskRepositoryInterface {

    public function getTaskListForMe(User $user);

    public function getTaskListByMe(User $user);

    public function showTask(User $user, $taskId);

    public function editTask(User $user, $taskId, array $data);

    public function createTask(User $user, array $data);

    public function setAsDone(User $user, $taskId, $for_id);

    public function setAsClose(User $user, $taskId);

    public function setAsToBeDone(User $user, $taskId);
}