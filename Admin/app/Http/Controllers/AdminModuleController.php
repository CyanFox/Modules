<?php

namespace Modules\Admin\Http\Controllers;

use App\Facades\ModuleManager;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

#[Group('Admin Modules')]
class AdminModuleController
{
    #[QueryParameter('show_disabled', description: 'Include disabled modules', type: 'boolean', default: true, example: false)]
    public function getModules(Request $request)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules') || ! $request->attributes->get('api_key')->can('admin.modules')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $showDisabled = $request->query('show_disabled', true);

        if ($showDisabled) {
            $modules = Module::all();
        } else {
            $modules = Module::allEnabled();
        }

        return response()->json([
            'message' => 'Modules retrieved successfully',
            'modules' => collect($modules)->map(function ($module) {
                return [
                    'name' => $module->getName(),
                    'enabled' => $module->isEnabled(),
                    'version' => ModuleManager::getVersion($module->getName()),
                    'remote_version' => ModuleManager::getRemoteVersion($module->getName()),
                ];
            })->values()->toArray(),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function getModule(Request $request, $moduleName)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules') || ! $request->attributes->get('api_key')->can('admin.modules')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = Module::find($moduleName);

        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        return response()->json([
            'message' => 'Module retrieved successfully',
            'module' => [
                'name' => $module->getName(),
                'enabled' => $module->isEnabled(),
                'version' => ModuleManager::getVersion($module->getName()),
                'remote_version' => ModuleManager::getRemoteVersion($module->getName()),
            ],
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to enable', type: 'string', example: 'Admin')]
    public function enableModule(Request $request, $moduleName)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules.enable') || ! $request->attributes->get('api_key')->can('admin.modules.enable')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = Module::find($moduleName);

        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        ModuleManager::getModule($moduleName)->enable();

        return response()->json([
            'message' => 'Module enabled successfully',
            'module' => $module->getName(),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to disable', type: 'string', example: 'Admin')]
    public function disableModule(Request $request, $moduleName)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules.disable') || ! $request->attributes->get('api_key')->can('admin.modules.disable')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = Module::find($moduleName);

        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        ModuleManager::getModule($moduleName)->disable();

        return response()->json([
            'message' => 'Module disabled successfully',
            'module' => $module->getName(),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to delete', type: 'string', example: 'Admin')]
    public function deleteModule(Request $request, $moduleName)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules.delete') || ! $request->attributes->get('api_key')->can('admin.modules.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = Module::find($moduleName);

        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        ModuleManager::getModule($moduleName)->delete();

        return response()->json([
            'message' => 'Module deleted successfully',
            'module' => $module->getName(),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function getModuleVersion(Request $request, $moduleName)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules') || ! $request->attributes->get('api_key')->can('admin.modules')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = Module::find($moduleName);

        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        return response()->json([
            'message' => 'Module version retrieved successfully',
            'version' => ModuleManager::getVersion($moduleName),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function getRemoteModuleVersion(Request $request, $moduleName)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules') || ! $request->attributes->get('api_key')->can('admin.modules')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = Module::find($moduleName);

        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        return response()->json([
            'message' => 'Remote module version retrieved successfully',
            'version' => ModuleManager::getRemoteVersion($moduleName),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function isModuleUpToDate(Request $request, $moduleName)
    {
        $user = request()->attributes->get('api_key')->user;

        if (! $user->can('admin.modules') || ! $request->attributes->get('api_key')->can('admin.modules')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $module = Module::find($moduleName);

        if (! $module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        return response()->json([
            'message' => 'Module version status retrieved successfully',
            'up_to_date' => ModuleManager::getVersion($moduleName) === ModuleManager::getRemoteVersion($moduleName),
            'current_version' => ModuleManager::getVersion($moduleName),
            'remote_version' => ModuleManager::getRemoteVersion($moduleName),
        ]);
    }
}
