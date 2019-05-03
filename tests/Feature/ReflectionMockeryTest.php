<?php

namespace Tests\Unit;

use Tests\TestCase;
use ReeceM\Mocker\ReflectionMockery;

class ReflectionMockeryTest extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function can_make_reflection_test()
    {
        $mock = new ReflectionMockery(new \ReflectionClass('Tests\ClassOfTest'));

        $class = new \ReflectionClass('Tests\ClassOfTest');

        $instance = $class->newInstanceArgs($mock->all());
        // run the invoke function to use the instantiated data
        $this->assertSame('data->complex->var->that->is->set->too => Hello World', $instance());
    }
}
