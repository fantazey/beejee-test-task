<?php

namespace App\Adapters;

interface IStorageAdapter
{
    /**
     * @param string $modelName
     * @return array
     */
    public function findAll(string $modelName): array;

    /**
     * @param string $modelName
     * @param string $fieldName
     * @param $value
     * @return mixed
     */
    public function findOneByField(string $modelName, string $fieldName, $value);

    /**
     * @param string $modelName
     * @param int $id
     * @return mixed
     */
    public function find(string $modelName, int $id);

    /**
     * @param string $modelName
     * @return int
     */
    public function count(string $modelName): int;

    /**
     * @param string $modelName
     * @param array $data
     * @return mixed
     */
    public function createRecord(string $modelName, array $data);
}