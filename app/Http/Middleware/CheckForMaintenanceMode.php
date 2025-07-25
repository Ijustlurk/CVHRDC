<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CheckForMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Only apply to user-side routes, not admin or superadmin
        $isAdminRoute = $request->is('admin/*') || $request->is('superadmin/*');
        if (!$isAdminRoute) {
            $maintenance = \App\Models\Maintenance::find(1);
            if ($maintenance && $maintenance->active) {
                return response()->view('maintenance', [
                    'message' => $maintenance->message ?? 'Site is under maintenance.'
                ]);
            }
        }
        return $next($request);
    }
}
