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
        try {
            $client = new Client([
                'base_uri' =>  config('client_auth.api'),
                'timeout'  => 2.0,
                'headers'  => [
                    'Auth-Token' => '57ef4c96a2324427b4004f84'
                ]
            ]);

            $response = $client->request('POST', 'session',[
                'form_params'    =>  ['permission'=>$permission]
            ]);

            dd($response->getBody()->getContents());
        } catch (\Exception $e) {
            if($e->getCode() == 401) {
                return response()->json(['error' => 'Acceso denegado'], 401);
            } else {
                return response()->json(['error' => 'ha ocurrido un error: '.$e->getCode()], $e->getCode());
            }
        }

        //dd($response);
        return $next($request);
    }

    private function checkLocalSession($request)
    {
        
        dd($request->session()->has('users'));
    }
}