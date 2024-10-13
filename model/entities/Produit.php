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



    public function getId()
    {
        return $this->id;
    }


    public function getProductName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getDateCreation()
    {
        return $this->date_creation;
    }

    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
    }
}
