<?php

namespace App\View;

use App\Model\TaskModel;
use App\Util\Paginator;

class IndexView
{
    private $content;
    private $tasks;
    private $paginator;
    private $errors;
    private $hasUser;
    private $showSuccessBar;

    public function __construct(array $tasks, array $errors, bool $success, Paginator $paginator)
    {
        $this->tasks = $tasks;
        $this->errors = $errors;
        $this->paginator = $paginator;
        $this->hasUser = $_SESSION['authenticated'] === true;
        $this->showSuccessBar = $success;
    }

    public function __destruct()
    {
        require $this->getLayoutPath('base');
    }

    public function render()
    {
        ob_clean();
        require $this->getLayoutPath('index');
        $this->content = ob_get_clean();
    }

    private function getLayoutPath($layout) {
        return __DIR__ . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . $layout . '.php';
    }
}