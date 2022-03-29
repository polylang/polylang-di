<?php
/**
 * Tests for `CallableDefinitionType->build()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Definition\CallableDefinitionType;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Argument\RawArgument;
use WP_Syntex\Polylang_DI\Container;
use WP_Syntex\Polylang_DI\Definition\CallableDefinitionType;

/**
 * Tests for `CallableDefinitionType->build()`.
 *
 * @group container
 */
class Build_Test extends TestCase {
	/**
	 * Test should build closure when args are passed to `build()`.
	 * Also use a reference to another item.
	 *
	 * @return void
	 */
	public function testShouldBuildClosureWhenArgsPassedToBuild() {
		$container  = new Container();
		$string1    = 'bar';
		$string2    = 'baz';
		$closure    = function ( $container, $arg1 = null, $arg2 = null ) use ( $string1, $string2 ) {
			$this->assertInstanceOf( Container::class, $container, 'Expected the closure\'s first argument to be an instance of Container.' );
			$this->assertSame( $string1, $arg1, 'Expected the closure\'s second argument to be the same as the first argument added to the container.' );
			$this->assertSame( $string2, $arg2, 'Expected the closure\'s third argument to be the same as the second argument added to the container.' );

			return "$arg1 / $arg2";
		};
		$definition = new CallableDefinitionType( 'foo', $closure );

		$container->add( 'lorem', $string2 );

		$result = $definition->build( $container, [ $string1, 'lorem' ] );

		$this->assertSame( "$string1 / $string2", $result, 'Expected build() to return the closure\'s result.' );
	}

	/**
	 * Test should build closure when args are passed to the container.
	 * Also use RawArgument.
	 *
	 * @return void
	 */
	public function testShouldBuildClosureWhenArgsPassedToContainer() {
		$container  = new Container();
		$string1    = 'bar';
		$string2    = 'foo';
		$rawArg     = new RawArgument( $string2 );
		$closure    = function ( $container, $arg1 = null, $arg2 = null ) use ( $string1, $string2 ) {
			$this->assertInstanceOf( Container::class, $container, 'Expected the closure\'s first argument to be an instance of Container.' );
			$this->assertSame( $string1, $arg1, 'Expected the closure\'s second argument to be the same as the first argument added to the container.' );
			$this->assertSame( $string2, $arg2, 'Expected the closure\'s third argument to be the same as the second argument added to the container.' );

			return "$arg1 / $arg2";
		};
		$definition = ( new CallableDefinitionType( 'foo', $closure ) )->withNewArguments( [ $string1, $rawArg ] );
		$result     = $definition->build( $container );

		$this->assertSame( "$string1 / $string2", $result, 'Expected build() to return the closure\'s result.' );
	}
}
