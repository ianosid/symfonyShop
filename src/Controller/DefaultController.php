<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('default/index.html.twig',['categories'=>$categoryRepository->findAll()]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/sayHello/{firstName}/{lastName}", name="test")
     */
    public function test($firstName, $lastName): Response
    {
        $name = "$firstName $lastName";

        return $this->render('default/test.html.twig',['name'=>$name]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/category/{category}", name="category")
     */
    public function category(Category $category): Response
    {
        return $this->render('default/category.html.twig',['category'=>$category]);
    }
}
