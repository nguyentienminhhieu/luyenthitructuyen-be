<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\AdminReponsitory;
use Illuminate\Support\Facades\Hash;

class AdminService {
    public $adminReponsitory;

    public function __construct(AdminReponsitory $adminReponsitory) {
        $this->adminReponsitory = $adminReponsitory;
    }

    public function createAccount($input)
    {
        $input['password'] = Hash::make($input['password']);
        return $this->adminReponsitory->create($input);
    }
}