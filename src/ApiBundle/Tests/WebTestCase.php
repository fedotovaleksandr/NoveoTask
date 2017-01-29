<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 21:05
 */

namespace ApiBundle\Tests;


class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    /**
     * @param array $needles
     * @param array $haystack
     * @param string $message
     */
    protected function assertArrayHasKeys(array $needles, array $haystack, $message = '')
    {
        foreach ($needles as $needle) {
            $this->assertArrayHasKey($needle, $haystack, $message);
        }
    }

    /**
     * @param string $expected
     * @param array $haystack
     * @param string $message
     */
    protected function assertArrayInternalType($expected, array $haystack, $message = '')
    {
        foreach ($haystack as $item) {
            $this->assertInternalType($expected, $item, $message);
        }
    }
}