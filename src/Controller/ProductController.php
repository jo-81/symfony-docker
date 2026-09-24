<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product/create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $product->setName('Clavier');
        $product->setPrice(4999);

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response(
            'Produit créé avec l\'ID : '.$product->getId()
        );
    }

    #[Route('/product/{id}')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (null === $product) {
            return new Response('Produit introuvable', 404);
        }

        return new Response(
            'Produit : '.$product->getName()
            .' — Prix : '.$product->getPrice()
        );
    }
}
