<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Event\UserEvent;
use ApiBundle\Event\UserEvents;
use ApiBundle\Form\UserType;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/fetch", name="_user")
     */
    public function fetchAction()
    {
        $userService = $this->get('api.service.user.service');
        if ($this->getParameter('app.use_cache')) {
            $cacheItem = $this->get('app.cache')->getItem($userService->getUserCacheKey('all'));
            if ($cacheItem->isHit()) {
                return new Response($cacheItem->get());
            }
        }
        $users = $this->get('api.user_repository')->findAll();
        $view = $this->view($users, 200);
        $view->setContext((new Context())->setGroups(['user']));
        $response = $this->handleView($view);
        if ($this->getParameter('app.use_cache')) {
            /**
             * @var $cacheItem CacheItem
             */
            $cacheItem->set($response->getContent())
                ->tag($userService->getUserCacheTags('fetch'))
                ->expiresAfter($this->getParameter('app.cache_ttl'));
            $this->get('app.cache')->save($cacheItem);
        }

        return $response;
    }

    /**
     *
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Post("/create", name="_user")
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     */
    public function createAction(Request $request)
    {

        $form = $this->createForm(UserType::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('event_dispatcher')->dispatch(UserEvents::AFTER_USER_CREATE, new UserEvent($user));
            return $this->view($user, 200);
        }
        return $this->view($form, 400);
    }


    /**
     * @return Response
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/{id}/", name="_user")
     */
    public function userAction($id)
    {
        $userService = $this->get('api.service.user.service');
        if ($this->getParameter('app.use_cache')) {
            $cacheItem = $this->get('app.cache')->getItem($userService->getUserCacheKey($id));
            if ($cacheItem->isHit()) {
                return new Response($cacheItem->get());
            }
        }
        $user = $this->get('api.user_repository')->find($id);
        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }
        $view = $this->view($user, 200);
        $view->setContext((new Context())->setGroups(['user']));
        $response = $this->handleView($view);
        if ($this->getParameter('app.use_cache')) {
            /**
             * @var $cacheItem CacheItem
             */
            $cacheItem->set($response->getContent())
                ->expiresAfter($this->getParameter('app.cache_ttl'));
            $this->get('app.cache')->save($cacheItem);
        }

        return $response;
    }

    /**
     * @param User $user
     *
     * @return \FOS\RestBundle\View\View
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/{id}/", name="_user")
     */
    public function updateAction(User $user)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->submit(json_decode($this
            ->get('request_stack')
            ->getCurrentRequest()
            ->getContent(), true), false
        );
        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('event_dispatcher')->dispatch(UserEvents::AFTER_USER_UPDATE, new UserEvent($user));
            return $this->view($user, 200);
        }
        return $this->view($form, 400);
    }
}
