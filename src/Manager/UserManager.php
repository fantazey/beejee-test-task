<?php


namespace App\Manager;

use App\Adapter\IStorageAdapter;
use App\Model\TaskModel;
use App\Model\UserModel;

class UserManager
{
    /**
     * @var IStorageAdapter $storage
     */
    private $storage;

    public function __construct(IStorageAdapter $adapter)
    {
        $this->storage = $adapter;
    }

    public function login(string $username, string $password)
    {
        if (!$username || !$password) {
            return 'Auth error. Empty credentials';
        }
        /** @var UserModel $user */
        $user = $this->storage->findOneByField('user', 'username', $username);
        if (!$user) {
            return 'Auth error. Wrong user';
        }
        if ($password !== $user->getPassword()) {
            return 'Auth error. Wrong password';
        }
        session_start();
        $_SESSION['authenticated'] = true;
        return true;
    }

    public function logout()
    {
        $_SESSION = [];
    }
}