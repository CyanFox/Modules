<?php

namespace Modules\Account\Http\Controllers\API\V1;

use Closure;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use PragmaRX\Google2FA\Google2FA;

#[Group('Account MFA')]
class MFAController
{
    #[Endpoint(title: 'Enable MFA')]
    #[Response(200, 'MFA enabled.', examples: ['application/json' => ['success' => true, 'message' => 'MFA enabled.', 'data' => [
        'recovery_codes' => []
    ]]])]
    #[Response(422, 'MFA already enabled.', examples: ['application/json' => ['success' => false, 'message' => 'MFA already enabled.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function enableMFA(Request $request)
    {
        $request->validate([
            'password' => 'required|string|current_password',
            'code' => ['required', 'digits:6', function (string $attribute, mixed $value, Closure $fail) use ($request) {
                $google2FA = new Google2FA();
                if (!$google2FA->verifyKey(UserSettings::get($request->user()->id, 'auth.mfa.secret'), $value)) {
                    $fail('invalid mfa code');
                }
            }]
        ]);
        if (userSettings('auth.mfa.enabled', $request->user()->id)) {
            return apiResponse('MFA already enabled.', [], false, 422);
        }

        $recoveryCodes = $this->regenerateCodes();

        UserSettings::set(auth()->id(), 'auth.mfa.enabled', true);
        UserSettings::set($request->user()->id, 'auth.mfa.recovery_codes', $recoveryCodes, true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);

        return apiResponse('MFA enabled.', ['recovery_codes' => $recoveryCodes]);
    }

    private function regenerateCodes()
    {
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = str()->random();
        }

        return $recoveryCodes;
    }

    #[Endpoint(title: 'Disable MFA')]
    #[Response(200, 'MFA disabled.', examples: ['application/json' => ['success' => true, 'message' => 'MFA disabled.', 'data' => []]])]
    #[Response(422, 'MFA not enabled.', examples: ['application/json' => ['success' => false, 'message' => 'MFA not enabled.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function disableMFA(Request $request)
    {
        $request->validate([
            'password' => 'required|string|current_password',
        ]);

        if (!userSettings('auth.mfa.enabled', $request->user()->id)) {
            return apiResponse('MFA not enabled.', [], false, 422);
        }

        UserSettings::delete($request->user()->id, 'auth.mfa.recovery_codes');
        UserSettings::delete($request->user()->id, 'auth.mfa.secret');
        UserSettings::delete($request->user()->id, 'auth.mfa.enabled');

        return apiResponse('MFA disabled');
    }

    #[Endpoint(title: 'Regenerate MFA Recovery Codes')]
    #[Response(200, 'Recovery codes regenerated.', examples: ['application/json' => ['success' => true, 'message' => 'Recovery codes regenerated.', 'data' => [
        'recovery_codes' => []
    ]]])]
    #[Response(422, 'MFA not enabled.', examples: ['application/json' => ['success' => false, 'message' => 'MFA not enabled.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|string|current_password',
        ]);

        if (!userSettings('auth.mfa.enabled', $request->user()->id)) {
            return apiResponse('MFA not enabled.', [], false, 422);
        }

        $recoveryCodes = $this->regenerateCodes();

        UserSettings::set($request->user()->id, 'auth.mfa.recovery_codes', $recoveryCodes, true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);

        return apiResponse('Recovery codes regenerated.', ['recovery_codes' => $recoveryCodes]);
    }

    #[Endpoint(title: 'Generate MFA Secret')]
    #[Response(200, 'MFA secret generated.', examples: ['application/json' => ['success' => true, 'message' => 'MFA secret generated.', 'data' => [
        'secret' => ''
    ]]])]
    #[Response(422, 'MFA already enabled.', examples: ['application/json' => ['success' => false, 'message' => 'MFA already enabled.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function generateMFASecret(Request $request)
    {
        $request->validate([
            'password' => 'required|string|current_password',
        ]);

        if (userSettings('auth.mfa.enabled', $request->user()->id)) {
            return apiResponse('MFA already enabled.', [], false, 422);
        }

        $google2FA = new Google2FA();

        $secret = $google2FA->generateSecretKey();

        UserSettings::set($request->user()->id, 'auth.mfa.secret', $secret, true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);

        return apiResponse('MFA Secret generated.', $secret);
    }

    #[Endpoint(title: 'Verify MFA')]
    #[Response(200, 'MFA verified.', examples: ['application/json' => ['success' => true, 'message' => 'MFA secret verified.', 'data' => []]])]
    #[Response(422, 'MFA not enabled.', examples: ['application/json' => ['success' => false, 'message' => 'MFA not enabled.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function verifyMFA(Request $request)
    {
        if (!userSettings('auth.mfa.enabled', $request->user()->id)) {
            return apiResponse('MFA not enabled.', [], false, 422);
        }

        $request->validate([
            'code' => ['required', 'digits:6', function (string $attribute, mixed $value, Closure $fail) use ($request) {
                $google2FA = new Google2FA();
                if (!$google2FA->verifyKey(UserSettings::get($request->user()->id, 'auth.mfa.secret'), $value)) {
                    $fail('invalid mfa code');
                }
            }]
        ]);

        return apiResponse('MFA verified.');
    }
}
