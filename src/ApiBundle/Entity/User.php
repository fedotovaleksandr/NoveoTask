<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var bool
     *
     * @ORM\Column(name="state", type="boolean" , options={"default" : false})
     */
    private $state = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users", indexBy="id",cascade={"persist"})
     * @ORM\JoinTable(name="users_groups")
     */
    private $groups;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->creationDate = new \DateTime();
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return bool
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return []UserGroups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    public function addGroup(UserGroup $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
        if (!$group->getUsers()->contains($this)) {
            $group->addUser($this);
        }
        return $this;
    }

    public function removeGroup(UserGroup $group)
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }
        if ($group->getUsers()->contains($this)) {
            $group->removeUser($this);
        }
        return $this;
    }

    public function addGroups($groups)
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }
        return $this;
    }

    /**
     * @param ArrayCollection $groups
     *
     * @return $this
     */
    public function setGroups($groups)
    {
        if (empty($groups)){
            return $this;
        }
        if (is_array($groups)){
            $groups = new ArrayCollection($groups);
        }
        foreach($this->groups as $id => $group) {
            if(!$groups->contains($group)) {
                //remove from old because it doesn't exist in new
                $this->removeGroup($group);
            }
            else {
                //the group already exists do not overwrite
                $groups->removeElement($group);
            }
        }
        //add products that exist in new but not in old
        $this->addGroups($groups);
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        //assign bi directional
        if (empty($this->creationDate)){
            $this->creationDate = new \DateTime("now");
        }
    }

    public function toApiFormat(){}
}

