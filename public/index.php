<?php
ob_end_clean();
if (preg_match('/\.(?:png|jpg|jpeg|gif|ico)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

use App\Adapter\FileStorageAdapter;
use App\Manager\TaskManager;
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

$taskManager = new TaskManager($storageAdapter);
$controller = new IndexController($taskManager);

$action = 'index';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}
$controller->handleAction($action, $_REQUEST);