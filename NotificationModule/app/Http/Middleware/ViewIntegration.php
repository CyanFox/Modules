<?php

namespace Modules\NotificationModule\Http\Middleware;

use App\Facades\Utils\ViewIntegrationManager;
use Closure;
use Illuminate\Http\Request;

class ViewIntegration
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        ViewIntegrationManager::addView('dashboardmodule.sidebar.auth.footer', 'notificationmodule::components.integrations.desktop');
        ViewIntegrationManager::addView('dashboardmodule.mobile.auth.nav', 'notificationmodule::components.integrations.mobile');
        ViewIntegrationManager::addView('dashboardmodule.profile.dropdown', 'notificationmodule::components.integrations.profile-dropdown');
        ViewIntegrationManager::addView('dashboardmodule.profile.mobile.dropdown', 'notificationmodule::components.integrations.profile-dropdown');

        ViewIntegrationManager::addView('dashboardmodule.dashboard', 'notificationmodule::components.integrations.dashboard');

        ViewIntegrationManager::addView('adminmodule.sidebar.top', 'notificationmodule::components.integrations.admin.desktop');
        ViewIntegrationManager::addView('adminmodule.mobile.nav', 'notificationmodule::components.integrations.admin.mobile');
        return $next($request);
    }
}
