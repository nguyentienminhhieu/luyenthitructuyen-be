<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\UserReponsitory;
use App\AppMain\Reponsitory\PasswordResetTokenReponsitory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Jobs\SendResetPasswordEmail;

class UserService {
    public $userReponsitory;
    public $passwordResetTokenReponsitory;

    public function __construct(UserReponsitory $userReponsitory, PasswordResetTokenReponsitory $passwordResetTokenReponsitory) {
        $this->userReponsitory = $userReponsitory;
        $this->passwordResetTokenReponsitory = $passwordResetTokenReponsitory;
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

    public function update($user_id, $inputs) 
    {
        return $this->userReponsitory->update('id', $user_id, $inputs);
    }
    public function changePassword($user_id, $inputs) 
    {
        $inputs['password'] =  Hash::make($inputs['password']);
        return $this->userReponsitory->update('id', $user_id, $inputs);
    }
    public function forgotPassword($inputs) 
    {
        $token = Str::random(60);
        $data = [
            'token' => $token,
            'created_at' => Carbon::now()
        ];
       
        $resetToken = $this->passwordResetTokenReponsitory->findOne('email', $inputs['email']);
        if(isset($resetToken)) {
            $this->passwordResetTokenReponsitory->update('email',$inputs['email'], $data);
        } else {
            $data['email'] = $inputs['email'];
            $this->passwordResetTokenReponsitory->create($data);
        }

        SendResetPasswordEmail::dispatch($inputs['email'], $token);

        return "Password reset email sent successfully!";
    }
}