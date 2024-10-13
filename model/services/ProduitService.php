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
    }

    private function buildProduct($content)
    {
        $produit = new Produit();
        $produit->setName($content->product_name);
        $produit->setDescription($content->description);
        $produit->setPrix($content->prix);
        $produit->setDateCreation(date("Y-m-d H:i:s"));
        return $produit;
    }

    private function isNotNull($content)
    {
        if (is_null($content->product_name)) {
            $this->buildError("Le nom du produit est obligatoire");
        } else if (is_null($content->description)) {
            $this->buildError("La description du produit est obligatoire");
        } else if (is_null($content->prix)) {
            $this->buildError("Le prix du produit est obligatoire");
        }
    }

    private function buildError($message)
    {
        $error = new Error();
        $error->setCode(400)->setError($message);
        throw $error;
    }
}