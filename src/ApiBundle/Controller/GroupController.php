<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\UserGroup;
use ApiBundle\Event\GroupEvent;
use ApiBundle\Event\GroupEvents;
use ApiBundle\Form\UserGroupType;
use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends FOSRestController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Rest\View(serializerGroups={"user_group"})
     * @Rest\Get("/fetch/")
     */
    public function getGroupsAction()
    {
        $groupService = $this->get('api.service.group_service');
        if ($this->getParameter('app.use_cache')) {
            $cacheItem = $this->get('app.cache')->getItem($groupService->getUserCacheKey(0));
            if ($cacheItem->isHit()) {
                return new Response($cacheItem->get());
            }
        }
        $groups = $this->get('api.user_group_repository')->findAll();
        $view = $this->view($groups, 200);
        $response = $this->handleView($view);
        if ($this->getParameter('app.use_cache')) {
            /**
             * @var $cacheItem CacheItem
             */
            $cacheItem->set($response->getContent())
                ->tag($groupService->getGroupCacheTags('fetch'))
                ->expiresAfter($this->getParameter('app.cache_ttl'));
            $this->get('app.cache')->save($cacheItem);
        }

        return $response;
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
            $this->get('event_dispatcher')->dispatch(GroupEvents::AFTER_GROUP_CREATE ,new GroupEvent($group));
            return $this->view($group, 200);
        }
        return $this->view($form, 400);
    }


    /**
     * @param UserGroup $user
     *
     * @return Response
     * @Rest\View(serializerGroups={"user_group"})
     * @Rest\Get("/{id}/", name="_info")
     */
    public function getGrouAction($id)
    {
        $groupService = $this->get('api.service.group_service');
        if ($this->getParameter('app.use_cache')) {
            $cacheItem = $this->get('app.cache')->getItem($groupService->getUserCacheKey($id));
            if ($cacheItem->isHit()) {
                return new Response($cacheItem->get());
            }
        }
        $group = $this->get('api.user_group_repository')->find($id);
        if (empty($group)) {
            return new JsonResponse(['message' => 'Group not found'], 404);
        }
        $view = $this->view($group, 200);
        $response = $this->handleView($view);
        if ($this->getParameter('app.use_cache')) {
            $cacheItem->set($response->getContent())
                ->expiresAfter($this->getParameter('app.cache_ttl'));
            $this->get('app.cache')->save($cacheItem);
        }

        return $response;
    }

    /**
     * @param UserGroup $group
     *
     * @return \FOS\RestBundle\View\View
     * @Rest\View(serializerGroups={"user_group"})
     * @Rest\Patch("/{id}/", name="_update")
     */
    public function postGroupAction(UserGroup $group)
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
            $this->get('event_dispatcher')->dispatch(GroupEvents::AFTER_GROUP_UPDATE ,new GroupEvent($group));
            return $this->view($group, 200);
        }
        return $this->view($form, 400);
    }
}