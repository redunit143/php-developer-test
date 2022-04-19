<?php
namespace App\Services;

use App\Models\User;

interface UserServiceInterface
{

    /**
     *
     * @param array $users
     * @param User $user
     */
    public function upsert(array $users, User $user): void;
}

