<?php
/**
 * Tests for `Container->get()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Container;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Container;
use WP_Syntex\Polylang_DI\Exception\InvalidArgumentException;
use WP_Syntex\Polylang_DI\Exception\NotFoundException;

/**
 * Tests for `Container->get()`.
 * Testing valid values is done when testing `Container->add()` and `Container->addShared()`.
 *
 * @group container
 */
class Get_Test extends TestCase {
	/**
	 * Test should throw an exception when the identifier is not a string.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenIdentifierIsNotAString() {
		$container = new Container();

		$this->expectException( InvalidArgumentException::class );

		$container->get( 42 );
	}

	/**
	 * Test should throw an exception when the identifier is unknown.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenIdentifierIsUnknown() {
		$container = new Container();

		$this->expectException( NotFoundException::class );

		$container->get( '42' );
	}
}
