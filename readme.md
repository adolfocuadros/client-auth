# PASOS PARA LA INSTALACIÓN

Probado en Lumen 5.3

## bootstrap/app.php
Habilitar este middleware:
```
$app->routeMiddleware([
    'check_session' => Adolfocuadros\ClientAuth\Middleware\CheckSessionMiddleware::class,
]);
```

Posteriormente usarlo en tu ruta, ejemplo:
```
$app->post('usuarios', [
    'middleware' => 'check_session',
    'uses' => 'UsuarioController@store'
]);
```