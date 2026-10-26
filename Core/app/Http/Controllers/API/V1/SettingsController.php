<?php

namespace Modules\Core\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Models\Setting;
use Modules\Core\Models\UserSetting;

class SettingsController extends Controller
{
    #[Endpoint(title: 'Get Setting')]
    #[PathParameter('key', 'Setting Key')]
    #[Response(200, 'Setting retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Setting retrieved.', 'data' => [
        'id' => 1,
        'key' => 'app.name',
        'value' => 'Base',
        'properties' => [],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    #[Response(404, 'Setting not found.', examples: ['application/json' => ['success' => false, 'message' => 'Setting not found.', 'data' => []]])]
    public function getSetting(string $key)
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return apiResponse('Setting not found.', [], false, 404);
        }

        if ($setting->getProperty(SettingsProperty::INTERNAL)) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        if ($setting->getProperty(SettingsProperty::PERMISSIONS) || $setting->getProperty(SettingsProperty::ROLES) || $setting->getProperty(SettingsProperty::AUTH)) {
            $user = auth('sanctum')->user();
            if (!$user) {
                return apiResponse('Unauthenticated.', [], false, 401);
            }

            if (array_any($setting->getProperty(SettingsProperty::PERMISSIONS) ?? [], fn($permission) => !$user->can($permission))) {
                return apiResponse('Unauthenticated.', [], false, 401);
            }

            if (array_any($setting->getProperty(SettingsProperty::ROLES) ?? [], fn($role) => !$user->hasRole($role))) {
                return apiResponse('Unauthenticated.', [], false, 401);
            }
        }

        if ($setting->getProperty(SettingsProperty::ENCRYPTED)) {
            $setting->value = decrypt($setting->value);
        }

        $setting->properties = json_decode($setting->properties);

        return apiResponse('Setting retrieved.', $setting);
    }


    #[Endpoint(title: 'Get User Setting')]
    #[PathParameter('key', 'Setting Key')]
    #[Response(200, 'Setting retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Setting retrieved.', 'data' => [
        'id' => 1,
        'user_id' => 1,
        'key' => 'user.setting',
        'value' => 'Setting',
        'properties' => [],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    #[Response(404, 'Setting not found.', examples: ['application/json' => ['success' => false, 'message' => 'Setting not found.', 'data' => []]])]
    public function getUserSetting(string $key)
    {
        $user = auth('sanctum')->user();

        $setting = UserSetting::where(['key' => $key, 'user_id' => $user->id])->first();
        if (!$setting) {
            return apiResponse('Setting not found', [], false, 404);
        }

        if ($setting->getProperty(SettingsProperty::INTERNAL)) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        if ($setting->getProperty(SettingsProperty::ENCRYPTED)) {
            $setting->value = decrypt($setting->value);
        }

        $setting->properties = json_decode($setting->properties);

        return apiResponse('Setting retrieved.', $setting);
    }
}
