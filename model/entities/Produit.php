<?php

namespace Model\Entities;

/**
 * Class Produit
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $prix
 * @property string $creation_date
 */
class Produit {
    private $id;
    private $name;
    private $description;
    private $prix;
    private $creation_date;


    public function __construct($id, $name, $description, $prix, $creation_date) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->prix = $prix;
        $this->creation_date = $creation_date;

    }

    public function getId() {
        return $this->id;
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

