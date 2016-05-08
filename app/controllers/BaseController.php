<?php
namespace app\controllers;

use app\exception\HttpAccessException;
use app\models\Users;
use \Phalcon\Di as Di;
use app\library\HttpCode;
use app\models\User;

class BaseController extends \Phalcon\Mvc\Controller
{
    /** @var \Phalcon\Http\Request $_request  */
    protected $_request = null;
    /** @var \Phalcon\Http\Response $_request  */
    protected $_response = null;
    /** @var HttpCode $_httpCode  */
    protected $_httpCode = null;

    public function initialize()
    {
        $this->_request = Di::getDefault()->get('request');
        $this->_response = Di::getDefault()->get('response');
        $this->_httpCode = Di::getDefault()->get('httpCode');

        Di::getDefault()->get('response')->setContentType('application/json', 'utf-8');
        $this->_response->setStatusCode($this->_httpCode->ok());
    }

    protected function _auth()
    {
        $login = Di::getDefault()->get('request')->getServer('PHP_AUTH_USER');
        $password = Di::getDefault()->get('request')->getServer('PHP_AUTH_PW');

        /** @var Users $user */
        $user = Users::findFirstByLogin($login);
        if ($user && $user->checkPassword($this->security->hash($password))) {
            return true;
        }
        throw new HttpAccessException();
    }
}