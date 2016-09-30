<?php
namespace Adolfocuadros\ClientAuth\Middleware;

use Closure;

Class CheckSession
{
    public function handle($request, Closure $next)
    {
        dd('pasa por middleware');

        return $next($request);
    }
}