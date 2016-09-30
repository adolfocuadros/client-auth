<?php
namespace Adolfocuadros\ClientAuth\Middleware;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

Class CheckSessionMiddleware
{
    private $check_url = "http://localhost/ccpp-software/mc_login/public/";

    public function handle($request, Closure $next)
    {
        try {
            $client = new Client([
                'base_uri' => $this->check_url,
                // You can set any number of default request options.
                'timeout'  => 2.0,
            ]);

            $response = $client->request('POST', 'session');
        } catch (ClientException $e) {
            if($e->getCode() == 401) {
                return response()->json(['error' => 'Acceso denegado'], 401);
            }
        }


        return $next($request);
    }
}