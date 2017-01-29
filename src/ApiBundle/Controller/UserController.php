<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Form\UserType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/fetch/")
     */
    public function getUsersAction()
    {
        $users = $this->get('api.user_repository')->findAll();
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     *
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Post("/create/")
     */
    public function postUsersCreateAction(Request $request)
    {

        $form = $this->createForm(UserType::class);
        $form->submit(json_decode($request->getContent(),true));
        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->view($user, 200);
        }
        return $this->view($form, 400);
    }


    /**
     * @return \FOS\RestBundle\View\View
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/{id}/", name="_info")
     */
    public function getUserAction(User $user)
    {
        $view = $this->view($user, 200);
        return $view;
    }

    /**
     * @param User $user
     *
     * @return \FOS\RestBundle\View\View
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/{id}/", name="_update")
     */
    public function postUserAction(User $user)
    {
        $form = $this->createForm(UserType::class,$user);

        $form->submit(json_decode($this
            ->get('request_stack')
            ->getCurrentRequest()
            ->getContent(),true),false
        );
        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->view($user, 200);
        }
        return $this->view($form, 400);
    }
}
