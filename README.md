# Polylang DI

![php](https://badgen.net/packagist/php/wpsyntex/polylang-di)
[![Latest Version](https://badgen.net/packagist/v/wpsyntex/polylang-di/latest)](https://packagist.org/packages/wpsyntex/polylang-di)
[![Software License](https://badgen.net/github/license/polylang/polylang-di)](LICENSE.md)

Dependency Injection Container by WP Syntex (Polylang).

This package is compliant with [PSR-4](https://www.php-fig.org/psr/psr-4/) and [PSR-11](https://www.php-fig.org/psr/psr-11/).

## Install

Via Composer

``` bash
composer require wpsyntex/polylang-di
```

## Requirements

This package supports php 5.6+.

## Documentation

### Create and retrieve shared and not shared instances

```php
<?php
namespace Foobar;

class Foo {}

$container = new \WP_Syntex\Polylang_DI\Container();

// Add to the container.
$container->add( 'foo', 'Foobar\Foo' );

// Instanciate and retrieve.
$foo1 = $container->get( 'foo' );
$foo2 = $container->get( 'foo' );

// We created 2 different instances.
var_dump( $foo1 === $foo2 ); // False.

// Add to the container as a shared item.
$container->addShared( 'foo', 'Foobar\Foo' );

// Instanciate and retrieve.
$foo1 = $container->get( 'foo' );
$foo2 = $container->get( 'foo' );

// We created only one instance.
var_dump( $foo1 === $foo2 ); // True.
```

### Add arguments

```php
<?php
namespace Foobar;

class Foo {
    public $int;
    public $string;

    public function __construct( $int, $string = null ) {
        $this->int    = $int;
        $this->string = $string;
    }
}

$container = new \WP_Syntex\Polylang_DI\Container();

// Add to the container.
$container->add( 'foo', Foo::class )
    ->withArgument( 46 );

// Instanciate and retrieve.
$foo1 = $container->get( 'foo' );
$foo2 = $container->get( 'foo' );

// We created 2 different instances with the same arguments.
var_dump( $foo1 === $foo2 ); // False.
var_dump( $foo1->int ); // 46.
var_dump( $foo1->string ); // Null.
var_dump( $foo1->int === $foo2->int ); // True.
var_dump( $foo1->string === $foo2->string ); // True.

// Retrieve the definition and add an argument.
$container->extend( 'foo' )
    ->withArgument( 'a string' ); // Not not shared items only.

// Instanciate and retrieve.
$foo3 = $container->get( 'foo' );

var_dump( $foo3->int ); // 46.
var_dump( $foo3->string ); // 'a string'.

// Retrieve the definition and replace previous arguments.
$container->extend( 'foo' )
    ->newArguments(
        [
            22,
            'some string',
        ]
    );

$foo4 = $container->get( 'foo' );

var_dump( $foo4->int ); // 22.
var_dump( $foo4->string ); // 'some string'.
```

### Reference other items (or not)

```php
<?php
namespace Foobar;

class Foo {}

class Bar {
    public $foo;
    public $string;

    public function __construct( Foo $foo, $string = null ) {
        $this->foo    = $foo;
        $this->string = $string;
    }
}

$container = new \WP_Syntex\Polylang_DI\Container();

// Add to the container.
$container->addShared( 'foo', Foo::class );
$container->add( 'bar', Bar::class )
    ->withArgument( 'foo' ) // Same identifier used for Foobar\Foo.
    ->withArgument(
        new \WP_Syntex\Polylang_DI\Argument\RawArgument( 'foo' ) // Same identifier used for Foobar\Foo, but we just want to pass a string here.
    );

// Instanciate and retrieve.
$bar1 = $container->get( 'bar' );
$bar2 = $container->get( 'bar' );

// We created 2 different instances with the same arguments.
var_dump( $bar1 === $bar2 ); // False.
var_dump( $bar1->foo instanceof Foo ); // True.
var_dump( $bar1->foo === $bar2->foo ); // True.
var_dump( $bar1->string ); // 'foo'.
```

Inspired by [league/container](https://github.com/thephpleague/container).
