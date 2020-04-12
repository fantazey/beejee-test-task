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
        $page = 1;
        if (isset($_REQUEST['page'])) {
            $page = (int)$_REQUEST['page'];
        }
        $this->renderView($page, []);
    }

    private function createtaskAction($request)
    {
        $errors = [];
        try {
            $this->storageAdapter->createRecord('task', $request);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        return $this->renderView(1, $errors);
    }

    public function renderView($page = 1, array $errors = [])
    {
        $count = $this->storageAdapter->count('task');
        $paginator = new Paginator($count);
        $tasks = $this->storageAdapter->findAll('task');
        $paginator->setCurrentPage($page);
        $pageTasks = array_slice($tasks, $paginator->getOffset(), $paginator->getLimit());
        $view = new IndexView($pageTasks, $errors, $paginator);
        $view->render();
    }
}