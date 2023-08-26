<?php

use Symfony\Component\Filesystem\Filesystem;

require dirname(__DIR__).'/vendor/autoload.php';

$env = true === isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : 'test';

(new Filesystem())->remove([__DIR__.'/Fixture/var/cache/'.$env]);
