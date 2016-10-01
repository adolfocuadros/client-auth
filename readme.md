# PASOS PARA LA INSTALACIÓN

Probado en Lumen 5.3

### Lumen: Copiar vendor/adolfocuadros/client-auth/config/client_auth.php a config/client_auth.php

### .env
Agregar
```
AUTH_API=http://localhost/direcciondelaapi/
```

### bootstrap/app.php
Habilitar este middleware:
```
$app->routeMiddleware([
    'check_session' => Adolfocuadros\ClientAuth\Middleware\CheckSessionMiddleware::class,
]);
```

Antes de cargar las rutas:
```
$app->configure('client_auth');
```

### ¿Cómo Usarlo?
#### app/Http/routes.php
```
$app->post('usuarios', [
    'middleware' => 'check_session',
    'uses' => 'UsuarioController@store'
]);
```