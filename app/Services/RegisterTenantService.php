<?php
namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterTenantService
{
    public function registerTenantAndUser($request)
    {
        // Check if a user with the provided email already exists
        $existing = User::where('email', $request->email)->first();
    
        // If the user already exists, throw an exception
        if ($existing) {
            throw new \Exception('User already exists');
        }
    
        // Generate the tenant database name using the tenant's name
        $dbName = 'tenant_' . Str::slug($request->name);
        
        // Create a new tenant database
        DB::statement("CREATE DATABASE `{$dbName}`");
    
        // Create a new tenant record in the tenants table
        $tenant = Tenant::create([
            'name' => $request->name,  // Tenant name
            'database' => $dbName,  // Tenant database name
        ]);
    
        // Create a new user associated with the tenant
        $user = new User([
            'name' => $request->name,  // User's name
            'tenant_id' => $tenant->id,  // Associate user with the tenant
            'email' => $request->email,  // User's email
            'password' => Hash::make($request->password),  // Hash and save the password
        ]);
        $user->save();  // Save the user to the database
    
        // Switch to the tenant's database to perform operations
        switchTenantConnection($dbName);
    
        // Run migrations for the tenant's database
        Artisan::call('migrate:fresh', [
            '--database' => 'tenant',  // Specify the tenant database
            '--path' => '/database/migrations/tenants',  // Specify the migration path for tenants
            '--force' => true,  // Force migration without confirmation
        ]);
    
        // Return the created tenant
        return $tenant;
    }
}
