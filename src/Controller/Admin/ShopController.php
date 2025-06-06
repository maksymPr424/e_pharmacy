<?php

namespace App\Controller\Admin;

use App\Entity\Shop;
use App\Form\ShopType;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shop')]
final class ShopController extends AbstractController
{
    #[Route(name: 'app_shop_index', methods: ['GET'])]
    public function index(): Response
    {
        $shop = $this->getUser()->getShop();

        if (!$shop) {
            return $this->redirectToRoute('app_shop_new');
        }

        return $this->render('shop/show.html.twig', [
            'shop' => $shop,
        ]);
    }

    #[Route('/new', name: 'app_shop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shop = new Shop();
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shop->setUser($this->getUser());
            $entityManager->persist($shop);
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop/new.html.twig', [
            'shop' => $shop,
            'form' => $form,
        ]);
    }

    #[Route('/edit', name: 'app_shop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shop = $this->getUser()->getShop();
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shop/edit.html.twig', [
            'shop' => $shop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shop_delete', methods: ['POST'])]
    public function delete(Request $request, Shop $shop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shop->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($shop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shop_index', [], Response::HTTP_SEE_OTHER);
    }
}
