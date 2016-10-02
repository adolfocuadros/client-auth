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
```php
$app->routeMiddleware([
    'check_session' => Adolfocuadros\ClientAuth\Middleware\CheckSessionMiddleware::class,
]);
```


Copiar vendor/adolfocuadros/client-auth/config/client_auth.php a config/client_auth.php
Posteriormente insertar antes de la carga de rutas:
```php
$app->configure('client_auth');
```

### ¿Cómo Usarlo?
#### app/Http/routes.php
```php
$app->post('usuarios', [
    'middleware' => 'check_session:usuarios.store',
    'uses' => 'UsuarioController@store'
]);
```
Notar que **check_session:usuarios.store** validará los permisos 
"usuarios.store" conjuntamente con la session, la respuesta será HTTP 200
si todo es correcto o 40x en caso haya problemas.

En caso de Error HTTP 401
```json
{"error":"No tiene los permisos suficientes."}
```
En caso de Éxito HTTP 200
```json
{"status":true}
```