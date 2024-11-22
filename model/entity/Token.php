<?php



namespace Model\Entity;

/**
 * Class Token
 * @property string $token_id
 * @property string $token_encrypted
 * @property string $user_id
 * @property string $created_at
 * @property string $updated_at
 * 
 * @method string getTokenId()
 * @method string getTokenEncrypted()
 * @method string getUserId()
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 * @method setTokenId(string $token_id)
 * @method setTokenEncrypted(string $token_encrypted)
 * @method setUserId(string $user_id)
 * @method setCreatedAt(string $created_at)
 * @method setUpdatedAt(string $updated_at)
 * @method toArray()
 */
class Token
{
    public function __construct(
        private ?string $token_id = null,
        private ?string $token_encrypted = null,
        private ?string $user_id = null,
        private ?string $created_at = null,
        private ?string $updated_at = null
    ) {}


    public static function make(array $data)
    {
        return new Token(
            $data["token_id"] ?? null,
            $data["token_encrypted"] ?? null,
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

    public function getTokenEncrypted(): ?string
    {
        return $this->token_encrypted;
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


    public function setTokenEncrypted(string $token_encrypted): self
    {
        $this->token_encrypted = $token_encrypted;
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
            'token_encrypted' => $token->getTokenEncrypted(),
            'user_id' => $token->getUserId(),
            'created_at' => $token->getCreatedAt(),
            'updated_at' => $token->getUpdatedAt()
        ];
    }
}
