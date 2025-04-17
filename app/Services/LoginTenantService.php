<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginTenantService
{
    public function authenticateUser($request)
    {
        $user = User::where('email', $request->email)->first();  // Retrieve the user by email from the database

        // Check if the user exists and if the provided password matches the stored hashed password
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new \Exception('Invalid credentials');  // Throw an exception if the credentials are invalid
        }
        
        // Authenticate the user by logging them in
        Auth::login($user);
        
        // Create a Passport token for the user
        $token = $user->createToken('TenantApp')->accessToken;
        
        // Return the generated token along with tenant information and user details
        return [
            'token' => $token,  // The generated Passport token
            'tenant_id' => $user->tenant_id,  // Tenant ID associated with the user
            'tenant_database' => $user->tenant->database,  // Tenant's database name
            'user' => $user,  // The authenticated user details
        ];
    }
}
