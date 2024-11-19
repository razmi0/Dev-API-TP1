CREATE TABLE `T_TOKEN` (
`token_id` int(11) NOT NULL AUTO_INCREMENT,
`token` TEXT NOT NULL UNIQUE,
`token_hash` TEXT NOT NULL UNIQUE,
`user_id` int(11) NOT NULL,
`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`token_id`),
FOREIGN KEY (`user_id`) REFERENCES `T_USER`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





<?php



namespace Model\Entity;

/**
 * Class Token
 * @property string $token_id
 * @property string $token
 * @property string $token_hash
 * @property string $user_id
 * @property string $created_at
 * @property string $updated_at
 * 
 * @method string getTokenId()
 * @method string getToken()
 * @method string getTokenHash()
 * @method string getUserId()
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 * @method setTokenId(string $token_id)
 * @method setToken(string $token)
 * @method setTokenHash(string $token_hash)
 * @method setUserId(string $user_id)
 * @method setCreatedAt(string $created_at)
 * @method setUpdatedAt(string $updated_at)
 * @method toArray()
 */
class Token
{
    public function __construct(
        private ?string $token_id = null,
        private ?string $token = null,
        private ?string $token_hash = null,
        private ?string $user_id = null,
        private ?string $created_at = null,
        private ?string $updated_at = null
    ) {}


    public static function make(array $data)
    {
        return new Token(
            $data["token_id"] ?? null,
            $data["token"] ?? null,
            $data["token_hash"] ?? null,
            $data["user_id"] ?? null,
            $data["created_at"] ?? null,
            $data["updated_at"] ?? null
        );
    }

    /**
     * Getters
     */
    public function getTokenId(): ?string
    {
        return $this->token_id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getTokenHash(): ?string
    {
        return $this->token_hash;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
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

    public function setTokenId(string $token_id): self
    {
        $this->token_id = $token_id;
        return $this;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function setTokenHash(string $token_hash): self
    {
        $this->token_hash = $token_hash;
        return $this;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;
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

    public static function toArray(Token $token): array
    {
        return [
            'token_id' => $token->getTokenId(),
            'token' => $token->getToken(),
            'token_hash' => $token->getTokenHash(),
            'user_id' => $token->getUserId(),
            'created_at' => $token->getCreatedAt(),
            'updated_at' => $token->getUpdatedAt()
        ];
    }
}
