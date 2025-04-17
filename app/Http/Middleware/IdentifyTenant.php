<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->user()->tenant_id;

        if(!$tenantId) {
            return response()->json(['message' => 'Tenant Id is missing'],400);
        }

        $tenant = Tenant::find($tenantId);

        if(!$tenant) {
            return response()->json(['message' => 'Invalid Tenant'],404);
        }
        
        config([
            'database.default' => 'tenant',
            'database.connections.tenant.database' => $tenant->database,
        ]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        app()->instance('currentTenant', $tenant);

        $request->merge(['tenant' => $tenant]);
        return $next($request);
    }
}
