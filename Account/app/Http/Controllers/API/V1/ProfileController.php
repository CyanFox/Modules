<?php

namespace Modules\Account\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Facades\UserSettings;

#[Group('Account Profile')]
class ProfileController extends Controller
{

    #[Endpoint(title: 'Change Avatar')]
    #[Response(200, 'Avatar updated.', examples: ['application/json' => ['success' => true, 'message' => 'Avatar Updated.', 'data' => [
        'avatar_url' => ''
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function changeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6144',
            'custom_avatar_url' => 'nullable|url',
        ]);

        if ($request->custom_avatar_url) {
            $request->user()->clearMediaCollection('avatar');

            UserSettings::set($request->user()->id, 'core.custom_avatar_url', $request->custom_avatar_url, true);
        } else if ($request->avatar) {
            UserSettings::delete($request->user()->id, 'core.custom_avatar_url');

            $request->user()->clearMediaCollection('avatar');
            $request->user()->addMedia($request->avatar)->toMediaCollection('avatar');
        }

        return apiResponse('Avatar updated.', ['avatar_url' => $request->user()->getAvatar()]);
    }

    #[Endpoint(title: 'Reset Avatar')]
    #[Response(200, 'Avatar reset.', examples: ['application/json' => ['success' => true, 'message' => 'Avatar reset.', 'data' => [
        'avatar_url' => ''
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function resetAvatar(Request $request)
    {
        UserSettings::delete($request->user()->id, 'core.custom_avatar_url');
        $request->user()->clearMediaCollection('avatar');

        return apiResponse('Avatar reset.', ['avatar_url' => $request->user()->getAvatar()]);
    }

    #[Endpoint(title: 'Update Profile')]
    #[Response(200, 'Profile updated.', examples: ['application/json' => ['success' => true, 'message' => 'Profile updated.', 'data' => [
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
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $request->user()->id,
            'email' => 'nullable|string|email|max:255',
            'theme' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
        ]);

        $request->user()->update($validated);

        return apiResponse('Profile updated.', $request->user());
    }

    #[Endpoint(title: 'Update Password')]
    #[Response(200, 'Password updated.', examples: ['application/json' => ['success' => true, 'message' => 'Password updated.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|current_password',
            'new_password' => ['required', 'string', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $request->new_password,
        ]);

        return apiResponse('Password updated.');
    }

    #[Endpoint(title: 'Delete Account')]
    #[Response(200, 'Account deleted.', examples: ['application/json' => ['success' => true, 'message' => 'Account deleted.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteAccount(Request $request)
    {
        $request->user()->delete();

        return apiResponse('Account deleted.');
    }
}
