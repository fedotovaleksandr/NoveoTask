<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 22.01.2017
 * Time: 23:45
 */

namespace ApiBundle\DataFixtures\ORM;


use ApiBundle\Entity\UserGroup;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class LoadGroupData
 * @package ApiBundle\DataFixtures\ORM
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{

    //Data for load
    /**
     *
     */
    private $names = ['BestPeople', 'Managers', 'Developers', 'Cooks', 'Taxis', 'Sportsmens', 'Cats'];
    /**
     *
     */
    const COUNT = 10;
    /**
     *
     */
    const PREFIX = 'group';


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $prefix = self::PREFIX;
        for ($i = 0; $i < self::COUNT; $i++) {
            $group = new UserGroup();
            $group->setName($this->genName());
            $manager->persist($group);
            $this->addReference("{$prefix}-{$i}", $group);
        }
        $manager->flush();
    }


    /**
     * @return mixed
     */
    private function genName()
    {
        return $this->names[rand(0, count($this->names)-1)];
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}