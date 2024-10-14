<?php

namespace Model\Services;

use Model\Entities\Produit as Produit;
use Utils\Error as Error;



class ProduitService
{
    private $error = null;
    private $produit = null;
    private $NAME_MAX_LENGTH = 50;
    private $DESCRIPTION_MAX_LENGTH = 65000;

    public function __construct()
    {
        $this->error = new Error();
        $this->error->setLocation("model/services/ProduitService.php")->setCode(400);
        $this->produit = new Produit();
    }

    public function createProduit($content)
    {
        try {
            $this->validate($content);
            $this->buildProduct($content);
        } catch (Error $error) {
            throw $error;
        }
        return $this->produit;
    }

    private function validate($content)
    {
        $this->isNotNull($content);
        $this->isNotEmpty($content);
        $this->isNotTooLong($content);
    }

    private function buildProduct($content)
    {
        $this->produit->setId($content->id);
        $this->produit->setName($content->name);
        $this->produit->setDescription($content->description);
        $this->produit->setPrix($content->prix);
        $this->produit->setDateCreation(date("Y-m-d H:i:s"));
    }

    private function isNotNull($content)
    {
        if (is_null($content->name)) {
            $this->error->setMessage("Le nom du produit est obligatoire");
        } else if (is_null($content->description)) {
            $this->error->setMessage("La description du produit est obligatoire");
        } else if (is_null($content->prix)) {
            $this->error->setMessage("Le prix du produit est obligatoire");
        } else if (is_null($content->id)) {
            $this->error->setMessage("L'id du produit est obligatoire");
        }
    }

    private function isNotEmpty($content)
    {
        if ($content->name == "") {
            $this->error->setMessage("Le nom du produit est obligatoire");
        } else if ($content->description == "") {
            $this->error->setMessage("La description du produit est obligatoire");
        } else if ($content->prix == "") {
            $this->error->setMessage("Le prix du produit est obligatoire");
        }
    }

    private function isNotTooLong($content)
    {
        if (strlen($content->name) >= $this->NAME_MAX_LENGTH) {
            $this->error->setMessage("Le nom du produit est trop long, la taille maximale est de 50 caractères");
        } else if (strlen($content->description) > $this->DESCRIPTION_MAX_LENGTH) {
            $this->error->setMessage("La description du produit est trop longue, la taile maximale est de 65000 caractères");
        }
    }
}
