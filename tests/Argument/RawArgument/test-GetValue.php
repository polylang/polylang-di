<?php
/**
 * Tests for `RawArgument->getValue()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Argument\RawArgument;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Argument\RawArgument;

/**
 * Tests for `RawArgument->getValue()`.
 *
 * @group container
 */
class GetValue_Test extends TestCase {
	/**
	 * Test should return the same value injected in the constructor.
	 *
	 * @dataProvider dataProvider
	 *
	 * @param  mixed $value The value.
	 * @return void
	 */
	public function testShouldReturnValue( $value ) {
		$this->assertSame( $value, ( new RawArgument( $value ) )->getValue(), 'Expected getValue() to return the same value injected in the constructor.' );
	}

	/**
	 * Data provider.
	 *
	 * @return array<array<mixed>>
	 */
	public function dataProvider() {
		return [
			'a number'  => [
				42,
			],
			'a string'  => [
				'some string',
			],
			'an array'  => [
				[ 'foo' ],
			],
			'an object' => [
				(object) [ 'bar' ],
			],
			'a closure' => [
				function () {},
			],
		];
	}
}
