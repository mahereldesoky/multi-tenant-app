<?php

return [
    'hosts' => [
        env('ELASTICSEARCH_ENDPOINT'),
    ],
    'apiKey' => env('ELASTICSEARCH_API_KEY'),
];