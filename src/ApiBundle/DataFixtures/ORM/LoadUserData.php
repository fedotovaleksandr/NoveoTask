<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 22.01.2017
 * Time: 23:16
 */

namespace ApiBundle\DataFixtures\ORM;


use ApiBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @package ApiBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    //Data for load
    /**
     *
     */
    private $names = ['Ivan', 'Alex', 'Igor', 'Masha', 'Nastya', 'Zina', 'Boris'];
    /**
     *
     */
    private $lastNames = ['Ivanov', 'Putin', 'Trump', 'Neiron', 'BestOfTheBest', 'WakaFloka', 'Master'];
    /**
     *
     */
    const COUNT = 100;
    /**
     *
     */
    const PREFIX = 'user';


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $prefix = self::PREFIX;
        for ($i = 0; $i < self::COUNT; $i++) {
            $user = new User();
            $user->setFirstName($this->genName())
                ->setLastName($this->genLastName())
                ->setEmail($this->genEmail())
                ->setState(boolval(rand(0, 1)));
            $manager->persist($user);
            $this->addReference("{$prefix}-{$i}", $user);
        }
        $manager->flush();
    }

    /**
     * @return string
     */
    private function genEmail()
    {
        $first = $this->genRandomString(6);
        $second = $this->genRandomString(3);
        $third = $this->genRandomString(3);
        return mb_strtolower(
            preg_replace('/[^a-z@\.]/i', '', "{$first}@{$second}.{$third}")
        );

    }

    /**
     * @param $length
     *
     * @return string
     */
    private function genRandomString($length)
    {
        return base64_encode(random_bytes($length));
    }

    /**
     * @return mixed
     */
    private function genName()
    {
        return $this->names[rand(0, count($this->names) - 1)];
    }

    /**
     * @return mixed
     */
    private function genLastName()
    {
        return $this->names[rand(0, count($this->lastNames) - 1)];
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}