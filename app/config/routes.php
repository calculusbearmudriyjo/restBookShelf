<?php 

use Phalcon\Mvc\Router;

	$router = new Router();

    $router->removeExtraSlashes(true);

    $router->add("/", array(
        'controller' => 'index',
        'action' => 'index'
    ));

    $router->addGet("/book", array(
        'controller' => 'book',
        'action'     => 'list'
    ));

    $router->addGet('/:controller/:int/([a-zA-Z0-9_-]+)', array(
        'controller'    => 1,
        'action'        => "list",
        'id'            => 2,
        'relationship'  => 3
    ));
    $router->addGet('/:controller/:int', array(
        'controller' => 1,
        'action'     => "get",
        'id'         => 2
    ));
//    $router->addGet('/:controller', array(
//        'controller' => 1,
//        'action'     => "list"
//    ));

    $router->addPost('/:controller', array(
        'controller' => 1,
        'action'     => "save"
    ));

    $router->addPut('/:controller/:int', array(
        'controller' => 1,
        'action'     => "save",
        'id'         => 2
    ));


    $router->addDelete('/:controller/:int', array(
        'controller' => 1,
        'action'     => "delete",
        'id'         => 2
    ));


    $router->notFound(array(
        'controller' => 'error',
        'action' => 'page404'
    ));

    $router->setDefaults(array(
        'controller' => 'index',
        'action' => 'index'
    ));

return $router;
