<?php

namespace ApiBundle\Tests\Controller;


use ApiBundle\Tests\WebTestCase;
use Doctrine\ORM\Query;

class UserControllerTest extends WebTestCase
{
    /**
     * @group api
     */
    public function testFetchAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET',
            '/api/v1/users/fetch',
            [],
            [],
            ['HTTP_Accept' => 'application/json']
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertUserData(reset($data));
    }

    /**
     * @dataProvider userApiProvider
     * @group        api
     */
    public function testCreateAction($userData)
    {
        $client = static::createClient();

        $crawler = $client->request('POST',
            '/api/v1/users/create',
            [],
            [],
            ['HTTP_Accept' => 'application/json', 'HTTP_Content-Type' => 'application/json'],
            json_encode($userData, JSON_OBJECT_AS_ARRAY)
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertUserData($data);
        $this->assertEquals(array_column($data['groups'], 'id'), $userData['groups'], 'AssertGroups Set');
        $this->assertArrayHasKey('id', $data);
        $this->assertNotEmpty(self::$kernel->getContainer()->get('api.user_repository')->findOneBy(['id' => $data['id']]));

    }

    /**
     * @group api
     */
    public function testUserAction()
    {
        $client = static::createClient();
        $users = $client->getContainer()->get('api.user_repository')->getRandomUsers(1);
        $user = reset($users);
        $crawler = $client->request('GET',
            "/api/v1/users/{$user->getId()}/",
            [],
            [],
            ['HTTP_Accept' => 'application/json', 'HTTP_Content-Type' => 'application/json']
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertUserData($data);
        self::$kernel->getContainer()->get('api.user_repository');
    }

    /**
     * @dataProvider userApiProvider
     * @group        api
     */
    public function testUpdateAction($userData)
    {
        $client = static::createClient();
        $users = $client->getContainer()->get('api.user_repository')->getRandomUsers(1);
        $user = reset($users);
        $crawler = $client->request('PATCH',
            "/api/v1/users/{$user->getId()}/",
            [],
            [],
            ['HTTP_Accept' => 'application/json', 'HTTP_Content-Type' => 'application/json'],
            json_encode($userData, JSON_FORCE_OBJECT)
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($client->getResponse()->getStatusCode(), 200, 'Assert status code');
        $this->assertUserData($data);
        if (!empty($userData['groups'])) {
            $this->assertEquals(array_column($data['groups'], 'id'), $userData['groups'], 'AssertGroups Set');
        } else {
            $this->assertEquals($data['groups'], [], 'AssertGroups Set');
        }
        $this->assertArrayHasKey('id', $data);
        $this->assertNotEmpty($client->getContainer()->get('api.user_repository')->findOneBy(['id' => $data['id']]));
    }

    /**
     * Provider
     *
     * @return array
     */
    public function userApiProvider()
    {
        $groups = static::createClient()->getContainer()->get('api.user_group_repository')->getRandomGroups(2, Query::HYDRATE_ARRAY);
        return [
            [
                [
                    'email' => 'test@mail.test',
                    'firstName' => 'tester',
                    'lastName' => 'testovich',
                    'state' => false,
                    'creationDate' => (new \DateTime())->format(\DateTime::RFC3339),
                    'groups' => [$groups[0]['id'], $groups[1]['id']]
                ]
            ],
            [
                [
                    'email' => 'test@mail.test',
                    'firstName' => 'tester',
                    'lastName' => 'testovich',
                    'state' => false,
                    'creationDate' => (new \DateTime())->format(\DateTime::RFC3339),
                    'groups' => [$groups[0]['id']
                    ]
                ]
            ],
            [
                [
                    'email' => 'test@mail.test',
                    'firstName' => 'tester',
                    'lastName' => 'testovich',
                    'state' => false,
                    'creationDate' => (new \DateTime())->format(\DateTime::RFC3339),
                    'groups' => []
                ]
            ]
        ];

    }

    private function assertUserData(array $data)
    {
        $fields = ['id' => 'integer',
            'email' => 'string',
            'firstName' => 'string',
            'lastName' => 'string',
            'state' => 'boolean',
            'creationDate' => 'string',
            'groups' => 'array'];
        $this->assertArrayHasKeys(array_keys($fields),
            $data, 'Assert user data have all fields');

        foreach ($fields as $field => $type) {
            if (!empty($data[$field])) {
                $this->assertInternalType($type, $data[$field], "Assert user.'{$field}' type of {$type}");
            }
        }

    }
}
