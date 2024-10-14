<?php

namespace Utils;

use Model\Entities\Produit as Produit;
use Utils\Error as Error;



class Validator
{
    private $error = null;
    private $produit = null;
    private $NAME_MAX_LENGTH = 50;
    private $DESCRIPTION_MAX_LENGTH = 65000;

    public function __construct()
    {
        $this->error = new Error();
        $this->error->setLocation("model/services/Validator.php")->setCode(400);
        $this->produit = new Produit();
    }

    public function createProduit($client_json)
    {
        try {
            $this->validate($client_json);
            $this->buildProduct($client_json);
        } catch (Error $error) {
            throw $error;
        }
        return $this->produit;
    }

    private function validate($client_json)
    {
        $this->isNotNull($client_json);
        $this->isNotEmpty($client_json);
        $this->isNotTooLong($client_json);
    }

    private function buildProduct($client_json)
    {
        $this->produit->setId($client_json->id);
        $this->produit->setName($client_json->name);
        $this->produit->setDescription($client_json->description);
        $this->produit->setPrix($client_json->prix);
        $this->produit->setDateCreation(date("Y-m-d H:i:s"));
    }

    private function isNotNull($client_json)
    {
        if (is_null($client_json->name)) {
            $this->error->setMessage("Le nom du produit est obligatoire");
        } else if (is_null($client_json->description)) {
            $this->error->setMessage("La description du produit est obligatoire");
        } else if (is_null($client_json->prix)) {
            $this->error->setMessage("Le prix du produit est obligatoire");
        } else if (is_null($client_json->id)) {
            $this->error->setMessage("L'id du produit est obligatoire");
        }
    }

    private function isNotEmpty($client_json)
    {
        if ($client_json->name == "") {
            $this->error->setMessage("Le nom du produit est obligatoire");
        } else if ($client_json->description == "") {
            $this->error->setMessage("La description du produit est obligatoire");
        } else if ($client_json->prix == "") {
            $this->error->setMessage("Le prix du produit est obligatoire");
        }
    }

    private function isNotTooLong($client_json)
    {
        if (strlen($client_json->name) >= $this->NAME_MAX_LENGTH) {
            $this->error->setMessage("Le nom du produit est trop long, la taille maximale est de 50 caractères");
        } else if (strlen($client_json->description) > $this->DESCRIPTION_MAX_LENGTH) {
            $this->error->setMessage("La description du produit est trop longue, la taile maximale est de 65000 caractères");
        }
    }
}
