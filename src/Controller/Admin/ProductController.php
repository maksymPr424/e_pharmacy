<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Shop;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $shop = $this->getUser()->getShop();
        // Find the shop
        if (!$shop) {
            throw $this->createNotFoundException('Shop not found');
        }

        $searchTerm = $request->query->get('search', '');

        // Fetch products for the shop, filtered by search term
        $products = $productRepository->findByShopWithSearch($shop, $searchTerm);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'shop' => $shop
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ShopRepository $shopRepository): Response
    {
        $shop = $this->getUser()->getShop();

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setShop($shopRepository->findById($shop->getId()));
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', ['shop' => $shop], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
            'shop' => $shop
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        $shop = $this->getUser()->getShop();

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'shop' => $shop
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $shop = $this->getUser()->getShop();
        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
            'shop' => $shop
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
