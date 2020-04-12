<?php
namespace App\Controller;

use App\Adapter\IStorageAdapter;
use App\Manager\TaskManager;
use App\Model\TaskModel;
use App\Util\Paginator;
use App\View\IndexView;

class IndexController
{
    /** @var TaskManager $taskManager*/
    private $taskManager;

    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
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
        $res = $this->taskManager->create($request);
        if ($res !== true) {
            $errors[] = $res;
        }
        $this->renderView(1, $errors);
    }

    private function markdoneAction($request)
    {
        $errors = [];
        $id = $request['id'];
        $page = $request['page'];
        try {
            /** @var TaskModel $task */
            $this->taskManager->markAsDone($id);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
        $this->renderView($page, $errors);
    }

    private function edittaskAction($request)
    {
        $errors = [];
        $page = $request['page'];
        $res = $this->taskManager->update($request);
        if ($res !== true) {
            $errors[] = $res;
        }
        $this->renderView($page, $errors);
    }

    public function renderView($page = 1, array $errors = [])
    {
        $count = $this->taskManager->count();
        $paginator = new Paginator($count);
        $paginator->setCurrentPage($page);
        $tasks = $this->taskManager->list($paginator->getLimit(), $paginator->getOffset());
        $view = new IndexView($tasks, $errors, $paginator);
        $view->render();
    }
}