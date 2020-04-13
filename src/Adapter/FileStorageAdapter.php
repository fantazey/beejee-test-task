<?php

namespace App\Adapter;

use App\Model\{IModel};

class FileStorageAdapter implements IStorageAdapter
{
    /**
     * @var string $fileName
     */
    private $fileName;

    private $TEMPLATE_CONTENT = [
        'task_last_id' => 0,
        'task' => [],
        'user_last_id' => 1,
        'user' => ['id' => 1, 'username' => 'admin', 'password' => '123']
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

    private function loadData()
    {
        $fileContent = file_get_contents($this->fileName);
        return json_decode($fileContent, true);
    }

    private function saveData(array $data)
    {
        $fileContent = json_encode($data);
        file_put_contents($this->fileName, $fileContent);
    }

    /**
     * @param string $modelName
     * @return mixed
     */
    private function loadRecords(string $modelName): ?array
    {
        $data = $this->loadData();
        if (array_key_exists($modelName, $data)) {
            return $data[$modelName];
        }
        return null;
    }

    public function create(string $modelName, array $record)
    {
        $data = $this->loadData();
        $lastIdKey = $modelName . '_last_id';
        $lastId = $data[$lastIdKey];
        $record['id'] = $lastId + 1;
        $data[$modelName][] = $record;
        $data[$lastIdKey] = $record['id'];
        $this->saveData($data);
    }

    public function save(string $modelName, array $record)
    {
        $data = $this->loadData();
        foreach ($data[$modelName] as &$item) {
            if ($item['id'] === $record['id']) {
                $item = $record;
            }
        }
        $this->saveData($data);
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
        $getter = 'get' . ucfirst($fieldName);
        foreach ($records as $record) {
            if ($record->$getter() === $value) {
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
}