<?php

namespace Model\Services;

use Model\Entities\Produit as Produit;

class ProduitService
{

    public function createProduit($content)
    {
        // TODO : Implement validation
        $produit = new Produit();
        $produit->setName($content->product_name);
        $produit->setDescription($content->description);
        $produit->setPrix($content->prix);
        $produit->setDateCreation(date("Y-m-d H:i:s"));
        return $produit;
    }
}