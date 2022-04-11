<?php
/**
 * Tests for `AbstractDefinitionType->withNewArguments()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Definition\AbstractDefinitionType;

use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use WP_Syntex\Polylang_DI\Definition\AbstractDefinitionType;
use WP_Syntex\Polylang_DI\Tests\Fixtures\DummyDefinition;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithTwoParams;
use Yoast\PHPUnitPolyfills\Polyfills\AssertIsType;

/**
 * Tests for `AbstractDefinitionType->withNewArguments()`.
 *
 * @group container
 */
class WithNewArguments_Test extends TestCase {
	use AssertIsType;

	/**
	 * Test should replace arguments.
	 *
	 * @return void
	 */
	public function testShouldReplaceArguments() {
		$definition = new DummyDefinition( 'foo', WithTwoParams::class );
		$definition->withNewArguments( [ 42, 'some string' ] );

		$arguments = $this->getPropertyValue( $definition, 'arguments' );

		$this->assertIsArray( $arguments, 'Expected the list of arguments to be an array.' );
		$this->assertSame( [ 42, 'some string' ], $arguments, 'Expected the arguments to contain exactly what has been added by withNewArguments()' );

		$definition->withNewArguments( [ 'another string' ] );

		$arguments = $this->getPropertyValue( $definition, 'arguments' );

		$this->assertIsArray( $arguments, 'Expected the list of arguments to be an array.' );
		$this->assertSame( [ 'another string' ], $arguments, 'Expected the arguments to contain exactly what has been added by withNewArguments()' );
	}

	/**
	 * Returns the value of an instance property.
	 *
	 * @param  object $objInstance  Instance object.
	 * @param  string $propertyName Property name.
	 * @return mixed
	 */
	private function getPropertyValue( $objInstance, $propertyName ) {
		$ref = new ReflectionProperty( $objInstance, $propertyName );
		$ref->setAccessible( true );

		return $ref->getValue( $objInstance );
	}
}
