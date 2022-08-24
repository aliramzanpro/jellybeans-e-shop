<?php

namespace App\Controller;

use App\Entity\Product;
use App\Services\SearchProducts;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(ProductRepository $repoProduct, Request $request): Response
    {
        $products = $repoProduct->findAll();
        $search = new SearchProducts();
        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           $products = $repoProduct->findWithSearch($search);    
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'search' => $form->createView()
        ]);

    }

    /**
     * @Route("/product/{slug}", name="product_details")
     */
    public function show(?Product $product): Response{
        
        if(!$product){
            return $this->redirectToRoute("home");
        }
        return $this->render("product/single_product.html.twig",[
            'product' => $product,
        ]);
    }


}
