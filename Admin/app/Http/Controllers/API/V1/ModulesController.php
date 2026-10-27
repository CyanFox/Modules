<?php

namespace Modules\Admin\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

#[Group('Admin Modules')]
class ModulesController extends Controller
{
    #[Endpoint(title: 'Get Modules')]
    #[Response(200, 'Modules retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Modules retrieved.', 'data' => [
        'modules' => [
            'name' => 'Module',
            'description' => 'Module description',
            'enabled' => true,
            'authors' => [],
            'version' => '1.0.0',
            'settings_page' => null,
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getModules(Request $request)
    {
        if ($request->user()->cannot('admin.modules')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        return apiResponse('Modules retrieved.', collect(Module::all())->map(function ($module) {
            return [
                'name' => $module->getName(),
                'description' => $module->getDescription(),
                'enabled' => $module->isEnabled(),
                'authors' => modules()->getAuthors($module),
                'version' => modules()->getVersion($module),
                'settings_page' => modules()->getSettingsPage($module),
            ];
        }));
    }

    #[Endpoint(title: 'Get Module')]
    #[Response(200, 'Module retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Module retrieved.', 'data' => [
        'name' => 'Module',
        'description' => 'Module description',
        'enabled' => true,
        'authors' => [],
        'version' => '1.0.0',
        'requires' => [],
        'requirements_met' => true,
        'remote_version' => null,
        'keywords' => [],
        'settings_page' => null,
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getModule(Request $request, string $module)
    {
        if ($request->user()->cannot('admin.modules')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        return apiResponse('Module retrieved.', [
            'name' => modules()->getModule($module)->getName(),
            'description' => modules()->getModule($module)->getDescription(),
            'enabled' => modules()->getModule($module)->isEnabled(),
            'authors' => modules()->getAuthors($module),
            'version' => modules()->getVersion($module),
            'requires' => modules()->getRequirements($module),
            'requirements_met' => modules()->checkRequirements($module),
            'remote_version' => modules()->getRemoteVersion($module),
            'keywords' => modules()->getKeywords($module),
            'settings_page' => modules()->getSettingsPage($module),
        ]);
    }

    #[Endpoint(title: 'Disable Module')]
    #[Response(200, 'Module disabled.', examples: ['application/json' => ['success' => true, 'message' => 'Module disabled.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function disableModule(Request $request, string $module)
    {
        if ($request->user()->cannot('admin.modules.disable')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        Module::find($module)->disable();

        return apiResponse('Module disabled.');
    }

    #[Endpoint(title: 'Enable Module')]
    #[Response(200, 'Module enabled.', examples: ['application/json' => ['success' => true, 'message' => 'Module enabled.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function enableModule(Request $request, string $module)
    {
        if ($request->user()->cannot('admin.modules.enable')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        Module::find($module)->enable();

        return apiResponse('Module enabled.');
    }

    #[Endpoint(title: 'Delete Module')]
    #[Response(200, 'Module deleted.', examples: ['application/json' => ['success' => true, 'message' => 'Module deleted.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteModule(Request $request, string $module)
    {
        if ($request->user()->cannot('admin.modules.delete')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        Module::find($module)->delete();

        return apiResponse('Module deleted.');
    }
}
