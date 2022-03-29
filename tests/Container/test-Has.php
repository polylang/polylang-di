<?php
/**
 * Tests for `Container->has()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Container;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Container;
use WP_Syntex\Polylang_DI\Exception\InvalidArgumentException;

/**
 * Tests for `Container->has()`.
 *
 * @group container
 */
class Has_Test extends TestCase {
	/**
	 * Test should throw an exception when the identifier is not a string.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenIdentifierIsNotAString() {
		$container = new Container();

		$this->expectException( InvalidArgumentException::class );

		$container->has( 42 );
	}

	/**
	 * Test should return false when the identifier is unknown.
	 *
	 * @return void
	 */
	public function testShouldReturnFalseWhenIdentifierIsUnknown() {
		$container = new Container();

		$this->assertFalse( $container->has( '42' ), 'Expected has() to return false for an unknown identifier.' );
	}

	/**
	 * Test should return true when the identifier is known.
	 *
	 * @return void
	 */
	public function testShouldReturnTrueWhenIdentifierIsKnown() {
		$container = new Container();
		$container->add( 'foo', 'Foo' );

		$this->assertTrue( $container->has( 'foo' ), 'Expected has() to return true for a known identifier.' );
	}
}
