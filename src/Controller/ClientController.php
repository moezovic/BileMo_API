<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use  FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use App\Representation\Users;
use Symfony\Component\Validator\ConstraintViolationList;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ClientController extends AbstractFOSRestController
{
    private $em;
    private $loggedInId;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $loggedInId = $this->getUser()->getId();
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
     * 
     * @Doc\Operation(
     *     tags={"Users"},
     *     summary="Get the list of all users.",
     *     @SWG\Response(
     *         response=200,
     *         description="Returned when a list of users is returned successfully",
     *         @Doc\Model(type=User::class)
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when the JWT Token is expired or invalid"
     *     )
     * )
     */
    public function showUsersList(ParamFetcher $paramFetcher)
    {

        $pager = $this->getDoctrine()->getRepository(User::class)->findUsersByClient(
            $paramFetcher->get('product'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $loggedInId
        );

        return new Users($pager);

    }

    /**
     * @Get(
     *      path = "/api/users/{id}",
     *      name = "show_user_details",
     * )
     * @View(serializerGroups={"detail"})
     * 
     * @Doc\Operation(
     *     tags={"Users"},
     *     summary="Get a specific user",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="The user unique identifier"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returned when a user is returned successfully",
     *         @Doc\Model(type=User::class)
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when the JWT Token is expired or invalid"
     *     )
     * )
     */
    public function showUser(User $user)
    {
        $userClientId = $user->getClient()->getId();

        if(null == $user){
            // throw not found Exception
        }
        if($userClientId !== $loggedInId){
            // throw not allowed exception
        }
        return $user;
    }

   /** 
    * @Rest\Post(
    *            Path = "/api/users",
    *            name = "create_user")
    * @Rest\View
    * @ParamConverter("user", converter="fos_rest.request_body")
    *
    * @Doc\Operation(
    *     tags={"Users"},
    *     summary="Create a new user",
    *     @SWG\Response(
    *         response=201,
    *         description="Returned when a user is created successfully",
    *         @Doc\Model(type=User::class)
    *     ),
    *     @SWG\Response(
    *         response="401",
    *         description="Returned when the JWT Token is expired or invalid"
    *     )
    * )
    */
    public function addUSer(User $user, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        $user->setClient($this->getDoctrine()->getRepository(Client::class)->findbyId($loggedInId));
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
     * 
     * @Doc\Operation(
     *     tags={"Users"},
     *     summary="Delete a specific user",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="The user unique identifier"
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Returned when user deleted succssefully",
     *         @Doc\Model(type=User::class)
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when the JWT Token is expired or invalid"
     *     )
     * )
     */
    public function deleteUSer(User $user)
    {
        if(null == $user){
            // throw not found Exception
        }
        if($userClientId !== $loggedInId){
            // throw not allowed exception
        }
        $this->em->remove($user);
        $this->em->flush();

    }
}
