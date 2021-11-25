<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Form\Product1Type;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/crud/product")
 */
class CrudProductController extends AbstractController
{
    /**
     * @Route("/", name="crud_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('crud_product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/remove-image/{id}", name="crud_product_remove_image")
     */
    public function removeImage(ProductImage $image, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($image);
        $entityManager->flush();
        return $this->redirectToRoute('crud_product_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/new", name="crud_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFiles = $form->get('images')->getData();
            foreach ($imageFiles as $imageFile){
                $imageFile->move('/var/www/html/national02/daniel/symfonyShop/public/images', $imageFile->getClientOriginalName());
                $productImage = new ProductImage();
                $productImage->setImage($imageFile->getClientOriginalName());
                $productImage->setProduct($product);
                $entityManager->persist($productImage);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="crud_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('crud_product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('images')->getData();
            foreach ($imageFiles as $imageFile){
                $imageFile->move('/var/www/html/national02/daniel/symfonyShop/public/images', $imageFile->getClientOriginalName());
                $productImage = new ProductImage();
                $productImage->setImage($imageFile->getClientOriginalName());
                $productImage->setProduct($product);
                $entityManager->persist($productImage);
            }
            $entityManager->flush();

            return $this->redirectToRoute('crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="crud_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
