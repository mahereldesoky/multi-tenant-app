<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Services\RegisterTenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;


class RegisterController extends Controller
{
    // Define the RegisterTenantService instance to be injected into the controller
    protected $registerService;

    // Constructor to inject RegisterTenantService
    public function __construct(RegisterTenantService $registerService)
    {
        $this->registerService = $registerService;
    }

    // Register method to create a new tenant and user
    public function register(Request $request)
    {
        try {
            // Call the service method to register the tenant and user using the request data
            $tenant = $this->registerService->registerTenantAndUser($request);
            
            // Return a success message along with the tenant data as a JSON response with status 201 (Created)
            return response()->json(['message' => 'User created successfully', 'data' => $tenant], 201);
        } catch (\Exception $e) {
            // If an exception occurs, return the error message as a JSON response with status 400 (Bad Request)
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
