#Renqo Client ACL
**Renqo Client ACL** Es una herramienta para conectarse a un servidor **RENQO ACL** el cual le permitirá fácilmente el manejo
de privilegios y roles en el sistema.

## PASOS PARA LA INSTALACIÓN

Probado en Lumen 5.3

### Lumen: Copiar vendor/adolfocuadros/client-auth/config/renqo_client_acl.php a config/renqo_client_acl.php

##Archivos a modificar

### config/renqo_client_acl.php
Modificar:
```php
    'api_auth'      =>  'http//renqoserver.com',
    'server_token'  => ''
```


### bootstrap/app.php
Habilitar este middleware:
```php
...
$app->routeMiddleware([
    'acl' => Adolfocuadros\RenqoClientACL\Middleware\CheckAclMiddleware::class,
]);
```

Antes de cargar las rutas:
```php
...
$app->configure('renqo_client_acl');
...
```

### ¿Cómo Usarlo?
#### app/Http/routes.php
```php
$app->post('usuarios', [
    'middleware' => 'acl:usuarios.store',
    'uses' => 'UsuarioController@store'
]);
```
Notar que **acl:usuarios.store** validará los permisos
"usuarios.store" conjuntamente con la session, la respuesta será HTTP 200
si todo es correcto o 40x en caso haya problemas.

Respuestas
En caso de Error HTTP 401
```json
{"error":"No tiene los permisos suficientes."}
```
En caso de Éxito HTTP 200
```json
{"status":true}
```