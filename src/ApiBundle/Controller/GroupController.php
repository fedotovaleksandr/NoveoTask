<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\UserGroup;
use ApiBundle\Form\UserGroupType;
use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class GroupController extends FOSRestController
{
    /**
     * @return \FOS\RestBundle\View\View
     * @Rest\View(serializerGroups={"user_group"})
     * @Rest\Get("/fetch/")
     */
    public function getGroupsAction()
    {
        $groups = $this->get('api.user_group_repository')->findAll();
        $view = $this->view($groups, 200);
        return $view;
    }

    /**
     *
     * @Rest\View(serializerGroups={"user_group"})
     * @Rest\Post("/create/")
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postGroupCreateAction(Request $request)
    {

        $form = $this->createForm(UserGroupType::class);
        $form->submit(json_decode($request->getContent(),true));
        if ($form->isValid()) {
            $group = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
            return $this->view($group, 200);
        }
        return $this->view($form, 400);
    }


    /**
     * @param UserGroup $user
     *
     * @return \FOS\RestBundle\View\View
     * @Rest\View(serializerGroups={"user_group"})
     * @Rest\Get("/{id}/", name="_info")
     */
    public function getUserAction(UserGroup $user)
    {
        $view = $this->view($user, 200);
        return $view;
    }

    /**
     * @param UserGroup $group
     *
     * @return \FOS\RestBundle\View\View
     * @Rest\View(serializerGroups={"user_group"})
     * @Rest\Patch("/{id}/", name="_update")
     */
    public function postUserAction(UserGroup $group)
    {
        $form = $this->createForm(UserGroupType::class,$group);

        $form->submit(json_decode($this
            ->get('request_stack')
            ->getCurrentRequest()
            ->getContent(),true),false
        );
        if ($form->isValid()) {
            $group = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
            return $this->view($group, 200);
        }
        return $this->view($form, 400);
    }
}