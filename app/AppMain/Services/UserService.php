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
}