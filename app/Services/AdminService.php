<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminService
{

    public function updateProfile(int $id, array $data): User
    {
        $user = User::find($id);
        if (empty($data['password'])) {
            unset($data['password']);
        }
        else{
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill($data)->save();
        $user->save();

        return $user;
    }
}
