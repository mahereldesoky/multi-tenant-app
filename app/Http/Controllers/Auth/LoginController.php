<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoginTenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Define the LoginTenantService instance to be injected into the controller
    protected $loginService;

    // Constructor to inject LoginTenantService
    public function __construct(LoginTenantService $loginService)
    {
        $this->loginService = $loginService;
    }

    // Login method to authenticate a user
    public function login(Request $request)
    {
        try {
            // Call the service method to authenticate the user using the request data
            $data = $this->loginService->authenticateUser($request);
            
            // Return the authentication data as a JSON response with status 200 (OK)
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // If an exception occurs, return the error message as a JSON response with status 401 (Unauthorized)
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
