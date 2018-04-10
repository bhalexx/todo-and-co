<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;


$kernel = new AppKernel('test', true); // create a "test" kernel
$kernel->boot();

$application = new Application($kernel);
$application->setAutoExit(false);

// Load fixtures
$options = array('command' => 'todolist:fixtures:load', '--environment'=> 'test');
$application->run(new ArrayInput($options));