<?php
namespace app\controllers;

use app\exception\HttpAccessException;
use app\models\Users;
use \Phalcon\Di as Di;

class BaseController extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        Di::getDefault()->get('response')->setContentType('application/json', 'utf-8');
    }

    protected function _auth()
    {
        $login = Di::getDefault()->get('request')->getServer('PHP_AUTH_USER');
        $password = Di::getDefault()->get('request')->getServer('PHP_AUTH_PW');

        $user = Users::findFirstByLogin($login);
//        var_dump($this->security->hash($password));exit;
        if ($user && $user->checkPassword('$2a$08$Fmelpv6WkGy9nBRyczKH9OV.WdG5dPQrB3tuivPOcPBr/AehCwLki')) {
            return true;
        }
        throw new HttpAccessException();
    }
}