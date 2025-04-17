<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobPostingRequest;
use App\Services\JobPostingService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Define the JobPostingService instance to be injected into the controller
    protected $jobService;

    // Constructor to inject JobPostingService
    public function __construct(JobPostingService $jobService)
    {
        $this->jobService = $jobService;
    }

    // List all jobs for the tenant
    public function index()
    {
        // Call the service method to get all jobs
        $jobs = $this->jobService->getAllJobs();
        
        // Return the jobs as a JSON response with status 200
        return response()->json($jobs, 200);
    }

    // Search for jobs based on the user's query
    public function search(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query');
        
        // Call the service method to search for jobs
        $results = $this->jobService->searchJobs($query);
        
        // Return the search results as a JSON response
        return response()->json($results);
    }

    // Store a new job posting
    public function store(JobPostingRequest $request) 
    {
        try {
            // Call the service method to store the job posting with validated data and user ID
            $job = $this->jobService->storeJob($request->validated(), $request->user()->id);
            
            // Return a success message as a JSON response with status 201
            return response()->json(['message' => 'Job created successfully'], 201);
        } catch (\Exception $e) {
            // If an exception occurs, return the error message as a JSON response with status 422
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }


}
