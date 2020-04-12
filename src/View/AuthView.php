<?php

namespace App\View;

use App\Model\TaskModel;
use App\Util\Paginator;

class AuthView
{
    private $content;
    private $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function __destruct()
    {
        require $this->getLayoutPath('base');
    }

    public function render()
    {
        ob_clean();
        require $this->getLayoutPath('auth');
        $this->content = ob_get_clean();
    }

    private function getLayoutPath($layout) {
        return __DIR__ . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . $layout . '.php';
    }
}