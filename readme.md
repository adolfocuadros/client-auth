# Renqo Client ACL
**Renqo Client ACL** Es una herramienta para conectarse a un servidor **RENQO ACL** el cual le permitirá fácilmente el manejo
de privilegios y roles en el sistema.

## PASOS PARA LA INSTALACIÓN

Instalar mediante Composer:
```cmd
composer require adolfocuadros/renqo-client-acl
```

### Laravel config/app.php
Agregar Como proveedor de Servicio:
```php
'providers' = [
    //Otros Proveedores de servicio
    //
    Adolfocuadros\RenqoClientACL\AclServiceProvider::class,
],
```

### Publicar Configuración
Laravel artisan:
```cmd
php artisan vendor:publish --tag=config
```

Lumen: En caso de no existir carpeta de configuración, crearla:
```
vendor/adolfocuadros/client-auth/config/renqo_client_acl.php -> config/renqo_client_acl.php
```

##Archivos a modificar

### config/renqo_client_acl.php
Modificar:
```php
//Server of Renqo ACL server
    'renqo_acl'     => 'http://url-to-renqo.com',
    'server_token'  => ''
```


### Registrar Middleware
Lumen: dentro de bootstrap/app.php
```php
...
$app->routeMiddleware([
    //Otros Middleware
    
    'acl' => Adolfocuadros\RenqoClientACL\Middleware\CheckAclMiddleware::class,
]);


//Antes de cargar las rutas
$app->configure('renqo_client_acl');
...
```

###Configuración de Autenticación (Sólo Laravel)
En caso de que desee usar el servidor de autenticación y ACL RENQO ACL

Abrir el archivo config/auth.php y modificar las siguientes lineas
```php
'guards' => [
        'web' => [
            'driver' => 'session',
            //'provider' => 'users',
            'provider' => 'renqo-acl'
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],
    
    //
    // Otras Configuraciones
    //
    
//Agregar un nuevo proveedor
'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        //RENQOACL
        'renqo-acl' => [
            'driver' => 'renqo',
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],
```

Posteriormente Registrar el Proveedor de Servicio **renqo-acl**, abrir
el archivo **app/Providers/AuthServiceProvider**.
```php
    public function boot()
    {
        $this->registerPolicies();

        \Auth::provider('renqo', function ($app, array $config) {
            return new \Adolfocuadros\RenqoClientACL\AuthUserProvider($config);
        });
    }
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