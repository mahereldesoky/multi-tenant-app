<?php

namespace App\Events;


use App\Models\JobPosting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jobId;
    public $userId;

    public function __construct(int $jobId, int $userId)
    {
        $this->jobId = $jobId;
        $this->userId = $userId;
    }

}
