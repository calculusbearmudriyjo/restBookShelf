<?php

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Users extends \Phalcon\Mvc\Model
{
    public $id;
    public $login;
    public $password;
}
