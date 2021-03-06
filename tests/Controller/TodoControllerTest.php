<?php

namespace App\Tests\Controller;


use App\Tests\DataFixtures\ORM\LoadTodoFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    /** @var \Doctrine\Common\DataFixtures\ReferenceRepository */
    private $fixtures;

    public function setup()
    {
        $this->fixtures = $this->loadFixtures([LoadTodoFixtures::class])->getReferenceRepository();
    }

    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/api/todo', ['Accept' => 'application/json']);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $data = \json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals([
            [
                'id' => 1,
                'description' => 'Hello World',
                'completed' => false,
                'createdDate' => '2018-01-01T00:00:00+00:00',
                'dueDate' => '2019-01-01',
            ],
            [
                'id' => 2,
                'description' => 'Goodbye, old friend',
                'completed' => true,
                'createdDate' => '2018-02-01T00:00:00+00:00',
            ],
        ], $data);
    }

    public function testCreate()
    {
        $client = static::createClient();

        $client->request('POST', '/api/todo', ['todo' => ['description' => 'Testing']]);
        $this->assertContains('"description":"Testing"', $client->getResponse()->getContent());
        $this->assertSame(201, $client->getResponse()->getStatusCode());
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $client->request('PATCH', '/api/todo/' . $this->fixtures->getReference('todo.0')->getId(), ['todo' => ['completed' => true, ]]);
        $this->assertContains('"description":"Hello World","completed":true', $client->getResponse()->getContent());
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/todo/' . $this->fixtures->getReference('todo.0')->getId());
        $this->assertSame(204, $client->getResponse()->getStatusCode());
    }
}
