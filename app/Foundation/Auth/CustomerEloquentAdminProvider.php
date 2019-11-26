<?php namespace App\Foundation\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomerEloquentAdminProvider extends EloquentUserProvider
{
    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];
        $authPassword = $user->getAuthPassword();
        return sha1('laravel' .$authPassword['salt'] . sha1($authPassword['salt'] . sha1($plain))) == $authPassword['password'];
    }
}