<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\AppMain\Services\AdminService;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService) {
        $this->adminService = $adminService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            // Đăng nhập thành công
            $admin = Auth::guard('admin')->user();
            $token = $admin->createToken('admin-access-token')->plainTextToken;

            return response()->json(['data'=> $admin,'token' => $token]);
        }

        // Đăng nhập không thành công
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout() 
    {
        Auth::guard('admin')->logout();

        return response()->json(['message' => 'Logged out'], 200);
    }

    public function detail()
    {
        $user = Auth::user();
        return response()->json(['data' => $user], 200);
    }

    public function createAccount(Request $request) 
    {
        try {
            $input = $request->all();
            $admin = $this->adminService->createAccount($input);
            return response()->json(['data' => $admin], 200);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
