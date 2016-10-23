<?php

namespace Adolfocuadros\RenqoClientACL\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    public $usuario;
    public $_id;
    public $nombre;
    public $nivel;
    public $password;
    public $renqo_token;
    public $permisos;

    function __construct(array $res)
    {
        //dd($res);
        $this->_id = $res['_id'];
        $this->nombre = $res['nombre'];
        $this->usuario = $res['usuario'];
        $this->nivel = $res['nivel'];
        $this->renqo_token = isset($res['renqo_token'])?:'';
        $configSession = config('renqo_client_acl.driver', 'cloud');
        if(isset($res['permisos']) && $configSession == 'session') {
            $this->permisos = $res['permisos'];
        }
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->usuario;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->_id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }

    public function setRenqoToken($renqo_token) {
        $this->renqo_token = $renqo_token;
    }

    public function setPermisos($permisos) {
        $this->permisos = $permisos;
    }
}