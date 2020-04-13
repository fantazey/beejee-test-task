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
        $this->renderView(1, $errors, $res);
    }

    private function markdoneAction($request)
    {
        $errors = [];
        $id = $request['id'];
        $page = $request['page'];
        $success = false;
        try {
            /** @var TaskModel $task */
            $this->taskManager->markAsDone($id);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (count($errors) === 0) {
            $success = true;
        }
        $this->renderView($page, $errors, $success);
    }

    private function edittaskAction($request)
    {
        $errors = [];
        $page = $request['page'];
        $res = $this->taskManager->update($request);
        if ($res !== true) {
            $errors[] = $res;
        }
        $this->renderView($page, $errors, $res);
    }

    private function renderView($page = 1, array $errors = [], bool $actionSuccess = false)
    {
        $paginator = $this->initPaginator($page);
        $tasks = $this->taskManager->list(
            $paginator->getLimit(),
            $paginator->getOffset(),
            $paginator->getSortField(),
            $paginator->getSortOrder()
        );
        $view = new IndexView($tasks, $errors, $actionSuccess, $paginator);
        $view->render();
    }

    private function initPaginator($page): Paginator
    {
        $count = $this->taskManager->count();
        $paginator = new Paginator($count);
        $paginator->setCurrentPage($page);
        if (isset($_REQUEST['sortOrder'])) {
            $paginator->setSortOrder($_REQUEST['sortOrder']);
        }
        if (isset($_REQUEST['sortField'])) {
            $paginator->setSortField($_REQUEST['sortField']);
        }
        return $paginator;
    }
}