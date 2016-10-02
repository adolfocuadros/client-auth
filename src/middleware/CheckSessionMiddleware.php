<?php
namespace Adolfocuadros\ClientAuth\Middleware;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

Class CheckSessionMiddleware
{

    public function handle(Request $request, Closure $next, $permission = null)
    {
        if(!isset(getallheaders()['Auth-Token'])) {
            return response()->json(['error' => 'Acceso denegado'], 401);
        }
        try {
            $client = new Client([
                'base_uri' =>  config('client_auth.api'),
                'timeout'  => 2.0,
                'headers'  => [
                    'Auth-Token' => getallheaders()['Auth-Token']
                ]
            ]);

            $client->request('POST', 'session',[
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