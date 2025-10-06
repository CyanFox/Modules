<?php

namespace Modules\Admin\Http\Controllers;

use App\Facades\ModuleManager;
use App\Facades\VersionManager;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;

#[Group('Admin Version')]
class AdminVersionController
{
    public function isBaseUpToDate(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.dashboard')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Base version status retrieved successfully', [
            'up_to_date' => VersionManager::isBaseUpToDate(),
            'current_version' => VersionManager::getCurrentBaseVersion(),
            'remote_version' => VersionManager::getRemoteBaseVersion(),
        ]);
    }

    public function getCurrentBaseVersion(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.dashboard')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Current base version retrieved successfully', [
            'version' => VersionManager::getCurrentBaseVersion(),
            'dev' => VersionManager::isDevVersion(),
        ]);
    }

    public function getRemoteBaseVersion(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.dashboard')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Remote base version retrieved successfully', VersionManager::getRemoteBaseVersion());
    }

    #[QueryParameter('module', 'The name of the module to check the version for', required: true, example: 'Admin')]
    public function getCurrentModuleVersion(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
        ]);

        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Current module version retrieved successfully', ModuleManager::getVersion($request->query('module')));
    }

    #[QueryParameter('module', 'The name of the module to check the version for', required: true, example: 'Admin')]
    public function getRemoteModuleVersion(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
        ]);

        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Remote module version retrieved successfully', ModuleManager::getRemoteVersion($request->query('module')));
    }

    #[QueryParameter('module', 'The name of the module to check the version for', required: true, example: 'Admin')]
    public function isModuleUpToDate(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
        ]);

        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.modules')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $module = $request->query('module');

        return apiResponse('Module version status retrieved successfully', [
            'version' => ModuleManager::getVersion($module) === ModuleManager::getRemoteVersion($module),
            'current_version' => ModuleManager::getVersion($module),
            'remote_version' => ModuleManager::getRemoteVersion($module),
        ]);
    }
}
