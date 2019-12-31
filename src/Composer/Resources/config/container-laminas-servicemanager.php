<?php

use Laminas\ServiceManager\Config;
use Laminas\ServiceManager\ServiceManager;

// Load configuration
$config = require 'config.php';

// Build container
$container = new ServiceManager(new Config($config['dependencies']));

// Inject config
$container->setService('config', $config);

return $container;
