<?php


namespace App\Tests\DataFixtures\ORM;


use App\Entity\Todo;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTodoFixtures extends AbstractFixture
{

    public function load(ObjectManager $manager)
    {
        $todo = new Todo();
        $todo->setCompleted(false);
        $todo->setDescription('Hello World');
        $todo->setCreatedDate(new \DateTime('2018-01-01 00:00:00'));
        $manager->persist($todo);
        $this->setReference('todo.0', $todo);
        $manager->flush();

        $todo = new Todo();
        $todo->setCompleted(true);
        $todo->setDescription('Goodbye, old friend');
        $todo->setCreatedDate(new \DateTime('2018-02-01 00:00:00'));
        $manager->persist($todo);
        $this->setReference('todo.1', $todo);
        $manager->flush();
    }
}