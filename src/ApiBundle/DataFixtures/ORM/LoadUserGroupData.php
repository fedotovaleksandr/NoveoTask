<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 22.01.2017
 * Time: 23:49
 */

namespace ApiBundle\DataFixtures\ORM;


use ApiBundle\Entity\User;
use ApiBundle\Entity\UserGroup;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    //count of attach some user to group
    private $count = LoadUserData::COUNT * 2;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < $this->count; $i++) {
            /**
             * @var $group UserGroup
             */
            $group = $this->getRandomGroup();
            /**
             * @var $user User
             */
            $user = $this->getRandomUser();
            $group->addUser($user);
            $manager->persist($group);
        }
        $manager->flush();
    }

    /**
     * @return User|object
     */
    private function getRandomUser()
    {
        $userPrefix = LoadUserData::PREFIX;
        $i = rand(0, LoadUserData::COUNT-1);
        return $this->getReference("{$userPrefix}-{$i}");
    }

    /**
     * @return UserGroup|object
     */
    private function getRandomGroup()
    {
        $groupPrefix = LoadGroupData::PREFIX;
        $i = rand(0, LoadGroupData::COUNT-1);
        return $this->getReference("{$groupPrefix}-{$i}");
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}