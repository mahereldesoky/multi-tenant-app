<?php
use Illuminate\Support\Facades\DB;


// Check if the function 'switchTenantConnection' is not already defined
if (!function_exists('switchTenantConnection')) {

    // Helper function to switch the tenant database connection
    function switchTenantConnection($tenandb)
    {
        // Set the database name to the tenant's database
        $databaseName = $tenandb;

        // Update the database connection configuration for the 'tenant' connection
        config(['database.connections.tenant.database' => $databaseName]);

        // Purge the current 'tenant' connection to clear any previous database connection settings
        DB::purge('tenant');

        // Reconnect to the newly set tenant's database
        DB::reconnect('tenant');

        // Set the default connection to the 'tenant' database
        DB::setDefaultConnection('tenant');
    }
}