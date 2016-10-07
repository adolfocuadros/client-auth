<?php

namespace Adolfocuadros\RenqoClientACL;


use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/renqo_client_acl.php' => config_path('renqo_client_acl.php'),
        ]);
    }

}