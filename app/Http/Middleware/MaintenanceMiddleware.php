<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Maintenance;

class MaintenanceMiddleware
{
    public function handle($request, Closure $next)
    {
        $maintenance = Maintenance::first();
        if ($maintenance && $maintenance->active) {
            return response()->view('maintenance', ['message' => $maintenance->message]);
        }

        return $next($request);
    }
}