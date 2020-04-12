<?php


namespace App\Model;


interface IModel
{
    /**
     * @param array $data
     * @return mixed
     */
    public function fromArray(array $data);

    /**
     * @return array
     */
    public function serialize(): array;
}