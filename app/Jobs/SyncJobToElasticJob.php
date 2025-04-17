<?php

namespace App\Jobs;

use App\Events\JobCreated;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncJobToElasticJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(ElasticsearchService $elasticsearchService): void
    {
        // Call the 'indexDocument' method from the ElasticsearchService to index the job document in Elasticsearch
        $elasticsearchService->indexDocument('jobs', $this->data['id'], $this->data);
    
    }
}
