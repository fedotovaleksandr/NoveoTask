<?php
/**
 * Created by PhpStorm.
 * User: aleksandr
 * Date: 29.01.2017
 * Time: 8:18
 */

namespace ApiBundle\Service;


use ApiBundle\Entity\UserGroup;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class GroupService
{
    /**
     * @var TagAwareAdapterInterface
     */
    private $cache;

    /**
     * GroupService constructor.
     *
     * @param TagAwareAdapterInterface $cache
     */
    public function __construct(TagAwareAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function clearGroupCache(UserGroup $group)
    {
        $this->cache->deleteItem($this->getGroupCacheKey($group->getId()));
        $this->cache->invalidateTags($this->getGroupCacheTags());

    }


    public function getGroupCacheKey($groupId)
    {
        return "group_{$groupId}";
    }

    public function getGroupCacheTags($param=null)
    {
        $tags = ['fetch'=>'user_tag_fetch'];
        if (!$param || !isset($tags[$param])){
            return array_values($tags);
        }
        return $tags[$param];
    }
}