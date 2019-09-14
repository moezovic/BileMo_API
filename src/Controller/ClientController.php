<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


class ClientController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Get(
     *      path = "/api/users",
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
     *      name = "show_user",
     * )
     * @View(serializerGroups={"detail"})
     */
    public function showUser(User $user)
    {
        return $user;
    }

   /** 
    * @Rest\Post("/api/users")
    * @Rest\View
    * @ParamConverter("user", converter="fos_rest.request_body")
    */
    public function addUSer(User $user){

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
     * @Rest\View()
     */
    public function deleteUSer($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findById($id);
        
        if(null == $user){
            // throw customized Exception
        }
        $this->em->remove($user);
        $this->em->flush();

    }
}
