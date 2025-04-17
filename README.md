# Laravel Elasticsearch Task with Multi-tenancy, Horizon, and Queue

## Overview

This project demonstrates a Laravel API-only backend that integrates:
- Laravel Sail with Docker
- Laravel Passport for authentication
- Manual multi-tenancy with dynamic database switching
- Elasticsearch synchronization using queued jobs
- Laravel Horizon for queue management
- Deferred event dispatching (after response)

---

## Features Implemented

### 1. **Multi-tenancy**
- Implemented using custom middleware `IdentifyTenant` that:
  - Extracts tenant ID from request header.
  - Dynamically switches DB connection at runtime.

### 2. **Authentication**
- Used Laravel Passport for API authentication.
- Configured Passport for authentication.
- Access tokens assigned per user.

### 3. **Job Synchronization to Elasticsearch**
- When a job is created:
  - A `JobCreated` event is dispatched after response.
  - `SyncJobToElasticAfterResponse` listens and dispatches `SyncJobToElasticJob` job to queue.
  - Job pushes data to Elasticsearch via PHP client.

### 4. **Queue System**
- Redis used as queue driver.
- `SyncJobToElasticJob` implements `ShouldQueue`.
- Processed through Laravel Horizon.

### 5. **Laravel Horizon**
- Installed and configured to manage and monitor queues.
- Jobs appear in Horizon Dashboard (`/horizon` route).
- Supervisor config can be added for production.

### 6. **Deferred Event Dispatching**
- Events use `->afterResponse()` to delay execution until after HTTP response is returned.
- Improves performance and UX.

---

## Setup Instructions

### Prerequisites
- Docker & Docker Compose installed.
- Git installed.

---

### Step 1: Clone the repository
```bash
git clone https://github.com/mahereldesoky/multi-tenant-app.git
cd laravel-elasticsearch-task

```
### Step 2: Environment Configuration

- Copy the example .env file
- cp .env.example .env

### Step 3: Start Laravel Sail
```bash 
./vendor/bin/sail up -d
```

### Step 4: Install Dependencies & Passport
```bash 
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan passport:install
```

### Step 5: Set Redis Queue And ElasticSearch
- QUEUE_CONNECTION=redis
- ELASTICSEARCH_HOSTS=http://elasticsearch:9200
- ELASTICSEARCH_ENDPOINT=your-end-point
- ELASTICSEARCH_API_KEY="api-key"


## Step 6: Horizon
```bash 
./vendor/bin/sail artisan horizon
```
Go to: http://localhost/horizon

### Step 7: Test the Flow
Send a POST request:

POST /api/jobs

{
  "title": "Sample Job",
  "description": "Test Elasticsearch sync",
  "location": "Remote"
}

Header: Authorization: Bearer {token}



### Notes

- All syncing to Elasticsearch is deferred and queued.
- You can monitor progress using Horizon dashboard.
- Passport token must be sent in each request.


