<?php
/**
 * Tests for `Container->extend()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Container;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Container;
use WP_Syntex\Polylang_DI\Definition\DefinitionInterface;
use WP_Syntex\Polylang_DI\Exception\InvalidArgumentException;
use WP_Syntex\Polylang_DI\Exception\NotFoundException;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithNoParams;

/**
 * Tests for `Container->extend()`.
 * Testing valid values is done when testing `Container->add()` and `Container->addShared()`.
 *
 * @group container
 */
class Extend_Test extends TestCase {
	/**
	 * Test should throw an exception when the identifier is not a string.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenIdentifierIsNotAString() {
		$container = new Container();

		$this->expectException( InvalidArgumentException::class );

		$container->extend( 42 );
	}

	/**
	 * Test should throw an exception when the identifier is unknown.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenIdentifierIsUnknown() {
		$container = new Container();

		$this->expectException( NotFoundException::class );

		$container->extend( '42' );
	}

	/**
	 * Test should throw an exception when the definition is already builded.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenDefinitionIsBuilded() {
		$container = new Container();

		$this->expectException( NotFoundException::class );

		$container->addShared( 'foo', 'some text' );
		$container->extend( 'foo' );
	}

	/**
	 * Test should return a definition when the identifier is known.
	 *
	 * @return void
	 */
	public function testShouldReturnDefinitionWhenIdentifierIsKnown() {
		$container = new Container();

		// Not shared.
		$container->add( 'foo', WithNoParams::class );
		$this->assertInstanceOf( DefinitionInterface::class, $container->extend( 'foo' ), 'Expected extend() to return an instance of DefinitionInterface for a known identifier.' );

		// Shared.
		$container->addShared( 'foo', WithNoParams::class );
		$this->assertInstanceOf( DefinitionInterface::class, $container->extend( 'foo' ), 'Expected extend() to return an instance of DefinitionInterface for a known shared identifier.' );
	}
}
