<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function showAction(Request $request)
    {
        $category = $this->getDoctrine()->getManager();

        $product = $category->getRepository('AppBundle:Product')->findAll();

        if(!$product)
        {
            throw $this->createNotFoundException('Not found');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $product,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('@App/show.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @return Response
     */
    public function showCategoryAction()
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if(!$category)
        {
            throw $this->createNotFoundException('Not found');
        }

        return $this->render('@App/Category/category.html.twig', array(
            'categories' => $category
        ));
    }

    /**
     * @Route("/category")
     */
    public function viewCategoryAction()
    {
        return $this->render('@App/Category/viewcategory.html.twig');
    }

    /**
     * Route("category_id")
     * @param $category_id
     * @return Response
     */
    public function showProductAction($category_id, Request $request)
    {
        $category = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($category_id);

        if(!$category)
        {
            throw $this->createNotFoundException('Not found');
        }

        $products = $category->getProductId();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('@App/Product/product.html.twig', array(
            'pagination' => $pagination
        ));
    }
}
