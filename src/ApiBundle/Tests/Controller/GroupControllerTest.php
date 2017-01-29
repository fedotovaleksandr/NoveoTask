<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\WebTestCase;
use Doctrine\ORM\Query;

class GroupControllerTest extends WebTestCase
{
    /**
     * @group api
     */
    public function testFetchAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET',
            '/api/v1/groups/fetch',
            [],
            [],
            ['HTTP_Accept' => 'application/json']
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertGroupData(reset($data));
    }

    /**
     * @dataProvider groupApiProvider
     * @group        api
     */
    public function testCreateAction($groupData)
    {
        $client = static::createClient();

        $crawler = $client->request('POST',
            '/api/v1/groups/create',
            [],
            [],
            ['HTTP_Accept' => 'application/json', 'HTTP_Content-Type' => 'application/json'],
            json_encode($groupData, JSON_OBJECT_AS_ARRAY)
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertGroupData($data);
        $this->assertEquals(array_column($data['users'], 'id'), $groupData['users'], 'AssertGroups Set');
        $this->assertArrayHasKey('id', $data);
        $this->assertNotEmpty(self::$kernel->getContainer()->get('api.user_group_repository')->findOneBy(['id' => $data['id']]));

    }

    /**
     * @group api
     */
    public function testUserAction()
    {
        $client = static::createClient();
        $groups = $client->getContainer()->get('api.user_group_repository')->getRandomGroups(1);
        $group = reset($groups);
        $client->request('GET',
            "/api/v1/groups/{$group->getId()}/",
            [],
            [],
            ['HTTP_Accept' => 'application/json', 'HTTP_Content-Type' => 'application/json']
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertGroupData($data);
    }

    /**
     * @dataProvider groupApiProvider
     * @group        api
     */
    public function testUpdateAction($groupData)
    {
        $client = static::createClient();
        $groups = $client->getContainer()->get('api.user_group_repository')->getRandomGroups(1);
        $group = reset($groups);
        $crawler = $client->request('PATCH',
            "/api/v1/groups/{$group->getId()}/",
            [],
            [],
            ['HTTP_Accept' => 'application/json', 'HTTP_Content-Type' => 'application/json'],
            json_encode($groupData, JSON_FORCE_OBJECT)
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertGroupData($data);
        if (!empty($groupData['users'])) {
            $this->assertEquals(array_column($data['users'], 'id'), $groupData['users'], 'AssertUserss Set');
        } else {
            $this->assertEquals($data['users'], [], 'AssertGroups Set');
        }
        $this->assertArrayHasKey('id', $data);
        $this->assertNotEmpty($client->getContainer()->get('api.user_group_repository')->findOneBy(['id' => $data['id']]));
    }

    /**
     * Provider
     *
     * @return array
     */
    public function groupApiProvider()
    {
        $users = static::createClient()->getContainer()->get('api.user_repository')->getRandomUsers(2, Query::HYDRATE_ARRAY);
        return [
            [
                [
                    'name' => 'user_test_group_1',
                    'users' => [$users[0]['id'], $users[1]['id']]
                ]
            ],
            [
                [
                    'name' => 'user_test_group_2',
                    'users' => [$users[0]['id']]
                ]
            ],
            [
                [
                    'name' => 'user_test_group_3',
                    'users' => []
                ],
            ]
        ];

    }

    private function assertGroupData(array $data)
    {
        $fields = ['id' => 'integer',
            'name' => 'string',
            'users' => 'array'];
        $this->assertArrayHasKeys(array_keys($fields),
            $data, 'Assert user data have all fields');

        foreach ($fields as $field => $type) {
            if (!empty($data[$field])) {
                $this->assertInternalType($type, $data[$field], "Assert group.'{$field}' type of {$type}");
            }
        }

    }
}
