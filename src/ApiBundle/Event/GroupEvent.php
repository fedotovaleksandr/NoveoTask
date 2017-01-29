<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 8:10
 */

namespace ApiBundle\Event;


use ApiBundle\Entity\UserGroup;

class GroupEvent
{
    /**
     * @var UserGroup
     */
    private $group;


    /**
     * GroupEvent constructor.
     *
     * @param UserGroup $group
     */
    public function __construct(UserGroup $group)
    {
        $this->group = $group;
    }

    /**
     * @return UserGroup
     */
    public function getGroup(): UserGroup
    {
        return $this->group;
    }


}