<?php

namespace App\Adapters;

use App\Model\{IModel,TaskModel};


class FileStorageAdapter implements IStorageAdapter
{
    /**
     * @var string $fileName
     */
    private $fileName;

    private $TEMPLATE_CONTENT = [
        'task_last_id' => 0,
        'task' => [],
        'admin_last_id' => 0,
        'admin' => []
    ];

    /**
     * FileStorageAdapter constructor.
     * @param array $adapterConfig
     */
    public function __construct(array $adapterConfig)
    {
        $this->fileName = $adapterConfig['path'];
        $this->initStorage();
    }

    private function initStorage()
    {
        if (!file_exists($this->fileName)) {
            $content = json_encode($this->TEMPLATE_CONTENT);
            file_put_contents($this->fileName, $content);
        }
    }

    /**
     * @param string $modelName
     * @return mixed
     */
    private function loadRecords(string $modelName): ?array
    {
        $fileContent = file_get_contents($this->fileName);
        $data = json_decode($fileContent, true);
        if (array_key_exists($modelName, $data)) {
            return $data[$modelName];
        }
        return null;
    }

    private function getLastId(string $modelName): int
    {
        $fileContent = file_get_contents($this->fileName);
        $data = json_decode($fileContent, true);
        $key = $modelName . '_last_id';
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }
        return 0;
    }

    private function saveRecord(string $modelName, array $record): ?bool
    {
        $fileContent = file_get_contents($this->fileName);
        $data = json_decode($fileContent, true);
        $lastIdKey = $modelName . '_last_id';
        $data[$modelName][] = $record;
        $data[$lastIdKey] = $record['id'];
        $fileContent = json_encode($data);
        file_put_contents($this->fileName, $fileContent);
        return true;
    }

    /**
     * @param string $modelName
     * @return array
     */
    private function loadAll(string $modelName): array
    {
        $records = [];
        $model = 'App\Model\\' . ucfirst($modelName) . 'Model';
        $data = $this->loadRecords($modelName);
        foreach ($data as $item) {
            /** @var IModel $task */
            $record = new $model();
            $record->fromArray($item);
            $records[] = $record;
        }
        return $records;
    }

    /**
     * @inheritDoc
     */
    public function findAll(string $modelName): array
    {
        return $this->loadAll($modelName);
    }

    /**
     * @inheritDoc
     */
    public function findOneByField(string $modelName, string $fieldName, $value)
    {
        $records = $this->findAll($modelName);
        foreach ($records as $record) {
            if ($record[$fieldName] === $value) {
                return $record;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function find(string $modelName, int $id)
    {
        return $this->findOneByField($modelName, 'id', $id);
    }

    /**
     * @inheritDoc
     */
    public function count(string $modelName): int
    {
        return count($this->loadAll($modelName));
    }

    public function createRecord(string $modelName, array $data): ?bool
    {
        $method = 'create' . ucfirst($modelName);
        if (method_exists($this, $method)) {
            return $this->$method($data);
        }
        return null;
    }

    private function createTask(array $data)
    {
        $lastId = $this->getLastId('task');
        $newId = $lastId + 1;
        $task = new TaskModel();
        $task->fromArray($data);
        $task->setId($newId);
        return $this->saveRecord('task', $task->serialize());
    }
}