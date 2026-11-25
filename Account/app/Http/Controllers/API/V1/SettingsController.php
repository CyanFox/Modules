<?php

namespace Modules\Account\Http\Controllers\API\V1;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Models\UserSetting;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Account Settings')]
class SettingsController
{
    #[Endpoint(title: 'Get Settings', description: 'Returns the authenticated user\'s settings. Hidden settings are excluded. Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'Settings retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Settings retrieved.', 'data' => [
        [
            'id' => 1,
            'user_id' => 1,
            'key' => 'user.setting',
            'value' => 'Setting',
            'properties' => [],
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getSettings(Request $request)
    {
        $settings = QueryBuilder::for($request->user()->userSettings())
            ->allowedSorts('key', 'created_at', 'updated_at')
            ->allowedFilters('key')
            ->get()
            ->filter(fn (Model $setting) => ! $this->isHidden($setting))
            ->map(function (Model $setting) {
                $this->hydrateSetting($setting);

                return $setting;
            })
            ->values();

        $perPage = (int) ($request->query('perPage') ?: 15);
        $perPage = $perPage > 0 ? $perPage : 15;
        $page = max((int) $request->query('page', 1), 1);

        $settings = new LengthAwarePaginator(
            $settings->slice(($page - 1) * $perPage, $perPage)->values(),
            $settings->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );

        return apiResponse('Settings retrieved.', $settings);
    }

    private function isHidden(Model $setting): bool
    {
        if (! $setting instanceof UserSetting) {
            return true;
        }

        return ($setting->hasProperty(SettingsProperty::HIDDEN) && $setting->getProperty(SettingsProperty::HIDDEN))
            || $setting->hasProperty(SettingsProperty::INTERNAL);
    }

    private function hydrateSetting(Model $setting): void
    {
        if (! $setting instanceof UserSetting) {
            return;
        }
        if ($setting->getProperty(SettingsProperty::ENCRYPTED)) {
            try {
                $setting->value = decrypt($setting->value);
            } catch (Exception) {
                $setting->value = null;
            }
        }

        $setting->properties = json_decode($setting->properties) ?? [];
    }

    #[Endpoint(title: 'Update Settings', description: 'Takes an array of setting keys and values. Hidden settings are ignored.')]
    #[Response(200, 'Settings updated.', examples: ['application/json' => ['success' => true, 'message' => 'Settings updated.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updateSettings(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        $userId = (int) $request->user()->getAuthIdentifier();

        foreach ($request->input('settings') as $key => $value) {
            $setting = UserSetting::where(['user_id' => $userId, 'key' => $key])->first();

            if (! $setting || $this->isHidden($setting)) {
                continue;
            }

            userSettings()->update($userId, $key, $value);
        }

        return apiResponse('Settings updated.');
    }

    #[Endpoint(title: 'Delete Setting')]
    #[PathParameter('settingId', 'Setting ID')]
    #[Response(200, 'Setting deleted.', examples: ['application/json' => ['success' => true, 'message' => 'Setting deleted.', 'data' => []]])]
    #[Response(404, 'Setting not found.', examples: ['application/json' => ['success' => false, 'message' => 'Setting not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteSetting(Request $request, $settingId)
    {
        $setting = $request->user()->userSettings()->where('id', $settingId)->first();

        if (! $setting || $this->isHidden($setting)) {
            return apiResponse('Setting not found.', apiError('account.settings.not_found'), false, 404);
        }

        $setting->delete();

        return apiResponse('Setting deleted.');
    }
}
