<?php

namespace ApiBundle\Service;

use ApiBundle\Entity\User;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 8:17
 */
class UserService
{
    /**
     * @var TagAwareAdapterInterface
     */
    private $cache;

    /**
     * UserService constructor.
     *
     * @param TagAwareAdapterInterface $cache
     */
    public function __construct(TagAwareAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function clearUserCache(User $user)
    {
       $this->cache->deleteItem($this->getUserCacheKey($user->getId()));
       $this->cache->invalidateTags($this->getUserCacheTags());

    }


    public function getUserCacheKey($userId)
    {
        return "user_{$userId}";
    }

    public function getUserCacheTags($param=null)
    {
        $tags = ['fetch'=>'user_tag_fetch'];
        if (!$param || !isset($tags[$param])){
            return array_values($tags);
        }
        return $tags[$param];
    }
}