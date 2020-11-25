<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$app = 'touch';
$env = 'prod';
$debug = true;

//load the appropriate app (doesn't check for existence, so will throw error if invalid one specified)
$configuration = ProjectConfiguration::getApplicationConfiguration($app, $env, $debug);

sfContext::createInstance($configuration)->dispatch();
