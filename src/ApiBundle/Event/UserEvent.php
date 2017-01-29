<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 8:07
 */

namespace ApiBundle\Event;


use ApiBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

}