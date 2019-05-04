<?php

namespace Tests\Unit;

use Tests\TestCase;
use ReeceM\Mocker\Mocked;
use ReeceM\Mocker\Utils\VarStore;

class MockedTest extends TestCase
{
    /**
     * Test if values are set in a mocked class
     * @test
     * @return void
     */
    public function mocked_returns_set_value_test()
    {
        $mocked = new Mocked('user', VarStore::singleton());
        // what would be set in a class when using Mocked::class
        $mocked->name->class = 'test';

        $this->assertContains('=> ["test"]', (string)$mocked->name->class);
        $this->assertSame('user->name->class => ["test"]', (string)$mocked->name->class);
        $this->assertNotSame('user->name->class => ["test"]', (string)$mocked->name->class->data);
    }
    /**
     * Test if the un-set mocked returns the calls chained
     * @test
     * @return void
     */
    public function mocked_returns_chained_only_test()
    {
        $mocked = new Mocked('user', VarStore::singleton());

        $this->assertSame('user', (string)$mocked);
        $this->assertSame('user->name', (string)$mocked->name);
        // singleton persists
        $this->assertNotSame('user->name->class', (string)$mocked->name->class);
        $this->assertSame('user->name->class->data', (string)$mocked->name->class->data);
    }

}