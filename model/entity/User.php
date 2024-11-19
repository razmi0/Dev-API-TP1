<?php

namespace Model\Entity;

/**
 * Class User
 * @property int $user_id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $created_at
 * @property string $updated_at
 * 
 * @method int getUserId()
 * @method string getUsername()
 * @method string getEmail()
 * @method string getPasswordHash()
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 * @method setUserId(int $user_id)
 * @method setUsername(string $username)
 * @method setEmail(string $email)
 * @method setPasswordHash(string $password_hash)
 * @method setCreatedAt(string $created_at)
 * @method setUpdatedAt(string $updated_at)
 * @method toArray()
 */
class User
{
    public function __construct(
        private ?int $user_id = null,
        private ?string $username = null,
        private ?string $email = null,
        private ?string $password_hash = null,
        private ?string $created_at = null,
        private ?string $updated_at = null
    ) {}

    public static function make(array $data)
    {
        return new User(
            $data["user_id"] ?? null,
            $data["username"] ?? null,
            $data["email"] ?? null,
            $data["password_hash"] ?? null,
            $data["created_at"] ?? null,
            $data["updated_at"] ?? null
        );
    }

    /**
     * Getters
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * Setters
     */
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt(string $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public static function toArray(User $user): array
    {
        return [
            'user_id' => $user->getUserId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt()
        ];
    }
}
