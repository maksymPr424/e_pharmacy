<?php

namespace App\Controller\Admin;

use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/supplier')]
final class SupplierController extends AbstractController
{
    #[Route(name: 'app_supplier_index', methods: ['GET', 'POST'])]
    public function index(Request $request, SupplierRepository $supplierRepository): Response
    {
        $shop = $this->getUser()->getShop();
        if (!$shop) {
            throw $this->createNotFoundException('No shop associated with your account.');
        }

 
        if (empty($request->query->all())) {
            $suppliers = $supplierRepository->findByShopWithFilters($shop, [
                'name' => '',
                'address' => '',
                'company' => '',
                'status' => '',
            ]);

            return $this->render('supplier/index.html.twig', [
                'suppliers' => $suppliers,
            ]);
        }
        
        $filters = $request->query->all()["filter"];

        $name = is_array($filters) && isset($filters['name']) ? $filters['name'] : '';
        $address = is_array($filters) && isset($filters['address']) ? $filters['address'] : '';
        $company = is_array($filters) && isset($filters['company']) ? $filters['company'] : '';
        $status = is_array($filters) && isset($filters['status']) ? $filters['status'] : '';

        // Fetch suppliers for the shop with filters
        $suppliers = $supplierRepository->findByShopWithFilters($shop, [
            'name' => $name,
            'address' => $address,
            'company' => $company,
            'status' => $status,
        ]);

        return $this->render('supplier/index.html.twig', [
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/new', name: 'app_supplier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supplier);
            $entityManager->flush();

            return $this->redirectToRoute('app_supplier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplier/new.html.twig', [
            'supplier' => $supplier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplier_show', methods: ['GET'])]
    public function show(Supplier $supplier): Response
    {
        return $this->render('supplier/show.html.twig', [
            'supplier' => $supplier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supplier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supplier $supplier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_supplier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplier/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supplier_delete', methods: ['POST'])]
    public function delete(Request $request, Supplier $supplier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supplier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($supplier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_supplier_index', [], Response::HTTP_SEE_OTHER);
    }
}
