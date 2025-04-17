<?php

namespace App\Listeners;

use App\Events\JobCreated;
use App\Jobs\SyncJobToElasticJob;
use App\Models\JobPosting;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SyncJobToElasticAfterResponse implements ShouldQueue
{
    public $queue = 'default';
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(JobCreated $event): void
    {
        // Retrieve the tenant associated with the user ID from the event
        $tenant = Tenant::find($event->userId);
        $tenantdb = $tenant->database;

        // If the tenant and tenant database exist, switch the connection to the tenant's database
        if ($tenant && $tenantdb) {
            switchTenantConnection($tenantdb);
        }

        // Retrieve the job posting associated with the job ID from the event
        $job = JobPosting::find($event->jobId);

        // If the job exists, dispatch a job to sync it to Elasticsearch
        if ($job) {
             // Dispatch the SyncJobToElasticJob to the 'default' queue
            dispatch(new SyncJobToElasticJob($job->toArray()))->onQueue('default');
        }

    }

}
