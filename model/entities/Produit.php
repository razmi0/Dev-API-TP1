<?php

namespace Model\Entities;

/**
 * Class Produit
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $prix
 * @property string $date_creation
 * 
 * @method string getId()
 * @method string getName()
 * @method string getDescription()
 * @method string getPrix()
 * @method string getDateCreation()
 * @method setId(string $id)
 * @method setName(string $name)
 * @method setDescription(string $description)
 * @method setPrix(string $prix)
 * @method setDateCreation(string $date_creation)
 * @method toArray()
 */
class Produit
{
    private $id = null;
    private $name = null;
    private $description = null;
    private $prix = null;
    private $date_creation = null;

    public function __construct($id = null, $name = null, $description = null, $prix = null, $date_creation = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->prix = $prix;
        $this->date_creation = $date_creation;
    }

    public static function make(array $data): Produit
    {
        return new Produit(
            $data["id"] ?? null,
            $data["name"],
            $data["description"],
            $data["prix"],
            $data["date_creation"] ?? date("Y-m-d H:i:s"),
        );
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getProductName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
        return $this;
    }

    public function getDateCreation()
    {
        return $this->date_creation;
    }

    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
        return $this;
    }

    public function toArray()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "prix" => $this->prix,
            "date_creation" => $this->date_creation
        ];
    }
}
