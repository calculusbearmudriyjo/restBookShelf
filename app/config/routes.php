<?php 

use Phalcon\Mvc\Router;

	$router = new Router();

    $router->removeExtraSlashes(true);

/* BOOK */
    $router->addGet("/book", array(
        'controller' => 'book',
        'action'     => 'list'
    ));

    $router->addGet("/book/:int", array(
        'controller' => 'book',
        'action'     => 'book',
        'id'         => 1
    ));

    $router->addPost('/book', array(
        'controller' => 'book',
        'action'     => "save"
    ));

    $router->addPut('/book/:int', array(
        'controller' => 'book',
        'action'     => "update",
        'id'         => 1
    ));

    $router->addDelete('/book/:int', array(
        'controller' => 'book',
        'action'     => "delete",
        'id'         => 1
    ));
/* BOOK */

/* LANGUAGE */
    $router->addGet("/language", array(
        'controller' => 'language',
        'action'     => 'list'
    ));

    $router->addGet("/language/:int", array(
        'controller' => 'language',
        'action'     => 'language',
        'id'         => 1
    ));

    $router->addPost('/language', array(
        'controller' => 'language',
        'action'     => "save"
    ));

    $router->addPut('/language/:int', array(
        'controller' => 'language',
        'action'     => "update",
        'id'         => 1
    ));

    $router->addDelete('/language/:int', array(
        'controller' => 'language',
        'action'     => "delete",
        'id'         => 1
    ));
/* LANGUAGE */

/* COMPLEXITY */
    $router->addGet("/complexity", array(
        'controller' => 'complexity',
        'action'     => 'list'
    ));

    $router->addGet("/complexity/:int", array(
        'controller' => 'complexity',
        'action'     => 'complexity',
        'id'         => 1
    ));

    $router->addPost('/complexity', array(
        'controller' => 'complexity',
        'action'     => "save"
    ));

    $router->addPut('/complexity/:int', array(
        'controller' => 'complexity',
        'action'     => "update",
        'id'         => 1
    ));

    $router->addDelete('/complexity/:int', array(
        'controller' => 'complexity',
        'action'     => "delete",
        'id'         => 1
    ));
/* COMPLEXITY */

/* CATEGORY */
    $router->addGet("/category", array(
        'controller' => 'category',
        'action'     => 'list'
    ));

    $router->addGet("/category/:int", array(
        'controller' => 'category',
        'action'     => 'category',
        'id'         => 1
    ));

    $router->addPost('/category', array(
        'controller' => 'category',
        'action'     => "save"
    ));

    $router->addPut('/category/:int', array(
        'controller' => 'category',
        'action'     => "update",
        'id'         => 1
    ));

    $router->addDelete('/category/:int', array(
        'controller' => 'category',
        'action'     => "delete",
        'id'         => 1
    ));
/* CATEGORY */

/* CATEGORY BOOKS*/
    $router->addGet("/category-books", array(
        'controller' => 'category-books',
        'action'     => 'list'
    ));

    $router->addGet("/category-books/:int", array(
        'controller' => 'category-books',
        'action'     => 'category',
        'id'         => 1
    ));

    $router->addPost('/category-books', array(
        'controller' => 'category-books',
        'action'     => "save"
    ));

    $router->addDelete('/category-books/:int', array(
        'controller' => 'category-books',
        'action'     => "delete",
        'id'         => 1
    ));
/* CATEGORY BOOKS */

/* RATING */
    $router->addGet("/rating", array(
        'controller' => 'rating',
        'action'     => 'list'
    ));

    $router->addGet("/rating/:int", array(
        'controller' => 'rating',
        'action'     => 'category',
        'id'         => 1
    ));

    $router->addPost('/rating', array(
        'controller' => 'rating',
        'action'     => "save"
    ));

    $router->addDelete('/rating/:int', array(
        'controller' => 'rating',
        'action'     => "delete",
        'id'         => 1
    ));
/* RATING */

return $router;
