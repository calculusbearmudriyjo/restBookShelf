<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    array(
        "app\\controllers"       => "../app/controllers/",
        "app\\models"            => "../app/models/",
        "app\\models\\base"      => "../app/models/base/",
        "app\\library"           => "../app/library/"
    )
)->register();

