<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewUserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleController extends Controller
{
    /**
     * Redirige l'utilisateur vers la page de consentement Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Traite le retour de Google et connecte/crée l'utilisateur.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException|\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'La connexion avec Google a échoué. Veuillez réessayer.');
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        $isNewUser = false;

        if (!$user) {
            $isNewUser = true;

            $nameParts = preg_split('/\s+/', trim($googleUser->getName() ?: $googleUser->getNickname() ?: 'Utilisateur Google'), 2);
            $firstName = $nameParts[0] ?? 'Utilisateur';
            $lastName = $nameParts[1] ?? 'Google';

            $user = User::create([
                'name' => $googleUser->getName() ?: trim($firstName . ' ' . $lastName),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => null,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'avatar_url' => $googleUser->getAvatar(),
                'role' => 'member',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            Mail::to('admin@e-school237.com')->send(new NewUserRegistered($user));
        } elseif (!$user->google_id) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }

        Auth::login($user, remember: true);

        return $isNewUser
            ? redirect()->route('user.profile')
            : redirect()->intended(route('home'));
    }
}
