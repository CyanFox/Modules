<?php

namespace Modules\Admin\Http\Controllers;

use App\Facades\SettingsManager;
use App\Models\Setting;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Exception;
use Illuminate\Http\Request;

#[Group('Admin Settings')]
class AdminSettingsController
{
    public function getSettings(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.settings.editor') || ! $request->attributes->get('api_key')->can('admin.settings.editor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Settings retrieved successfully',
            'settings' => Setting::all(),
        ]);
    }

    #[QueryParameter('encrypted', description: 'Whether to return decrypted settings', type: 'boolean', default: false, example: true)]
    #[PathParameter('settingsKey', description: 'Key of the setting to retrieve', type: 'string', example: 'internal.app.name')]
    public function getSetting(Request $request, $settingsKey)
    {
        $user = $request->attributes->get('api_key')->user;
        $isEncrypted = $request->query('encrypted', false);

        if (! $user->can('admin.settings.editor') || ! $request->attributes->get('api_key')->can('admin.settings.editor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $setting = SettingsManager::getSetting($settingsKey, isEncrypted: $isEncrypted);

        if (! $setting) {
            return response()->json(['error' => 'Setting not found'], 404);
        }

        return response()->json([
            'message' => 'Setting retrieved successfully',
            'key' => $settingsKey,
            'value' => $setting,
        ]);
    }

    #[PathParameter('settingsKey', description: 'Key of the setting to update', type: 'string', example: 'internal.app.name')]
    public function updateSetting(Request $request, $settingsKey)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.settings.editor') || ! $request->attributes->get('api_key')->can('admin.settings.editor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $value = $request->input('value');
        $isEncrypted = $request->input('encrypted', false);
        $locked = $request->input('locked', false);

        SettingsManager::updateSetting($settingsKey, $value, isLocked: $locked, isEncrypted: $isEncrypted);

        return response()->json([
            'message' => 'Setting updated successfully',
            'key' => $settingsKey,
            'value' => $value,
        ]);
    }

    #[PathParameter('settingsKey', description: 'Key of the setting to delete', type: 'string', example: 'internal.app.name')]
    public function deleteSetting(Request $request, $settingsKey)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.settings.editor') || ! $request->attributes->get('api_key')->can('admin.settings.editor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        SettingsManager::deleteSetting($settingsKey);

        return response()->json([
            'message' => 'Setting deleted successfully',
            'key' => $settingsKey,
        ]);
    }

    public function createSetting(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.settings.editor') || ! $request->attributes->get('api_key')->can('admin.settings.editor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required',
            'encrypted' => 'nullable|boolean',
        ]);

        $setting = SettingsManager::setSetting(
            $request->input('key'),
            $request->input('value'),
            isLocked: $request->input('locked', false),
            isEncrypted: $request->input('encrypted', false),
        );

        return response()->json([
            'message' => 'Setting created successfully',
            'setting' => $setting,
        ]);
    }

    public function encryptValue(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.settings.editor') || ! $request->attributes->get('api_key')->can('admin.settings.editor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $value = $request->input('value');

        if (blank($value)) {
            return response()->json(['error' => 'Value is required'], 400);
        }

        return response()->json([
            'message' => 'Value encrypted successfully',
            'value' => encrypt($value),
        ]);
    }

    public function decryptValue(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.settings.editor') || ! $request->attributes->get('api_key')->can('admin.settings.editor')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $value = $request->input('value');

        if (blank($value)) {
            return response()->json(['error' => 'Value is required'], 400);
        }

        try {
            $decryptedValue = decrypt($value);
        } catch (Exception $e) {
            return response()->json(['error' => 'Decryption failed: '.$e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Value decrypted successfully',
            'value' => $decryptedValue,
        ]);
    }
}
