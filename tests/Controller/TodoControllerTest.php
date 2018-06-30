<?php

namespace App\Tests\Controller;


use App\Tests\DataFixtures\ORM\LoadTodoFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    /** @var \Doctrine\Common\DataFixtures\ReferenceRepository */
    private $fixtures;
    public function setup(){
        $this->fixtures = $this->loadFixtures([LoadTodoFixtures::class])->getReferenceRepository();
    }

    public function testSomething()
    {
        $client = static::createClient();

        // Add some fixture data
        $client->request('GET', '/api/todo', ['Accept' => 'application/json']);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Hello World', $client->getResponse()->getContent());
    }
}
