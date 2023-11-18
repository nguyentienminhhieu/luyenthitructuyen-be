<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\AppMain\Services\AdminService;
use App\AppMain\Services\UserService;

class AdminController extends Controller
{
    protected $adminService;
    protected $userService;

    public function __construct(
        AdminService $adminService,
        UserService $userService
    ) {
        $this->adminService = $adminService;
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            // Đăng nhập thành công
            $admin = Auth::guard('admin')->user();
            unset($admin->password);
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
            return response()->json(['data' => 'success'], 200);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function activeUser(Request $request)
    {
        try {
            $input = $request->all();
            $user = $this->userService->activeUser($input['user_id']);
            return response()->json(['data' => $user], 200);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function activeAdmin(Request $request)
    {
        try {
            $input = $request->all();
            $admin = $this->adminService->activeAdmin($input['id']);
            return response()->json(['data' => $admin], 200);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function listAccount(Request $request) 
    {
        try {
            $input = $request->all();
            $admin = $this->adminService->listAdmin($input);
            return response()->json(['data' => $admin], 200);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function deleteAccount($id) 
    {
        try {
            $admin = $this->adminService->deleteAdmin($id);
            return response()->json(['data' => $admin], 200);
        } 
        catch (Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
