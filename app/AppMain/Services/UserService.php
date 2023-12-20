<?php

namespace App\AppMain\Services;
use App\AppMain\Reponsitory\UserReponsitory;
use App\AppMain\Reponsitory\PasswordResetTokenReponsitory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Jobs\SendResetPasswordEmail;
use App\Jobs\VerifyEmail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UserService {
    public $userReponsitory;
    public $passwordResetTokenReponsitory;

    public function __construct(UserReponsitory $userReponsitory, PasswordResetTokenReponsitory $passwordResetTokenReponsitory) {
        $this->userReponsitory = $userReponsitory;
        $this->passwordResetTokenReponsitory = $passwordResetTokenReponsitory;
    }

    public function createUser($input)
    {
        $token = Str::random(60);
        $input['password'] = Hash::make($input['password']);
        $input['token'] = $token;
        $user = $this->userReponsitory->create($input);
        VerifyEmail::dispatch($input['email'], $token);
        return $user;
    }
    public function activeUser($user_id)
    {
        $user = $this->userReponsitory->find($user_id);
        $input = [
            'active' => $user->active==0?1:0
        ];
        return $this->userReponsitory->update('id', $user_id, $input);
    }
    public function listUsers($inputs)
    {
        $user = $this->userReponsitory->getUsers($inputs);
        
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

    public function resetPassword($token, $password) 
    {
        try {
            $dataResetEmail = $this->passwordResetTokenReponsitory->findOne('token',$token);
            if(isset($dataResetEmail)) {
            $user = $this->userReponsitory->findOne('email', $dataResetEmail->email);
            $data = [
                'password' => Hash::make($password)
            ];
            $rs = $this->userReponsitory->update('id',$user->id, $data);
            $this->passwordResetTokenReponsitory->deleteWhere('token',$token);
            return $rs;
            } else {
                return 'Reset password failed!';
            }
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }

    public function verifyEmail($token) 
    {
        try {
            $verify_user = $this->userReponsitory->findOne('token',$token);
            if(isset($verify_user)) {
            $data = [
                'active' => 1,
                'token' => null
            ];
            $rs = $this->userReponsitory->update('id',$verify_user->id, $data);
            return $rs;
            } else {
                return 'Verify email failed!';
            }
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }
}