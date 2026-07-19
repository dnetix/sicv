<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatesUser
{
    /**
     * Attempt to authenticate by username and plain-text password.
     *
     * Users imported from the legacy system only carry a haval128,4 hash
     * (the algorithm the CodeIgniter app used); on their first successful
     * login the password is transparently upgraded to bcrypt.
     */
    public function attempt(string $username, string $password, bool $remember = false): bool
    {
        $user = User::query()
            ->where('username', $username)
            ->where('active', true)
            ->first();

        if ($user === null) {
            return false;
        }

        if ($user->password !== null) {
            if (! Hash::check($password, $user->password)) {
                return false;
            }
        } elseif (! $this->matchesLegacyHash($user, $password)) {
            return false;
        } else {
            $user->forceFill([
                'password' => $password,
                'legacy_password_hash' => null,
            ])->save();
        }

        Auth::login($user, $remember);

        return true;
    }

    private function matchesLegacyHash(User $user, string $password): bool
    {
        return $user->legacy_password_hash !== null
            && hash_equals($user->legacy_password_hash, hash('haval128,4', $password));
    }
}
