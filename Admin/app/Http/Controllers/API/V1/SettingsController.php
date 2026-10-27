<?php

namespace Modules\Admin\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Facades\Settings;
use Modules\Core\Models\Setting;

#[Group('Admin Settings')]
class SettingsController extends Controller
{
    #[Endpoint(title: 'Change Logo')]
    #[Response(200, 'Logo updated.', examples: ['application/json' => ['success' => true, 'message' => 'Logo updated.', 'data' => [
        'logo' => 'logo'
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function changeLogo(Request $request)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $request->validate([
            'logo' => 'required|image:allow_svg',
        ]);

        Storage::disk('public')->delete('img/' . str_replace('/storage/img/', '', settings('app.logo')));

        $request->logo->storeAs('img', 'Logo.' . $request->logo->getClientOriginalExtension(), 'public');

        Settings::set('app.logo', '/storage/img/Logo.' . $request->logo->getClientOriginalExtension(), true);

        return apiResponse('Logo updated.', ['logo' => Settings::get('app.logo')]);
    }

    #[Endpoint(title: 'Reset Logo')]
    #[Response(200, 'Logo reset.', examples: ['application/json' => ['success' => true, 'message' => 'Logo reset.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function resetLogo(Request $request)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        Storage::disk('public')->delete('img/' . str_replace('/storage/img/', '', settings('app.logo')));

        Settings::set('app.logo', '/img/Logo.svg', true);

        activity()
            ->causedBy(auth()->user())
            ->log('admin.settings.logo.reset');

        return apiResponse('Logo reset.');
    }

    #[Endpoint(title: 'Get Settings')]
    #[Response(200, 'Settings retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Settings retrieved.', 'data' => [
        [
            'id' => 1,
            'key' => 'key',
            'value' => 'value',
            'properties' => [],
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getSettings(Request $request)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        return apiResponse('Settings retrieved.', Setting::all()->each(function (Setting $setting) {
            if (is_string($setting->properties)) {
                $setting->setAttribute('properties', json_decode($setting->properties, true));
            }
            $setting->setAttribute('value', Settings::get($setting->key, setting: $setting));
        }));
    }

    #[Endpoint(title: 'Get Setting')]
    #[Response(200, 'Setting retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Setting retrieved.', 'data' => [
        'id' => 1,
        'key' => 'key',
        'value' => 'value',
        'properties' => [],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getSetting(Request $request, $key)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $setting = Setting::where('key', $key)->first();
        $setting->properties = json_decode($setting->properties, true);
        $setting->value = Settings::get($setting->key, setting: $setting);

        return apiResponse('Setting retrieved.', $setting);
    }

    #[Endpoint(title: 'Create Setting')]
    #[Response(200, 'Setting created.', examples: ['application/json' => ['success' => true, 'message' => 'Setting created.', 'data' => [
        'id' => 1,
        'key' => 'key',
        'value' => 'value',
        'properties' => [],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function createSetting(Request $request)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $request->validate([
            'key' => 'required|unique:settings,key',
            'value' => 'required',
            'properties' => 'nullable|array',
            'update' => 'nullable|boolean'
        ]);

        $setting = Settings::set($request->key, $request->value, $request->update ?? false, $request->properties);
        $setting->properties = json_decode($setting->properties, true);

        return apiResponse('Setting created.', $setting);
    }

    #[Endpoint(title: 'Update Setting')]
    #[Response(200, 'Setting updated.', examples: ['application/json' => ['success' => true, 'message' => 'Setting updated.', 'data' => [
        'id' => 1,
        'key' => 'key',
        'value' => 'value',
        'properties' => [],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updateSetting(Request $request, $key)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $request->validate([
            'value' => 'required',
            'properties' => 'nullable|array'
        ]);

        $setting = Settings::update($key, $request->value, $request->properties);
        $setting->properties = json_decode($setting->properties, true);

        return apiResponse('Setting updated.', $setting);
    }

    #[Endpoint(title: 'Update Settings')]
    #[Response(200, 'Settings updated.', examples: ['application/json' => ['success' => true, 'message' => 'Settings updated.', 'data' => [
        [
            'id' => 1,
            'key' => 'key',
            'value' => 'value',
            'properties' => [],
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updateSettings(Request $request)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $payload = $request->all();
        Validator::make($payload, [
            '*' => ['required', 'array'],
        ])->validate();

        $settings = [];

        foreach ($payload as $key => $data) {
            Validator::make($data, [
                'value' => ['required'],
                'properties' => ['nullable', 'array'],
            ])->validate();

            $setting = Settings::update($key, $data['value'], $data['properties'] ?? null);
            $setting->properties = json_decode($setting->properties, true);
            $settings[$key] = $setting;
        }

        return apiResponse('Settings updated.', $settings);
    }

    #[Endpoint(title: 'Delete Setting')]
    #[Response(200, 'Setting deleted.', examples: ['application/json' => ['success' => true, 'message' => 'Setting deleted.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteSetting(Request $request, $key)
    {
        if ($request->user()->cannot('admin.settings')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        if (!Settings::delete($key)) {
            return apiResponse('Setting not found.', [], false, 404);
        }

        return apiResponse('Setting deleted.');
    }
}
