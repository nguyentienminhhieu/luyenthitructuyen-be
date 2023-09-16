<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\AppMain\Services\UserService;
use Exception;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('web')->attempt($credentials)) {
            // Đăng nhập thành công
            $admin = Auth::guard('web')->user();
            $token = $admin->createToken('access-token')->plainTextToken;

            return response()->json(['data'=> $admin,'token' => $token]);
        }

        // Đăng nhập không thành công
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);
               
            $data = $request->all();
            $user = $this->userService->createUser($data);

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
