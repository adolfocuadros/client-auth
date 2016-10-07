<?php

namespace Adolfocuadros\RenqoClientACL\Microservices;


use Adolfocuadros\RenqoMicroservice\Microservice;

class RenqoACLServer extends Microservice
{
    function __construct()
    {
        $this->path_root = config('renqo_client_acl.renqo_acl');
        parent::__construct();
    }
}