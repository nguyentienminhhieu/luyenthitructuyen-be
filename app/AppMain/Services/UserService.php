<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\UserReponsitory;
use Illuminate\Support\Facades\Hash;

class UserService {
    public $userReponsitory;

    public function __construct(UserReponsitory $userReponsitory) {
        $this->userReponsitory = $userReponsitory;
    }

    public function createUser($input)
    {
        $input['password'] = Hash::make($input['password']);
        return $this->userReponsitory->create($input);
    }
    public function activeUser($user_id)
    {
        $user = $this->userReponsitory->find($user_id);
        $input = [
            'active' => $user->active==0?1:0
        ];
        return $this->userReponsitory->update('id', $user_id, $input);
    }
    public function listUsers()
    {
        $user = $this->userReponsitory->all();
        
        return $user;
    }
}