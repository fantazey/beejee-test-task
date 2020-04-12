<?php
namespace App\Controller;

use App\Adapters\IStorageAdapter;
use App\View\IndexView;

class IndexController
{
    /** @var IStorageAdapter $storageAdapter*/
    private $storageAdapter;

    public function __construct(IStorageAdapter $storageAdapter)
    {
        $this->storageAdapter = $storageAdapter;
    }

    public function handleAction(string $action, $request)
    {
        $handler = $action . 'Action';
        if (method_exists($this, $handler)) {
            return $this->$handler($request);
        }
        throw new \Exception('Action does not exists');
    }

    private function indexAction($request)
    {
        $tasks = $this->storageAdapter->findAll('task');
        $view = new IndexView($tasks);
        $view->render();
    }
}