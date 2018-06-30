<?php

namespace App\Tests\Form;

use App\Form\TodoType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TodoTypeTest extends KernelTestCase
{
    /**
     * @param $data
     * @param $valid
     * @dataProvider dataForTestForm
     */
    public function testForm($data, $valid)
    {
        $kernel = static::bootKernel();
        $container = $kernel->getContainer();
        $form = $container->get('form.factory')->create(TodoType::class, null, ['csrf_protection' => false]); // Disable csrf for test only
        $form->submit($data);
        $this->assertSame($valid, $form->isSubmitted() && $form->isValid());
    }

    public function dataForTestForm()
    {
        yield [['description' => 'Hello', 'completed' => false, ], true];
        yield [['description' => '', 'completed' => false, ], false];
        yield [['completed' => false, ], false];
        yield [['description' => \str_repeat('a', 266), 'completed' => false, ], false];
        yield [[], false];
        yield [['description' => 'Hello', 'completed' => false, 'extra' => 123, ], false];
    }
}
