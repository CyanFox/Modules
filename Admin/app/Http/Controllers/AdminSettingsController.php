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
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.settings.editor')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Settings retrieved successfully', Setting::all());
    }

    #[QueryParameter('encrypted', description: 'Whether to return decrypted settings', type: 'boolean', default: false, example: true)]
    #[PathParameter('settingsKey', description: 'Key of the setting to retrieve', type: 'string', example: 'internal.app.name')]
    public function getSetting(Request $request, $settingsKey)
    {
        $isEncrypted = $request->query('encrypted', false);
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.settings.editor')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $setting = SettingsManager::getSetting($settingsKey, isEncrypted: $isEncrypted);

        if (! $setting) {
            return apiResponse('Setting not found', null, false, 404);
        }

        return apiResponse('Setting retrieved successfully', [
            'key' => $settingsKey,
            'value' => $setting,
        ]);
    }

    #[PathParameter('settingsKey', description: 'Key of the setting to update', type: 'string', example: 'internal.app.name')]
    public function updateSetting(Request $request, $settingsKey)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.settings.editor')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $value = $request->input('value');
        $isEncrypted = $request->input('encrypted', false);
        $locked = $request->input('locked', false);

        SettingsManager::updateSetting($settingsKey, $value, isLocked: $locked, isEncrypted: $isEncrypted);

        return apiResponse('Setting updated successfully', [
            'key' => $settingsKey,
            'value' => $value,
        ]);
    }

    #[PathParameter('settingsKey', description: 'Key of the setting to delete', type: 'string', example: 'internal.app.name')]
    public function deleteSetting(Request $request, $settingsKey)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.settings.editor')) {
            return $apiKey->sendNoPermissionResponse();
        }

        SettingsManager::deleteSetting($settingsKey);

        return apiResponse('Setting deleted successfully', $settingsKey);
    }

    public function createSetting(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.settings.editor')) {
            return $apiKey->sendNoPermissionResponse();
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

        return apiResponse('Setting created successfully', $setting);
    }

    public function encryptValue(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.settings.editor')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $value = $request->input('value');

        if (blank($value)) {
            return apiResponse('Value is required', null, false, 400);
        }

        return apiResponse('Value encrypted successfully', encrypt($value));
    }

    public function decryptValue(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.settings.editor')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $value = $request->input('value');

        if (blank($value)) {
            return apiResponse('Value is required', null, false, 400);
        }

        try {
            $decryptedValue = decrypt($value);
        } catch (Exception $e) {
            return apiResponse('Decryption failed', $e->getMessage(), false, 500);
        }

        return apiResponse('Value decrypted successfully', $decryptedValue);
    }
}
