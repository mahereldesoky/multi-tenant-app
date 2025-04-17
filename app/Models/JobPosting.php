<?php

namespace App\Models;

use App\Services\ElasticsearchService;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $connection = 'tenant';
    protected $fillable = ['user_id', 'title', 'location','description'];


    protected static function boot()
    {
        parent::boot();

        static::saved(function ($job) {
            // Sync to Elasticsearch
            app(ElasticsearchService::class)->indexDocument(
                'jobs',
                $job->id,
                $job->toArray()
            );
        });
    }
}
