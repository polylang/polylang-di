<?php
/**
 * Tests for `ClassDefinitionType->build()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Definition\ClassDefinitionType;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Argument\RawArgument;
use WP_Syntex\Polylang_DI\Container;
use WP_Syntex\Polylang_DI\Definition\ClassDefinitionType;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithTwoParams;

/**
 * Tests for `ClassDefinitionType->build()`.
 *
 * @group container
 */
class Build_Test extends TestCase {
	/**
	 * Test should build instance when args are passed to `build()`.
	 * Also use a reference to another item.
	 *
	 * @return void
	 */
	public function testShouldBuildInstanceWhenArgsPassedToBuild() {
		$container  = new Container();
		$int        = 15;
		$string     = 'baz';
		$definition = new ClassDefinitionType( 'foo', WithTwoParams::class );

		$container->add( 'lorem', $string );

		$result = $definition->build( $container, [ $int, 'lorem' ] );

		$this->assertInstanceOf( WithTwoParams::class, $result, 'Expected build() to return an instance of WithTwoParams.' );
		$this->assertSame( $int, $result->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $string, $result->string, 'Expected the instance property to be the same as the second argument.' );
	}

	/**
	 * Test should build instance when args are passed to the container.
	 * Also use RawArgument.
	 *
	 * @return void
	 */
	public function testShouldBuildInstanceWhenArgsPassedToContainer() {
		$container  = new Container();
		$int        = 15;
		$string     = 'baz';
		$rawArg     = new RawArgument( $string );
		$definition = ( new ClassDefinitionType( 'foo', WithTwoParams::class ) )->withNewArguments( [ $int, $rawArg ] );
		$result     = $definition->build( $container );

		$this->assertInstanceOf( WithTwoParams::class, $result, 'Expected build() to return an instance of WithTwoParams.' );
		$this->assertSame( $int, $result->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $string, $result->string, 'Expected the instance property to be the same as the second argument.' );
	}
}
