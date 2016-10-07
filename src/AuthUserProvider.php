<?php

namespace Adolfocuadros\RenqoClientACL;

use Adolfocuadros\RenqoClientACL\Auth\User;
use Adolfocuadros\RenqoClientACL\Microservices\RenqoACLServer;
use Adolfocuadros\RenqoMicroservice\Exceptions\MicroserviceRequestException;
use Adolfocuadros\RenqoMicroservice\Factory\RequestFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AuthUserProvider implements UserProvider
{
    private $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    public function retrieveById($identifier)
    {
        return 'es esto';
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
        $user->setRenqoToken($response->toArray()->token);
        //dd($response->getContent());
        return true;
    }
}