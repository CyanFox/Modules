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
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $showDisabled = $request->query('show_disabled', true);

        if ($showDisabled) {
            $modules = Module::all();
        } else {
            $modules = Module::allEnabled();
        }

        return apiResponse('Modules retrieved successfully',
            collect($modules)->map(function ($module) {
                return [
                    'name' => $module->getName(),
                    'enabled' => $module->isEnabled(),
                    'version' => ModuleManager::getVersion($module->getName()),
                    'remote_version' => ModuleManager::getRemoteVersion($module->getName()),
                ];
            })->values()->toArray());
    }

    #[PathParameter('moduleName', description: 'Name of the module to enable', type: 'string', example: 'Admin')]
    public function enableModule(Request $request, $moduleName)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules.enable')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = Module::find($moduleName);

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        ModuleManager::getModule($moduleName)->enable();

        return response()->json([
            'message' => 'Module enabled successfully',
            'module' => $module->getName(),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function getModule(Request $request, $moduleName)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = Module::find($moduleName);

        if (!$module) {
            return apiResponse('Module not found', null, false, 404);
        }

        return apiResponse('Module retrieved successfully', [
            'name' => $module->getName(),
            'enabled' => $module->isEnabled(),
            'version' => ModuleManager::getVersion($module->getName()),
            'remote_version' => ModuleManager::getRemoteVersion($module->getName()),
        ]);
    }

    #[PathParameter('moduleName', description: 'Name of the module to disable', type: 'string', example: 'Admin')]
    public function disableModule(Request $request, $moduleName)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules.disable')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = Module::find($moduleName);

        if (!$module) {
            return apiResponse('Module not found', null, false, 404);
        }

        ModuleManager::getModule($moduleName)->disable();

        return apiResponse('Module disabled successfully',
            $module->getName());
    }

    #[PathParameter('moduleName', description: 'Name of the module to delete', type: 'string', example: 'Admin')]
    public function deleteModule(Request $request, $moduleName)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules.delete')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = Module::find($moduleName);

        if (!$module) {
            return apiResponse('Module not found', null, false, 404);
        }

        ModuleManager::getModule($moduleName)->delete();

        return apiResponse('Module deleted successfully',
            $module->getName());
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function getModuleVersion(Request $request, $moduleName)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = Module::find($moduleName);

        if (!$module) {
            return apiResponse('Module not found', null, false, 404);
        }

        return apiResponse('Module version retrieved successfully',
            ModuleManager::getVersion($moduleName));
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function getRemoteModuleVersion(Request $request, $moduleName)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = Module::find($moduleName);

        if (!$module) {
            return apiResponse('Module not found', null, false, 404);
        }

        return apiResponse('Remote module version retrieved successfully',
            ModuleManager::getRemoteVersion($moduleName));
    }

    #[PathParameter('moduleName', description: 'Name of the module to retrieve', type: 'string', example: 'Admin')]
    public function isModuleUpToDate(Request $request, $moduleName)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = Module::find($moduleName);

        if (!$module) {
            return apiResponse('Module not found', null, false, 404);
        }

        return apiResponse('Module version status retrieved successfully', [
            'up_to_date' => ModuleManager::getVersion($moduleName) === ModuleManager::getRemoteVersion($moduleName),
            'current_version' => ModuleManager::getVersion($moduleName),
            'remote_version' => ModuleManager::getRemoteVersion($moduleName),
        ]);
    }
}
