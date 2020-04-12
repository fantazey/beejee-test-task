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
        /** @var UserModel $user */
        $user = $this->storage->findOneByField('user', 'username', $username);
        if ($password === $user->getPassword()) {
            session_start();
            $_SESSION['username'] = $user->getUsername();
            $sessionId = session_id();
            $user->setSessionId($sessionId);

        }
    }
}