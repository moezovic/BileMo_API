<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Entity\MobilePhone;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
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
use App\Exception\NotFoundException;
use App\Exception\NotAuthorizedException;
use App\Exception\ResourceValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;



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
     * @View(StatusCode = 200,
     *       serializerGroups={"list"})
     * 
     * @Doc\Operation(
     *     tags={"Users"},
     *     summary="Get the list of all users.",
     *     @SWG\Response(
     *         response=200,
     *         description="Returned when a list of users is returned successfully",
     *         @Model(type=User::class, groups={"list"})
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when the JWT Token is expired or invalid"
     *     )
     * )

     */
    public function showUsersList(ParamFetcher $paramFetcher)
    {   
        $list = $this->getDoctrine()->getRepository(User::class)->findUsersByClient(
            $paramFetcher->get('product'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $this->getUser()->getId()
        );

         return $list;

    }

    /**
     * @Get(
     *      path = "/api/users/{id}",
     *      name = "show_user_details",
     *      requirements = {"id"="\d+"}
     * )
     * @View(StatusCode = 200,
     *       serializerGroups={"detail"})
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
     *         @Model(type=User::class, groups={"detail"})
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Returned when the JWT Token is expired or invalid"
     *     )
     * )
     * @Cache(smaxage="1800", mustRevalidate=true)
     */
    public function showUser(User $user)
    {
;        if(null === $user){
            throw new NotFoundHttpException('The id used does not refer to any user');
        }
        // Get user's associated client id
        $userClientId = $user->getClient()->getId();
        // check if the recovered client id correspond to connected client id
        if($userClientId !== $this->getUser()->getId()){
            throw new NotAuthorizedException('You are not authorized to access this data');
        }
        return $user;
    }

   /** 
    * 
    * @Rest\Post(
    *            Path = "/api/users",
    *            name = "create_user")
    * @Rest\View(StatusCode = 201)
    *
    * @Doc\Operation(
    *     tags={"Users"},
    *     summary="Create a new user",
    *     @SWG\Response(
    *         response=201,
    *         @Model(type=User::class),
    *         description="Returned when a user is created successfully",
    *     ),
    *     @SWG\Response(
    *         response="401",
    *         description="Returned when the JWT Token is expired or invalid"
    *     )
    * )
    */
    public function addUSer(Request $request, ValidatorInterface $validator)
    {   
        // verify the validity of mobile phone choice
        $phoneChoice = $this->getDoctrine()
            ->getRepository(MobilePhone::class)
            ->find($request->request->get('phone_choice'));

        if($phoneChoice == null){
            throw new notFoundException('The user\'s phone choice is not valid');
        }

        $user = new User();

        try {
            $user->setFirstname($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setPhoneNumber($request->request->get('phone_number'));
            $user->setAddress($request->request->get('address'));
            $user->setClient($this->getUser());
            $user->AddPhoneChoice($phoneChoice);
        } catch (\Throwable $th) {
            throw new ResourceValidationException("User's sent data contains errors");
            
        }

        // Verify if received the new user's data ara valid
        // $validationErrors = $validator->validate($user);

        // if (count($validationErrors)) {
        //     $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
        //     foreach ($validationErrors as $error) {
        //         $message .= sprintf("Field %s: %s ", $validationErrors->getPropertyPath(), $validationErrors->getMessage());
        //     }

        //     throw new ResourceValidationException($message);
        // }

        $user->setClient($this->getUser());
        $this->em->persist($user);
        $this->em->flush();

        return $user;
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
            throw new NotFoundHttpException('The id used does not refer to any user');
        }
        // Get user's associated client id
        $userClientId = $user->getClient()->getId();
        // check if the recovered client id correspond to connected client id
        if($userClientId !== $this->getUser()->getId()){
            throw new NotAuthorizedException('You are not authorized to access this data');
        }

        // Detach associated product before user removal
        $product = $this->getDoctrine()
            ->getRepository(MobilePhone::class)
            ->findproductByUser($user->getId());

        $user->removePhoneChoice($product);
        $this->em->persist($user);
        $this->em->remove($user);
        $this->em->flush();

    }
}
