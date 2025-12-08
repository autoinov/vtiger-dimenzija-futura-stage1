<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/fatal_php.log');
error_reporting(E_ALL);

if (!file_exists("vendor/autoload.php")) {
    echo "Please install composer dependencies.";
    exit;
}

include_once 'config.php';
require_once 'vendor/autoload.php';
include_once 'include/Webservices/Relation.php';
include_once 'vtlib/Vtiger/Module.php';
include_once 'includes/main/WebUI.php';

$webUI = new Vtiger_WebUI();
$webUI->process(new Vtiger_Request($_REQUEST, $_REQUEST));
