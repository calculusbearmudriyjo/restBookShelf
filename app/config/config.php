<?php

return new \Phalcon\Config(array(
    'database' => array(
        'host'        => 'localhost',
        'username'    => 'books_admin',
        'password'    => 'test',
        'dbname'      => 'books',
    ),

    'application' => array(
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'languagesDir'   => __DIR__ . '/../../app/languages/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/API-REST-PHALCON-PHP/',
    )
));
