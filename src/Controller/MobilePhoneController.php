<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\MobilePhone;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Validator\Constraints;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use App\Representation\Phones;



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
     * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of phones per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="The pagination offset"
     * )
     * @View()
     */
    public function listProducts(ParamFetcher $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository(MobilePhone::class)->search(
            // $paramFetcher->get('keyword'),
            // $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Phones($pager);

    }
}
