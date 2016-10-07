<?php

namespace Adolfocuadros\RenqoClientACL\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    public $usuario;
    public $id;
    public $nombre;
    public $nivel;
    public $password;
    public $renqo_token;

    function __construct($res)
    {
        $this->id = $res->_id;
        $this->nombre = $res->nombre;
        $this->usuario = $res->usuario;
        $this->nivel = $res->nivel;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->id;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->usuario;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return 'secreto';
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
}