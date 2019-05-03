<?php

namespace App;

require_once __DIR__ . '/vendor/autoload.php';

use ReeceM\Mocker\ReflectionMockery;

$mock = new ReflectionMockery(new \ReflectionClass('ReeceM\Mocker\ClassOfTest'));
// $mock->complex->var->that->is->set->too = "Hello World";

$class = new \ReflectionClass('ReeceM\Mocker\ClassOfTest');

$instance = $class->newInstanceArgs($mock->all());

dd($instance());
// use ReeceM\Mocker\Mocked;
// use ReeceM\Mocker\Utils\VarStore;

// $mocked = new Mocked('user', VarStore::singleton());
// $mocked->name->class = 'Chips';

// echo $mocked->name->class . PHP_EOL;

// echo $mocked->name->class->data . PHP_EOL;

die(1);
