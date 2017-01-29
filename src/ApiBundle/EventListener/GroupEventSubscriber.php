<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 8:11
 */

namespace ApiBundle\EventListener;


use ApiBundle\Event\GroupEvent;
use ApiBundle\Event\GroupEvents;
use ApiBundle\Service\GroupService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GroupEventSubscriber implements  EventSubscriberInterface
{
    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * GroupEventSubscriber constructor.
     *
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
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
            GroupEvents::AFTER_GROUP_CREATE=>'onGroupCreate',
            GroupEvents::AFTER_GROUP_UPDATE=>'onGroupUpdate',
        ];

    }

    public function onGroupCreate(GroupEvent $event)
    {
        $this->groupService->clearGroupCache($event->getGroup());
    }

    public function onGroupUpdate(GroupEvent $event)
    {
        $this->groupService->clearGroupCache($event->getGroup());
    }
}