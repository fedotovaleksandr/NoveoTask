<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @View(serializerGroups={"user"})
     * @Post("/users/create", name="_one")
     */
    public function postUsersCreateAction()
    {
        $users = $this->getDoctrine()->getRepository('ApiBundle:User')->findAll();
        $view = $this->view($users, 200);
        return $this->handleView($view);
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
