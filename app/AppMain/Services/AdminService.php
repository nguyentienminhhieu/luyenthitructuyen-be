<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\AdminReponsitory;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
    public function activeAdmin($id)
    {
        $admin = $this->adminReponsitory->find($id);
        if(Auth::user()->role == Admin::ADMIN && Auth::user()->id != $id) {
            $input = [
                'active' => $admin->active==0?1:0
            ];
            return $this->adminReponsitory->update('id', $id, $input);
        } else {
            return response()->json(['errors' => "Bạn không có quyền thực hiện điều này"]);
        }
        
    }
    public function listAdmin($input)
    {
        return $this->adminReponsitory->all();
    }
    public function deleteAdmin($id)
    {
        $admin = $this->adminReponsitory->find($id);
        if(Auth::user()->role == Admin::ADMIN && Auth::user()->id != $id) {
            return $this->adminReponsitory->delete($id);
        } else {
            return response()->json(['errors' => "Bạn không có quyền thực hiện điều này"]);
        }
        
    }
}