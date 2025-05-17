<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'app_statistics_index')]
    public function index(OrderRepository $orderRepository, SupplierRepository $supplierRepository, ProductRepository $productRepository): Response
    {
        $shop = $this->getUser()->getShop();
        $suppliers = $supplierRepository->findByShop($shop);
        $products = $productRepository->findByShopId($shop->getId());
        $orders = $orderRepository->findByShop($shop);
        // dd();

        return $this->render('statistics/index.html.twig', [
            'suppliers_count' => count($suppliers),
            'products_count' => count($products),
            'orders_count' => count($orders),
            'orders' => $orders,
            'suppliers' => $suppliers
        ]);
    }
}
