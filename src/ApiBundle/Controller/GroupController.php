<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\UserGroup;
use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

class GroupController extends FOSRestController
{
    /**
     * @return \FOS\RestBundle\View\View
     * @View(serializerGroups={"user_group"})
     */
    public function getGroupsAction()
    {
        $users = $this->getDoctrine()->getRepository('ApiBundle:UserGroup')->findAll();
        $view = $this->view($users, 200);
        return $view;
    }

    /**
     * @param UserGroup $user
     *
     * @return \FOS\RestBundle\View\View
     * @View(serializerGroups={"user_group"})
     * @Get("/groups/{id}/", name="_one")
     */
    public function getUserAction(UserGroup $user)
    {
        $view = $this->view($user, 200);
        return $view;
    }
}