<?php

namespace Model\Services;

use Model\Entities\Produit as Produit;
use Utils\Error as Error;

class ProduitService
{

    public function createProduit($content)
    {
        $produit = new Produit();
        try {
            $this->validate($content);
            $produit = $this->buildProduct($content);
        } catch (Error $error) {
            throw $error;
        }
        return $produit;
    }

    private function validate($content)
    {
        $this->isNotNull($content);
        $this->isNotEmpty($content);
        $this->isNotTooLong($content);
    }

    private function buildProduct($content)
    {
        $produit = new Produit();
        $produit->setName($content->name);
        $produit->setDescription($content->description);
        $produit->setPrix($content->prix);
        $produit->setDateCreation(date("Y-m-d H:i:s"));
        return $produit;
    }

    private function isNotNull($content)
    {
        if (is_null($content->name)) {
            $this->buildError("Le nom du produit est obligatoire");
        } else if (is_null($content->description)) {
            $this->buildError("La description du produit est obligatoire");
        } else if (is_null($content->prix)) {
            $this->buildError("Le prix du produit est obligatoire");
        }
    }

    private function isNotEmpty($content)
    {
        if ($content->name == "") {
            $this->buildError("Le nom du produit est obligatoire");
        } else if ($content->description == "") {
            $this->buildError("La description du produit est obligatoire");
        } else if ($content->prix == "") {
            $this->buildError("Le prix du produit est obligatoire");
        }
    }

    private function isNotTooLong($content)
    {
        if (strlen($content->name) >= 50) {
            $this->buildError("Le nom du produit est trop long");
        } else if (strlen($content->description) > 255) {
            $this->buildError("La description du produit est trop longue");
        }
    }

    private function buildError($message)
    {
        $error = new Error();
        $error->setCode(400)->setError($message)->setLocation("model/services/ProduitService.php :: buildError");
        throw $error;
    }
}
