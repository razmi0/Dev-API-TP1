<?php

namespace Model\Entities;

/**
 * Class Produit
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $prix
 * @property string $date_creation
 */
class Produit
{
    private $id;
    private $name;
    private $description;
    private $prix;
    private $date_creation;

    public function __construct($id, $name, $description, $prix, $date_creation)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->prix = $prix;
        $this->date_creation = $date_creation;
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
