# Reflection Mocker
<p align="center">
<a href="https://packagist.org/packages/reecem/mocker"><img src="https://poser.pugx.org/reecem/mocker/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/reecem/mocker"><img src="https://poser.pugx.org/reecem/mocker/license" alt="License"></a>
</p>

This package is initially made to fi an issue on the MailEclipse package, but improvements are welcome.
It currently is probably stupid simple, but deals with the one job of reading a file and mocking it.

> Generate a mocked instance of the un-typed params in a __construct() method

This searches the file retrieved from the reflection class and looks for all object like arrow calls;
ie: 

```php
...
public function __construct($objectArg, string $arg) 
{
    $this->value    = $objectArg->value; // this will be picked up
    $this->name     = $arg;
}
...
```

## Installation

You can install the package via composer:

```bash
composer require reecem/mocker
```
# Requirements 

- Laravel ^5.6 (min)

## Usage

```php

use ReeceM\ReflectionMockery;

/**
 * The class __construct Method is automatically read and args created
 */
$mock = new ReflectionMockery('\App\User');
// or
$mock = new ReflectionMockery(new \ReflectionClass('\App\User'));

// some time later

/**
 * Use call a variable from the class that don't exist
 */
{{ $mock->get('somethingNotInUser') }}

```
