<?php
namespace App\Controller;

use App\Adapters\IStorageAdapter;
use App\Util\Paginator;
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
        $count = $this->storageAdapter->count('task');
        $paginator = new Paginator($count);
        $tasks = $this->storageAdapter->findAll('task');
        if (isset($_REQUEST['page'])) {
            $paginator->setCurrentPage((int)$_REQUEST['page']);
        }
        $pageTasks = array_slice($tasks, $paginator->getOffset(), $paginator->getLimit());
        $view = new IndexView($pageTasks, [], $paginator);
        $view->render();
    }
}