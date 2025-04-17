<?php

namespace App\Services;

use App\Models\JobPosting;
use App\Events\JobCreated;

class JobPostingService
{
    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        // Initialize the Elasticsearch service
        $this->elasticsearchService = $elasticsearchService;
    }

    // Method to get all jobs
    public function getAllJobs()
    {
        // Retrieve and return all job postings
        return JobPosting::all();
    }

    // Method to search for jobs using Elasticsearch
    public function searchJobs(string $query)
    {
        // Perform a search using Elasticsearch with the provided query
        return $this->elasticsearchService->search('jobs', [
            'term' => $query
        ]);
    }

    // Method to store a job and create a relationship with Elasticsearch
    public function storeJob(array $data, $userId)
    {
        // Create a new job posting in the database
        $job = JobPosting::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'user_id' => $userId
        ]);

        // After adding the job, dispatch an event to synchronize with Elasticsearch
        dispatch(function () use ($job) {
            event(new JobCreated($job->id, $job->user_id));
        })->afterResponse(); // Ensure this runs after the HTTP response
        // Return the created job
        return $job;
    }
}
