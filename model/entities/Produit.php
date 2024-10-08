<?php

namespace Model\Entities;

/**
 * Class Produit
 * @property string $name
 * @property string $description
 * @property string $prix
 * @property string $creation_date
 */
class Produit {
    private $name;
    private $description;
    private $prix;
    private $creation_date;


    public function __construct($name, $description, $prix, $creation_date) {
        $this->name = $name;
        $this->description = $description;
        $this->prix = $prix;
        $this->creation_date = $creation_date;

    }

   

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

}

