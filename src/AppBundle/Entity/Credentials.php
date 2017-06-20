<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 04/06/2017
 * Time: 17:28
 */

namespace AppBundle\Entity;


class Credentials
{
    protected $login;

    protected $password;

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

}