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
            if($admin->active == 0) {
                Auth::guard('web')->logout();
                return response()->json(['error' => 'Tài khoản chưa được kích hoạt hoặc đã bị vô hiệu hóa'], 401);
            }
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
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                'role' => 'required',
            ]);
               
            $data = $request->all();
            $user = $this->userService->createUser($data);

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function listUsers()
    {
        try {  
            $user = $this->userService->listUsers();

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function infoUser()
    {
        try {  
            $user = Auth::user();

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateUser(Request $request)
    {
        try {  
            $request->validate([
                'name' => 'required',
                'phone' => 'numeric|min:10'
            ]);
            $input = $request->all();
            $user_id = Auth::id();
            $user = $this->userService->update($user_id, $input);

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    
    public function changePassword(Request $request)
    {
        try {  
            $request->validate([
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ]);
            $input = $request->all();
            $user_id = Auth::id();
            $user = $this->userService->changePassword($user_id, $input);

            return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {  
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);
            $input = $request->all();
            $user = $this->userService->forgotPassword($input);

            // return response()->json(['data'=> $user], 200);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
