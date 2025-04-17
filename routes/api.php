<?php

use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\IdentifyTenant;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


// Tenant user registration & login (using Passport authentication)
Route::post('register', [RegisterController::class, 'register']);  // Register a new user
Route::post('login', [LoginController::class, 'login']);  // Login an existing user
    
// Grouping tenant-specific routes under the 'tenant' prefix, applying 'auth:api' middleware for authentication and 'IdentifyTenant' middleware to switch database connection
Route::prefix('tenant')->middleware(['auth:api', IdentifyTenant::class])->group(function () {
    // Job routes related to the tenant
    Route::post('jobs', [JobController::class, 'store']);  // Store a new job posting
    Route::get('jobs', [JobController::class, 'index']);  // Get all jobs for the tenant
    Route::post('search', [JobController::class, 'search']);  // Search for jobs based on a query

});