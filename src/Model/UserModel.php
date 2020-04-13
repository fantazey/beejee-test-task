<?php


namespace App\Model;


class UserModel implements IModel
{
    /** @var int */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function fromArray(array $data)
    {
        if ($data['id']) {
            $this->setId($data['id']);
        }
        $this->setUsername($data['username']);
        $this->setPassword($data['password']);
    }

    public function serialize(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword()
        ];
    }
}