<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserGroup
 *
 * @ORM\Table(name="user_group")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UserGroupRepository")
 */
class UserGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups",indexBy="id",cascade={"persist"})
     */
    private $users;

    /**
     * UserGroup constructor.
     *
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        if (!$user->getGroups()->contains($this)) {
            $user->addGroup($this);
        }
        return $this;
    }

    public function addUsers($users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param array $users
     *
     * @return $this
     */
    public function setUsers($users)
    {
        if (empty($groups)){
            return $this;
        }
        if (is_array($users)){
            $users = new ArrayCollection($users);
        }

        foreach($this->users as $id => $user) {
            if(!$users->contains($user)) {
                //remove from old because it doesn't exist in new
                $this->removeUser($user);
            }
            else {
                //the user already exists do not overwrite
                $users->removeElement($user);
            }
        }
        //add products that exist in new but not in old
        $this->addUsers($users);
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
        if ($user->getGroups()->contains($this)) {
            $user->removeGroup($this);
        }
        return $this;
    }

}

