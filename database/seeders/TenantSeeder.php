<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            ['name' => 'Tenant_1', 'database' => 'tenant1_db'],
            ['name' => 'Tenant_2', 'database' => 'tenant2_db'],
        ];
    
        foreach ($tenants as $tenant) {
            Tenant::updateOrCreate(['name' => $tenant['name']], $tenant);
    
            // Create empty DB if not exists
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$tenant['database']}`");
        }
    }
}
