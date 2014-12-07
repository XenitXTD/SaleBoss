<?php namespace SaleBoss\Repositories;

use SaleBoss\Models\User;

interface TaskMessagesRepositoryInterface {

    public function sendMessageOnTask(User $user, $taskId, $message, $notification = false, $for_id = null);
}