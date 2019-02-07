<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

/*
 
//get the last-level host
$hosts = explode('.', $_SERVER['HTTP_HOST']);
$app = ($hosts[0] == 'www' ? $hosts[1] : $hosts[0]);

//figure out the environment (dev/prod/staging)
$env = 'prod';
$debug = false;
if (substr($app, -4) == '_dev')
{
    $env = 'dev';
    $debug = true;
    $app = substr($app, 0, -4);
}
else if (substr($app, -8) == '_staging')
{
    $env = 'staging';
    $app = substr($app, 0, -8);
}

//check for root sandbox/host
if ($app == 'delta' || $app == 'dave' || $app == 'wi')
{
    $app = 'admin';
}
 */

$app = 'admin';
$env = 'prod';
$debug = true;

//load the appropriate app (doesn't check for existence, so will throw error if invalid one specified)
$configuration = ProjectConfiguration::getApplicationConfiguration($app, $env, $debug);

sfContext::createInstance($configuration)->dispatch();
