<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class OpenProductController extends AbstractController
{
    #[Route('/{id}', name: 'app_open_product_show', methods: ['GET'])]
    public function show(Product $product, ShopRepository $shopRepository): Response
    {
        $shop = $shopRepository->findById($product->getShop());

        return $this->render('product/open_products.html.twig', [
            'product' => $product,
            'shop' => $shop
        ]);
    }
}
