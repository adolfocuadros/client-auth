<?php

namespace Adolfocuadros\RenqoClientACL;


use Adolfocuadros\RenqoClientACL\Exceptions\ConfigException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ACL
{
    public static function can(Request $request, $permission) {
        if(!$request->hasHeader('Auth-Token')) {
            return false;
        }
        if(empty(config('renqo_client_acl.renqo_acl'))) {
            throw new ConfigException('Hay un problema con la configuración api_auth');
        }
        try {
            $client = new Client([
                'base_uri' =>  config('renqo_client_acl.renqo_acl'),
                'timeout'  => 15.0,
                'headers'  => [
                    'Auth-Token' => $request->header('Auth-Token')
                ]
            ]);

            $client->request('POST', 'acl',[
                'form_params'    =>  ['permission'=>$permission]
            ]);
        } catch (\Exception $e) {
            return false;;
        }
        return true;
    }
}