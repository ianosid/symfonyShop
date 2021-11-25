<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/cat")
 */
class CategoryController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/view/{category}", name="category")
     */
    public function view(Category $category): Response
    {
        return $this->render('default/category.html.twig',['category'=>$category]);
    }

    /**
     * @Route("/create", name="category_form")
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $imageFile = $categoryForm->get('file')->getData();
            $imageFile->move('/var/www/html/national02/daniel/symfonyShop/public/images', $imageFile->getClientOriginalName());
            $category->setImage($imageFile->getClientOriginalName());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }

        return $this->render('category/index.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }

    /**
     * @Route("/edit/{category}", name="category_edit")
     */
    public function edit(Category $category, Request $request): Response
    {
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $imageFile = $categoryForm->get('file')->getData();
            $imageFile->move('/var/www/html/national02/daniel/symfonyShop/public/images', $imageFile->getClientOriginalName());
            $category->setImage($imageFile->getClientOriginalName());

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }

        return $this->render('category/index.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }
}
