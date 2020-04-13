<?php


namespace App\Controller;

use App\Manager\UserManager;
use App\View\AuthView;

class AuthController
{
    /** @var UserManager $userManager*/
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function handleAction(string $action, $request)
    {
        $handler = $action . 'Action';
        if (method_exists($this, $handler)) {
            return $this->$handler($request);
        }
        throw new \Exception('Action does not exists');
    }

    public function loginAction($request)
    {
        $view = new AuthView([]);
        $view->render();
    }

    public function authAction($request)
    {
        $res = $this->userManager->login($request['username'], $request['password']);
        if ($res === true) {
            header('Location: /', true, 302);
            exit();
        }
        $errors[] = $res;
        $view = new AuthView($errors);
        $view->render();
    }

    public function logoutAction($request)
    {
        $this->userManager->logout();
        $view = new AuthView([]);
        $view->render();
    }
}