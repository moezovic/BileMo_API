<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use  FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use App\Representation\Users;
use Symfony\Component\Validator\ConstraintViolationList;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;


class ClientController extends AbstractFOSRestController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Get(
     *      path = "/api/users",
     *      name = "show_users_list"
     * )
     * @Rest\QueryParam(
     *     name="product",
     *     requirements="[a-zA-Z0-9]",
     *     default=false,
     *     description="find users list by product."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)."
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of users per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="The pagination offset"
     * )
     * @View()
     */
    public function showUsersList(ParamFetcher $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository(User::class)->findUsers(
            $paramFetcher->get('product'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Users($pager);

    }

    /**
     * @Get(
     *      path = "/api/users/{id}",
     *      name = "show_user_details",
     * )
     * @View(serializerGroups={"detail"})
     */
    public function showUser(User $user)
    {
        return $user;
    }

   /** 
    * @Rest\Post(
    *            Path = "/api/users",
    *            name = "create_user")
    * @Rest\View
    * @ParamConverter("user", converter="fos_rest.request_body")
    */
    public function addUSer(User $user, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        
        $this->em->persist($user);
        $this->em->flush();

        return $this->view(
            $article,
            Response::HTTP_CREATED,
            [
                'location' => $this->generateUrl('show_user', ['id' => $article->getId(), UrlGeneratorInterface::ABSOLUTE_URL])
            ]
            );
    }

    /**
     * @Rest\Delete(
     *              path = "api/users/{id}",
     *              name = "delete_user",
     *              requirements = {"id" = "\d+"}
     * )
     * @Rest\View(
     *     populateDefaultVars = false,
     *     statusCode = 204
     *     )
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function deleteUSer(User $user)
    {
        dump($user); die;
        if(null == $user){
            // throw customized Exception
        }
        $this->em->remove($user);
        $this->em->flush();

    }
}
