<?php
use App\Adapters\FileStorageAdapter;
use App\Controller\IndexController;

function taskAutoloader($className) {
    $path = str_replace('App\\', 'src' . DIRECTORY_SEPARATOR, $className);
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    include $path . '.php';
}

spl_autoload_register('taskAutoloader');

$appConfig = require __DIR__ . '/../config/app.config.php';

$useStorage = $appConfig['storage']['use'];
$storageConfig = $appConfig['storage'][$useStorage];

$storageAdapter = new FileStorageAdapter($storageConfig);

$controller = new IndexController($storageAdapter);

$action = $_GET['action'];

if (!isset($action)) {
    $action = 'index';
}

$controller->handleAction($action, $_REQUEST);