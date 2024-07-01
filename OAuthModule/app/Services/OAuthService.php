<?php

namespace Modules\OAuthModule\app\Services;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OAuthService
{

    public function redirectToProvider(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleGitHubCallback(): RedirectResponse
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $user = User::where('github_id', $githubUser->id)->first();

            if (!$user) {
                $user = new User;
                $user->github_id = $githubUser->id;
                $user->username = $githubUser->name;
                $user->custom_avatar_url = $githubUser->avatar;

                try {
                    $user->save();
                } catch (Exception) {
                    $user->username = $githubUser->name . '_' . Str::random(5);
                    $user->save();
                }

                UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->body($e->getMessage())
                ->danger()
                ->send();
            return redirect()->route('auth.login');
        }
    }


    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = new User;
                $user->google_id = $googleUser->id;
                $user->username = $googleUser->name;

                try {
                    $user->save();
                } catch (Exception) {
                    $user->username = $googleUser->name . '_' . Str::random(5);
                    $user->save();
                }

                UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->body($e->getMessage())
                ->danger()
                ->send();
            return redirect()->route('auth.login');
        }
    }

    public function handleDiscordCallback(): RedirectResponse
    {
        try {
            $discordUser = Socialite::driver('discord')->user();
            $user = User::where('discord_id', $discordUser->id)->first();

            if (!$user) {
                $user = new User;
                $user->discord_id = $discordUser->id;
                $user->username = $discordUser->name;

                try {
                    $user->save();
                } catch (Exception) {
                    $user->username = $discordUser->name . '_' . Str::random(5);
                    $user->save();
                }

                UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->body($e->getMessage())
                ->danger()
                ->send();
            return redirect()->route('auth.login');
        }
    }

    public function handleAuthentikCallback()
    {
        try {
            $authentikUser = Socialite::driver('authentik')->user();
            $user = User::where('authentik_id', $authentikUser->id)->first();

            if (!$user) {
                $user = new User;
                $user->authentik_id = $authentikUser->id;
                $user->username = $authentikUser->name;

                try {
                    $user->save();
                } catch (Exception) {
                    $user->username = $authentikUser->name . '_' . Str::random(5);
                    $user->save();
                }

                UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->body($e->getMessage())
                ->danger()
                ->send();
            return redirect()->route('auth.login');
        }

    }

    public function handleCustomCallback()
    {
        try {
            $customUser = Socialite::driver('custom')->user();
            $user = User::where('custom_id', $customUser->id)->first();

            if (!$user) {
                $user = new User;
                $user->custom_id = $customUser->id;
                if ($customUser->name !== null) {
                    $user->username = $customUser->name;
                } else if ($customUser->username !== null) {
                    $user->username = $customUser->username;
                } else if ($customUser->email !== null) {
                    $user->username = $customUser->email;
                } else {
                    $user->username = $customUser->id . '_' . Str::random(5);
                }

                try {
                    $user->save();
                } catch (Exception) {
                    if ($customUser->name !== null) {
                        $user->username = $customUser->name . '_' . Str::random(5);
                    } else if ($customUser->username !== null) {
                        $user->username = $customUser->username . '_' . Str::random(5);
                    } else if ($customUser->email !== null) {
                        $user->username = $customUser->email . '_' . Str::random(5);
                    } else {
                        $user->username = $customUser->id . '_' . Str::random(5);
                    }
                    $user->save();
                }

                UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->body($e->getMessage())
                ->danger()
                ->send();
            return redirect()->route('auth.login');
        }

    }

}
