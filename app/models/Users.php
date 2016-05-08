<?php

namespace app\models;

class Users extends base\Users
{
    public function checkPassword($password) {
       return $this->password == $password;
    }
}