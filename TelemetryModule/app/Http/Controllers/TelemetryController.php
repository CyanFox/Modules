<?php

namespace Modules\TelemetryModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\TelemetryModule\Models\Telemetry;

class TelemetryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'instance' => 'required|string',
            'modules' => 'required|integer',
            'os' => 'required|string',
            'php' => 'required|string',
            'laravel' => 'required|string',
            'db' => 'required|string',
            'timezone' => 'required|string',
            'lang' => 'required|string',
            'template_version' => 'required|string',
            'project_version' => 'required|string',
        ]);

        try {
            $telemetry = Telemetry::where('instance', $request->instance)->first();

            if ($telemetry) {
                $telemetry->update($request->all());
            } else {
                Telemetry::create($request->all());
            }

            Telemetry::where('updated_at', '<', now()->subWeek())->delete();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Telemetry data stored successfully. Thanks :D'], 201);
    }

    public function delete($instanceId)
    {
        try {
            $telemetry = Telemetry::where('instance', $instanceId)->first();
            if (!$telemetry) {
                return response()->json(['error' => 'Instance not found'], 404);
            }
            $telemetry->delete();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'All telemetry data for instance ' . $instanceId . ' deleted successfully. Don\'t forget to set telemetry enabled to false']);
    }
}
