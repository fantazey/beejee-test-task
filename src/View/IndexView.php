<?php

namespace App\View;

use App\Model\TaskModel;

class IndexView
{
    private $content;
    private $tasks;

    private function getLayoutPath($layout) {
        return __DIR__ . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . $layout . '.phtml';
    }

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function __destruct()
    {
        include $this->getLayoutPath('base');
    }

    public function render()
    {
        ob_clean();
        require $this->getLayoutPath('index');
        $this->content = ob_get_clean();
    }
}