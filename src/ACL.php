<?php

namespace Adolfocuadros\RenqoClientACL;


use Adolfocuadros\RenqoClientACL\Exceptions\ConfigException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ACL
{
    public static function can() {
        $args = func_get_args();
        foreach ($args as $arg) {
            if($arg instanceof Request) {
                $request = $arg;
            }
            if(is_string($arg)) {
                $permission = $arg;
            }
        }

        if(!isset($permission)) {
            return false;
        }

        if(!isset($request)) {
            $request = request();
        }

        if(empty(config('renqo_client_acl.renqo_acl'))) {
            throw new ConfigException('Hay un problema con la configuración api_auth');
        }
        $configSession = config('renqo_client_acl.driver','cloud');
        if($configSession == 'session') {

            $usuario_permisos = \Auth::user()->permisos;

            if(permission($usuario_permisos, $permission)) {
                return true;
            }
        } else {

            if(!$request->hasHeader('Auth-Token')) {
                return false;
            }

            try {
                $client = new Client([
                    'base_uri' =>  config('renqo_client_acl.renqo_acl'),
                    'timeout'  => 20.0,
                    'headers'  => [
                        'Auth-Token' => $request->header('Auth-Token')
                    ]
                ]);

                $client->request('POST', 'acl',[
                    'form_params'    =>  ['permission'=>$permission]
                ]);
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }
}