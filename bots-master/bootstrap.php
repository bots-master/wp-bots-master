<?php

require __DIR__.'/helpers.php';

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/vendor/autoload.php';

$loader->addPsr4('WebXID\\BotsMaster\\', __DIR__ . '/app/', true);
