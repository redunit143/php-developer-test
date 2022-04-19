<?php
namespace App\Services;

use App\Models\User;

class UserService implements UserServiceInterface
{

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\UserServiceInterface::upsert()
     */
    public function upsert(array $users, User $user): void
    {
        User::upsert($users, [
            'name'
        ]);
    }
}

