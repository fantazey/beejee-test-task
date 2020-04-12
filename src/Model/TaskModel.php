<?php

namespace App\Model;

class TaskModel implements IModel
{

    const STATE_ACTIVE = 0,
        STATE_DONE = 1;

    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var int $state
     */
    private $state;

    /**
     * @var string $content
     */
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
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
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param null|int $state
     */
    public function setState(?int $state)
    {
        $this->state = $state ?? self::STATE_ACTIVE;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function markAsDone()
    {
        $this->state = self::STATE_DONE;
    }

    public function fromArray(array $data)
    {
        if ($data['id']) {
            $this->setId($data['id']);
        }
        $this->setUsername($data['username']);
        $this->setEmail($data['email']);
        $this->setContent($data['content']);
        $this->setState($data['state']);
    }

    public function serialize(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'content' => $this->getContent(),
            'state' => $this->getState()
        ];
    }

    public function getValuesForTemplate(): array
    {
        return [
            'id' => $this->getId(),
            'username' => htmlspecialchars($this->getUsername(), ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($this->getEmail(), ENT_QUOTES, 'UTF-8'),
            'content' => htmlspecialchars($this->getContent(), ENT_QUOTES, 'UTF-8'),
            'isActive' => $this->getState() === self::STATE_ACTIVE,
        ];
    }
}