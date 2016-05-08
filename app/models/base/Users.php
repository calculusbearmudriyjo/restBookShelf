<?php
namespace app\models\base;

use Phalcon\Mvc\Model;

class Users extends Model
{
    protected $id;
    protected $login;
    protected $password;
}
