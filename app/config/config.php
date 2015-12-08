<?php
use Phalcon\Config\Adapter\Ini as ConfigIni;

$config = new ConfigIni("config.ini");

return $config->merge(new \Phalcon\Config(array(
    'application' => array(
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'languagesDir'   => __DIR__ . '/../../app/languages/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/',
    )
)));

