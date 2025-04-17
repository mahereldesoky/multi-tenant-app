<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        // Initialize Elasticsearch client using configuration settings
        $this->client = ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts')) // Set Elasticsearch hosts
            ->setApiKey(config('elasticsearch.apiKey')) // Use API Key for authentication
            ->build();
    }

    // Method to index a document in Elasticsearch
    public function indexDocument($index, $id, $document)
    {
        // Log the document indexing process
        Log::info('Indexing document to Elasticsearch', ['index' => $index, 'id' => $id, 'document' => $document]);

        // Index the document in Elasticsearch
        $response =  $this->client->index([
            'index' => $index,
            'id'    => $id,
            'body'  => $document
        ]);
        // Log the Elasticsearch response for monitoring
        Log::info('Elasticsearch response', ['response' => $response]);
        return $response;     // Return Elasticsearch response
    } 

    // Method to search documents in Elasticsearch
    public function search(string $index, array $query = []): array
    {
        // Set search parameters for Elasticsearch
        $params = [
            'index' => $index, // Elasticsearch index name
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query'  => $query['term'] ?? '', // Search term from query
                        'fields' => ['title', 'description', 'location'], // Fields to search in
                    ]
                ]
            ]
        ];

        // Perform the search query and get the response
        $response = $this->client->search($params);
        // Return the search hits (results) or an empty array if no results found
        return $response['hits']['hits'] ?? [];
    }
  
}