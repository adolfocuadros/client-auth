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
        $configSession = config('renqo_client_acl.driver','cloud');
        if($configSession == 'session') {

            $usuario_permisos = \Auth::user()->permisos;

            if(!is_array($usuario_permisos) && $usuario_permisos == '*') {
                return $next($request);
            }

            $app_permisos = explode('.',$permission);
            $niveles = count($app_permisos);

            foreach ($usuario_permisos as $permiso) {
                for($i = 0; $i < $niveles; $i++) {
                    if($permiso == $app_permisos[$i].'.*' || $permiso == $app_permisos[$i]) {
                        return $next($request);
                    }
                }
            }

            abort(401, 'acceso denegado');
        }
        
        if(!$request->hasHeader('Auth-Token')) {
            abort(401, 'acceso denegado');
        }
        if(empty(config('renqo_client_acl.renqo_acl'))) {
            throw new ConfigException('Hay un problema con la configuración api_auth');
        }
        try {
            $client = new Client([
                'base_uri' =>  config('renqo_client_acl.renqo_acl'),
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
                abort(401, 'acceso denegado');
            } elseif($e->getCode() == 0) {
                abort(500, 'Error en el sistema');
            } else {
                abort($e->getCode(), 'Ha ocurrido un error');
            }
        }
        return $next($request);
    }
}