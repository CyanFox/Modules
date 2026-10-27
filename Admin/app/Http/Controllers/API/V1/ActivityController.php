<?php

namespace Modules\Admin\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Admin Activity')]
class ActivityController extends Controller
{
    #[Endpoint(title: 'Get Activities', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'Activities retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Activities retrieved.', 'data' => [
        [
            'id' => 1,
            'log_name' => 'default',
            'description' => 'descrition',
            'subject_type' => 'Some\\Model',
            'subject_id' => 1,
            'event' => 'created',
            'causer_type' => 'Some\\Model',
            'causer_id' => 1,
            'attribute_changes' => [
                'attributes' => [],
                'old' => []
            ],
            'properties' => [],
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getActivities(Request $request)
    {
        if ($request->user()->cannot('admin.activity')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        return apiResponse('Activities retrieved.', QueryBuilder::for(Activity::class)
            ->allowedSorts('created_at', 'causer_id', 'causer_type', 'description', 'log_name', 'event', 'subject_id', 'subject_type')
            ->allowedFilters('causer_id', 'causer_type', 'description', 'log_name', 'event', 'attribute_changes', 'subject_id', 'subject_type')
            ->paginate($request->query('perPage') ?? null)
            ->appends($request->query()));
    }
}
