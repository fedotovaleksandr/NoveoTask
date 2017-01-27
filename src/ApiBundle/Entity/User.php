<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UserRepository")
 * @ExclusionPolicy("all")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Expose
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Expose
     */
    private $firstName;

    /**
     * @var bool
     *
     * @ORM\Column(name="state", type="boolean" , options={"default" : false})
     * @Expose
     */
    private $state=false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     * @Expose
     */
    private $creationDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users",cascade={"persist"})
     * @ORM\JoinTable(name="users_groups")
     * @Expose
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

    public function addToGroup(UserGroup $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
        if (!$group->getUsers()->contains($this)) {
            $group->addUser($this);
        }
        return $this;
    }

}

