<?php

namespace Modules\Admin\Http\Controllers;

use App\Facades\ModuleManager;
use App\Facades\VersionManager;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Admin Version')]
class AdminVersionController
{
    public function getCurrentBaseVersion(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.dashboard') || !$request->attributes->get('api_key')->can('admin.dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Current base version retrieved successfully',
            'version' => VersionManager::getCurrentBaseVersion(),
            'dev' => VersionManager::isDevVersion()
        ]);
    }

    public function getRemoteBaseVersion(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.dashboard') || !$request->attributes->get('api_key')->can('admin.dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Remote base version retrieved successfully',
            'version' => VersionManager::getRemoteBaseVersion()
        ]);
    }

    public function isBaseUpToDate(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.dashboard') || !$request->attributes->get('api_key')->can('admin.dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Base version status retrieved successfully',
            'up_to_date' => VersionManager::isBaseUpToDate(),
            'current_version' => VersionManager::getCurrentBaseVersion(),
            'remote_version' => VersionManager::getRemoteBaseVersion()
        ]);
    }

    public function getCurrentModuleVersion(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.dashboard') || !$request->attributes->get('api_key')->can('admin.dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Current module version retrieved successfully',
            'version' => ModuleManager::getVersion('Admin'),
        ]);
    }

    public function getRemoteModuleVersion(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.dashboard') || !$request->attributes->get('api_key')->can('admin.dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Remote module version retrieved successfully',
            'version' => ModuleManager::getRemoteVersion('Admin'),
        ]);
    }

    public function isModuleUpToDate(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->can('admin.dashboard') || !$request->attributes->get('api_key')->can('admin.dashboard')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Module version status retrieved successfully',
            'version' => ModuleManager::getVersion('Admin') === ModuleManager::getRemoteVersion('Admin'),
            'current_version' => ModuleManager::getVersion('Admin'),
            'remote_version' => ModuleManager::getRemoteVersion('Admin')
        ]);
    }
}
