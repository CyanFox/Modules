<?php

namespace Modules\Admin\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

#[Group('Admin Activity')]
class AdminActivityController
{
    #[QueryParameter('per_page', description: 'Number of activity entries per page', type: 'integer', default: 20, example: 10)]
    public function getActivity(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.activity')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $activityLog = Activity::orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 20));

        return apiResponse('Activity retrieved successfully',
            $activityLog->map(function (Activity $activity) {
                $properties = json_decode($activity->properties, true) ?? [];

                return [
                    'id' => $activity->id,
                    'description' => $activity->description,
                    'event' => $activity->event,
                    'created_at' => $activity->created_at->toIso8601String(),
                    'properties' => $properties,
                    'old_values' => $properties['old'] ?? [],
                    'new_values' => $properties['attributes'] ?? [],
                ];
            }));
    }
}
