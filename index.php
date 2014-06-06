<?php
// tom|hetmer|cz 2013

// NETTE
require './lib/nette.min.php';
require './lib/Kdyby/BootstrapFormRenderer/BootstrapRenderer.php';
require './lib/i18n_detect.php';


$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/tmp');

// Debug bar
//$configurator->setDebugMode(array(
//	"83.208.218.52"
//));
$configurator->enableDebugger(__DIR__ . "/tmp");

// SESSION
$container 	= $configurator->createContainer();
$session	= $container->session;
$user   	= $container->user;
$s      	= $session->getSection('rt');

// LIBS
require './db.php';
require './func.php';

require './index.security.php';
require './index.i18n.php';
require './index.routing.php';
?>
