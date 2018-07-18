<?php

namespace Adolfocuadros\RenqoClientACL;

use Adolfocuadros\RenqoClientACL\Auth\User;
use Adolfocuadros\RenqoClientACL\Microservices\RenqoACLServer;
use Adolfocuadros\RenqoMicroservice\Exceptions\MicroserviceRequestException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class AuthUserProvider implements UserProvider
{
    private $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    public function retrieveById($identifier)
    {
        //dd(session()->get('user_raw'));
        if(!session()->has('user_raw')) {
            return null;
        }
        return new User(session('user_raw'));
    }

    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    public function retrieveByCredentials(array $credentials)
    {
        try {
            $response = (new RenqoACLServer)->post('usuarios/search-user', $credentials);
        } catch(MicroserviceRequestException $e) {
            return null;
        }
        return new User($response->toArray());
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        try {
            $response = (new RenqoACLServer)->post('login', $credentials);
        } catch(MicroserviceRequestException $e) {
            //dd($e->getRealMessage());
            return false;
        }
        $rsp = $response->toObject();
        $token = $rsp->token;
        $user->setRenqoToken($token);
        $user->setPermisos($rsp->usuario->permisos);
        session(['renqo_token' => $token]);
        session(['user_raw' => get_object_vars($user)]);
        return true;
    }
}