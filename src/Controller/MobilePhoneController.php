<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\MobilePhone;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;


class MobilePhoneController extends AbstractController
{
    /**
     * @Get(
     *      path = "/products/{id}",
     * )
     * @View()
     */
    public function showProduct(MobilePhone $product)
    {
        return $product;
    }

    /**
     * @Get(
     *      path = "/products",
     * )
     * @View()
     */
    public function listProducts()
    {
        $repo = $this->getDoctrine()->getRepository(MobilePhone::class);
        $products = $repo->findAll();
        return $products;
    }
}
