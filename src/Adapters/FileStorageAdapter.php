<?php

namespace App\Adapters;

use App\Model\{IModel,TaskModel};


class FileStorageAdapter implements IStorageAdapter
{
    /**
     * @var string $fileName
     */
    private $fileName;

    /**
     * FileStorageAdapter constructor.
     * @param array $adapterConfig
     */
    public function __construct(array $adapterConfig)
    {
        $this->fileName = $adapterConfig['path'];
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
}