<?php
namespace Adolfocuadros\RenqoClientACL\Middleware;

use Adolfocuadros\RenqoClientACL\Exceptions\ConfigException;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

Class CheckAclMiddleware
{

    public function handle(Request $request, Closure $next, $permission = null)
    {
        if(!$request->hasHeader('Auth-Token')) {
            return response()->json(['error' => 'Acceso denegado'], 401);
        }
        if(empty(config('renqo_client_acl.api_auth'))) {
            throw new ConfigException('Hay un problema con la configuración api_auth');
        }
        try {
            $client = new Client([
                'base_uri' =>  config('renqo_client_acl.api_auth'),
                'timeout'  => 2.0,
                'headers'  => [
                    'Auth-Token' => $request->header('Auth-Token')
                ]
            ]);

            $client->request('POST', 'acl',[
                'form_params'    =>  ['permission'=>$permission]
            ]);
        } catch (\Exception $e) {
            if($e->getCode() == 401) {
                return response()->json(['error' => 'Acceso denegado'], 401);
            } elseif($e->getCode() == 0) {
                return response()->json(['error' => 'Ha ocurrido un error con el servicio, inténtelo luego.'], 500);
            } else {
                return response()->json(['error' => 'ha ocurrido un error: '.$e->getCode()], $e->getCode());
            }
        }
        return $next($request);
    }
}