<?php

namespace Modules\Auth\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Mews\Captcha\Captcha;
use Modules\Auth\Emails\ForgotPasswordMail;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use Modules\Core\Models\User;
use Modules\Core\Models\UserSetting;
use PragmaRX\Google2FA\Google2FA;

#[Group('Auth')]
class AuthController extends Controller
{
    #[Endpoint(title: 'Login')]
    #[Response(200, 'Login successful.', examples: ['application/json' => ['success' => true, 'message' => 'Login successful.', 'data' => [
        'user' => [
            'id' => 1,
            'first_name' => 'first name',
            'last_name' => 'last name',
            'username' => 'username',
            'email' => 'email',
            'theme' => 'theme',
            'language' => 'language',
            'disabled' => false,
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ],
        'token' => '00|abcdefghijklmnopqrstuvwxyz',
        'uuid' => '00000000-0000-0000-0000-000000000000',
    ]]])]
    #[Response(403, 'This account has been disabled.', examples: ['application/json' => ['success' => false, 'message' => 'This account has been disabled.', 'data' => []]])]
    #[Response(422, 'Captcha validation failed.', examples: ['application/json' => ['success' => false, 'message' => 'Captcha validation failed.', 'data' => []]])]
    #[Response(403, 'Login is disabled.', examples: ['application/json' => ['success' => false, 'message' => 'Login is disabled.', 'data' => []]])]
    #[Response(400, 'Invalid username or password.', examples: ['application/json' => ['success' => false, 'message' => 'Invalid username or password.', 'data' => []]])]
    #[Response(401, 'MFA Required.', examples: ['application/json' => ['success' => false, 'message' => 'MFA Required.', 'data' => ['mfa_required' => true]]])]
    public function login(Request $request)
    {
        if (!settings('auth.login.enabled')) {
            return apiResponse('Login is disabled.', [], false, 403);
        }

        if (settings('auth.login.captcha')) {
            $validator = Validator::make($request->all(), [
                'captcha_key' => ['required', 'string'],
                'captcha' => ['required', 'string', 'captcha_api:' . $request->captcha_key . ',' . $request->captcha_type ?? 'default'],
            ]);

            if ($validator->fails()) {
                return apiResponse('Captcha validation failed.', [], false, 422);
            }
        }

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'device_name' => 'required|string',
            'api_type' => 'nullable|in:api,native',
            'platform' => 'nullable|in:android,ios,linux,windows,macos,desktop,unknown',
        ]);

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return apiResponse('Invalid username or password.', [], false, 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return apiResponse('Invalid username or password.', [], false, 400);
        }

        if ($user->disabled) {
            return apiResponse('This account has been disabled.', [], false, 403);
        }

        if (userSettings('auth.mfa.enabled', $user->id)) {
            if (filled($request->mfa_code)) {
                $google2FA = new Google2FA();

                $secret = userSettings('auth.mfa.secret', $user->id);

                if (!$google2FA->verifyKey($secret, $request->mfa_code)) {
                    $recoveryCodes = userSettings('auth.mfa.recovery_codes', $user->id);
                    if (!in_array($request->mfa_code, $recoveryCodes)) {
                        throw ValidationException::withMessages([
                            'mfaCode' => __('auth::login.invalid_mfa_code'),
                        ]);
                    }

                    unset($recoveryCodes[array_search($request->mfa_code, $recoveryCodes)]);

                    UserSettings::set($user->id, 'auth.mfa.recovery_codes', $recoveryCodes, true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);
                }
            } else {
                return apiResponse('MFA Required.', ['mfa_required' => true], false, 401);
            }
        }

        $uuid = str()->uuid();

        $token = $user->createToken(json_encode(['name' => $request->device_name, 'type' => $request->api_type ?? 'native', 'platform' => $request->platform ?? 'unknown', 'uuid' => $uuid]))->plainTextToken;

        return apiResponse('Login successful.', [
            'user' => $user,
            'token' => $token,
            'uuid' => $uuid,
        ]);
    }

    #[Endpoint(title: 'QR-Code Login')]
    #[Response(200, 'Login successful.', examples: ['application/json' => ['success' => true, 'message' => 'Login successful.', 'data' => [
        'user' => [
            'id' => 1,
            'first_name' => 'first name',
            'last_name' => 'last name',
            'username' => 'username',
            'email' => 'email',
            'theme' => 'theme',
            'language' => 'language',
            'disabled' => false,
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ],
        'token' => '00|abcdefghijklmnopqrstuvwxyz',
        'uuid' => '00000000-0000-0000-0000-000000000000',
    ]]])]
    #[Response(404, 'User not found.', examples: ['application/json' => ['success' => false, 'message' => 'User not found.', 'data' => []]])]
    public function loginQrCode(Request $request, int $userId)
    {
        if (!settings('auth.login.enabled')) {
            return apiResponse('Login is disabled.', [], false, 422);
        }

        $request->validate([
            'device_name' => 'required|string',
            'api_type' => 'nullable|in:api,native',
            'platform' => 'nullable|in:android,ios,linux,windows,macos,desktop,unknown',
        ]);

        $user = User::find($userId);
        if (!$user) {
            return apiResponse('User not found.', [], false, 404);
        }

        $uuid = str()->uuid();

        $token = $user->createToken(json_encode(['name' => $request->device_name, 'type' => $request->api_type ?? 'native', 'platform' => $request->platform ?? 'unknown', 'uuid' => $uuid]))->plainTextToken;

        return apiResponse('Login successful.', [
            'user' => $user,
            'token' => $token,
            'uuid' => $uuid,
        ]);
    }

    #[Endpoint(title: 'Register')]
    #[Response(200, 'User registered successfully.', examples: ['application/json' => ['success' => true, 'message' => 'Login successful.', 'data' => [
        'id' => 1,
        'first_name' => 'first name',
        'last_name' => 'last name',
        'username' => 'username',
        'email' => 'email',
        'theme' => 'theme',
        'language' => 'language',
        'disabled' => false,
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(422, 'Captcha validation failed.', examples: ['application/json' => ['success' => false, 'message' => 'Captcha validation failed.', 'data' => []]])]
    #[Response(403, 'Registration is disabled.', examples: ['application/json' => ['success' => false, 'message' => 'Registration is disabled.', 'data' => []]])]
    public function register(Request $request)
    {
        if (!settings('auth.register.enabled')) {
            return apiResponse('Registration is disabled.', [], false, 403);
        }

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'string', Password::defaults()],
        ]);

        if (settings('auth.register.captcha')) {
            $validator = Validator::make($request->all(), [
                'captcha_key' => ['required', 'string'],
                'captcha' => ['required', 'string', 'captcha_api:' . $request->captcha_key . ',' . $request->captcha_type ?? 'default'],
            ]);

            if ($validator->fails()) {
                return apiResponse('Captcha validation failed.', [], false, 422);
            }
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return apiResponse('User registered successfully.', $user);
    }

    #[Endpoint(title: 'Forgot Password')]
    #[PathParameter('resetToken', 'Token from Email', required: false, example: 'abcdefghijklm123456789')]
    #[Response(200, 'Password reset successfully.', examples: ['application/json' => ['success' => true, 'message' => 'Password reset successfully.', 'data' => []]])]
    #[Response(422, 'Captcha validation failed.', examples: ['application/json' => ['success' => false, 'message' => 'Captcha validation failed.', 'data' => []]])]
    #[Response(422, 'Token expired.', examples: ['application/json' => ['success' => false, 'message' => 'Token expired.', 'data' => []]])]
    #[Response(403, 'Forgot password is disabled.', examples: ['application/json' => ['success' => false, 'message' => 'Forgot password is disabled.', 'data' => []]])]
    public function forgotPassword(Request $request, $resetToken = null)
    {
        if (!settings('auth.forgot_password.enabled')) {
            return apiResponse('Forgot password is disabled.', [], false, 403);
        }

        if ($resetToken) {
            $request->validate([
                'password' => ['required', 'string', Password::defaults()],
            ]);


            $user = UserSetting::where('key', 'auth.forgot_password.token')->where('value', $resetToken)->first()?->user;
            if (!$user) {
                return apiResponse('Invalid token.', [], false, 422);
            }

            if (carbon()->parse(userSettings('auth.forgot_password.expiration', $user->id))->isBefore(now())) {
                userSettings()->delete($user->id, 'auth.forgot_password.token');
                userSettings()->delete($user->id, 'auth.forgot_password.expiration');

                return apiResponse('Token expired.', [], false, 422);
            }

            $user->update([
                'password' => $request->password,
            ]);

            userSettings()->delete($user->id, 'auth.forgot_password.token');
            userSettings()->delete($user->id, 'auth.forgot_password.expiration');

            activity()
                ->performedOn($user)
                ->causedByAnonymous()
                ->log('auth.forgot_password.reset');

            return apiResponse('Password reset successfully.');
        }

        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        if (settings('auth.forgot_password.captcha')) {
            $validator = Validator::make($request->all(), [
                'captcha_key' => ['required', 'string'],
                'captcha' => ['required', 'string', 'captcha_api:' . $request->captcha_key . ',' . $request->captcha_type ?? 'default'],
            ]);

            if ($validator->fails()) {
                return apiResponse('Captcha validation failed.', [], false, 422);
            }
        }

        $user = userModel()->where('username', $request->username)->first();
        if ($user) {
            $token = Str::random(64);
            $expiration = now()->addHour();
            userSettings()->set($user->id, 'auth.forgot_password.token', $token, true, [SettingsProperty::INTERNAL => true, SettingsProperty::HIDDEN => true]);
            userSettings()->set($user->id, 'auth.forgot_password.expiration', $expiration, true, [SettingsProperty::INTERNAL => true, SettingsProperty::HIDDEN => true]);

            Mail::to($user->email)->queue(new ForgotPasswordMail($user, $token, $expiration));

            activity()
                ->performedOn($user)
                ->causedByAnonymous()
                ->log('auth.forgot_password.sent');
        }

        return apiResponse('Password reset email sent.');
    }

    #[Endpoint(title: 'Logout')]
    #[Response(200, 'Logged out successfully.', examples: ['application/json' => ['success' => true, 'message' => 'Logged out successfully.', 'data' => []]])]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return apiResponse('Logged out successfully.');
    }

    #[Endpoint(title: 'Captcha')]
    #[PathParameter('config', 'Captcha config', required: false, example: 'default')]
    #[Response(200, 'Captcha generated.', examples: ['application/json' => ['success' => true, 'message' => 'Captcha generated.', 'data' => [
        'sensitive' => false,
        'key' => '',
        'img' => 'data:image/jpeg;base64,...'
    ]]])]
    public function captcha(Captcha $captcha, string $config = 'default')
    {
        return apiResponse('Captcha generated.', $captcha->create($config, true));
    }
}
