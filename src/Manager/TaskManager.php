<?php


namespace App\Manager;

use App\Adapter\IStorageAdapter;
use App\Model\TaskModel;

class TaskManager
{
    /**
     * @var IStorageAdapter $storage
     */
    private $storage;

    public function __construct(IStorageAdapter $adapter)
    {
        $this->storage = $adapter;
    }

    public function create(array $data)
    {
        list($valid, $errors) = $this->validateData($data);
        if (!$valid) {
            return 'Error. Incorrect fields: ' . implode(', ', $errors);
        }
        $task = new TaskModel();
        $task->fromArray($data);
        try {
            $this->storage->create('task', $task->serialize());
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function validateData(array $data)
    {
        $errors = [];
        $valid = true;

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $errors[] = 'email';
        }

        if (!$data['username']) {
            $valid = false;
            $errors[] = 'username';
        }

        if (!$data['content']) {
            $valid = false;
            $errors[] = 'content';
        }

        return [
            $valid,
            $errors
        ];
    }

    public function update(array $data)
    {
        /** @var TaskModel $task */
        $task = $this->findById($data['id']);
        if (!$task) {
            return 'Error. Record not found';
        }
        $data = array_merge($task->serialize(), $data);
        list($valid, $errors) = $this->validateData($data);
        if (!$valid) {
            return 'Error. Incorrect fields:' . implode(', ', $errors);
        }

        try {
            $task->fromArray($data);
            $this->save($task);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function save(TaskModel $task)
    {
        $this->storage->save('task', $task->serialize());
    }

    public function findById(int $id)
    {
        return $this->storage->find('task', $id);
    }

    public function list(int $limit, int $offset, ?string $sortField, ?string $sortOrder)
    {

        $tasks = $this->storage->findAll('task');
        if ($sortOrder) {
            usort($tasks, function ($a, $b) use ($sortField, $sortOrder) {
                $field = 'get' . ucfirst($sortField);
                if ($sortOrder === 'asc') {
                    return $a->{$field}() > $b->{$field}();
                }
                return $a->{$field}() < $b->{$field}();
            });
        }

        return array_slice($tasks, $offset, $limit);
    }

    public function count()
    {
        return $this->storage->count('task');
    }

    public function markAsDone($id)
    {
        /** @var TaskModel $task */
        $task = $this->findById($id);
        $task->markAsDone();
        $this->save($task);
    }

}