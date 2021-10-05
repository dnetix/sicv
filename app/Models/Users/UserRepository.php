<?php

namespace App\Models\Users;

class UserRepository
{
    public function validateUserPassword($user_id, $password)
    {
        $user = User::findOrFail($user_id);
        return \Hash::check($password, $user->password);
    }
}
