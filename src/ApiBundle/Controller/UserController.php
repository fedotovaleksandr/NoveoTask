<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Form\UserType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

class UserController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"user"})
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('ApiBundle:User')->findAll();
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     *
     * @View()
     * @Post("/users/create", name="_one")
     */
    public function postUsersCreateAction(Request $request)
    {

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        }

        return $form;
    }

    /**
     * @return \FOS\RestBundle\View\View
     * @View(serializerGroups={"user"})
     * @Get("/users/{id}/", name="_one")
     */
    public function getUserAction(User $user)
    {
        $view = $this->view($user, 200);
        return $view;
    }
}
