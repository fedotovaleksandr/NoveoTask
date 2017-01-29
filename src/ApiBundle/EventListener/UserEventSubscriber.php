<?php

namespace ApiBundle\EventListener;
use ApiBundle\Event\UserEvent;
use ApiBundle\Event\UserEvents;
use ApiBundle\Service\UserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 8:01
 */
class UserEventSubscriber implements  EventSubscriberInterface
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserEventSubscriber constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {

        return [
            UserEvents::AFTER_USER_CREATE=>'onUserCreate',
            UserEvents::AFTER_USER_CREATE=>'onUserUpdate',
        ];

    }

    public function onUserCreate(UserEvent $event)
    {
        $this->userService->clearUserCache($event->getUser());
    }

    public function onUserUpdate(UserEvent $event)
    {
        $this->userService->clearUserCache($event->getUser());
    }
}