<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\MobilePhone;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use App\Representation\Phones;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


class MobilePhoneController extends AbstractFOSRestController
{
    /**
     * @Get(
     *      path = "/api/products/{id}",
     *      name = "show_product_details"
     * )
     * @View(serializerGroups={"detail"})
     * 
     * @Doc\Operation(
     *     tags={"Products"},
     *     summary="Get the details of a specific mobile phone",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="The product unique identifier"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returned when successful",
     *         @Doc\Model(type=MobilePhone::class)
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when the JWT Token is expired or invalid"
     *     )
     * )
     */
    public function showProduct(MobilePhone $product)
    {
        return $product;
    }

    /**
     * @Get(
     *      path = "/api/products",
     *      name = "show_products_list"
     * )
     * @Rest\QueryParam(
     *     name="user",
     *     requirements="[a-zA-Z0-9]",
     *     default=false,
     *     description="find product list by user."
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
     *     default="10",
     *     description="Max number of phones per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="The pagination offset"
     * )
     * @View(serializerGroups={"list"})
     * 
     * @Doc\Operation(
     *     tags={"Products"},
     *     summary="Get the list of all mobile phones.",
     *     @SWG\Response(
     *         response=200,
     *         description="Returned when the list is recovered successfully",
     *         @Doc\Model(type=MobilePhone::class)
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when the JWT Token is expired or invalid"
     *     )
     * )
     */
    public function listProducts(ParamFetcher $paramFetcher)
    {   

        $list = $this->getDoctrine()->getRepository(MobilePhone::class)->findProducts(
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
        
        return $list;

    }
}
