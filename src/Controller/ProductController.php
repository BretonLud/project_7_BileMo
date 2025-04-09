<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    public function getProducts(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        
        return $this->json([
            'items' => $products,
        ]);
    }
}