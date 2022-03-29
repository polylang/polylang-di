<?php
/**
 * Tests for `AbstractDefinitionType->withArgument()`.
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

/**
 * Tests for `AbstractDefinitionType->withArgument()`.
 *
 * @group container
 */
class WithArgument_Test extends TestCase {
	/**
	 * Test should add an argument.
	 *
	 * @return void
	 */
	public function testShouldAddArgument() {
		$definition = new DummyDefinition( 'foo', WithTwoParams::class );
		$definition->withArgument( 42 );

		$arguments = $this->getPropertyValue( $definition, 'arguments' );

		$this->assertIsArray( $arguments, 'Expected the list of arguments to be an array.' );
		$this->assertSame( [ 42 ], $arguments, 'Expected the arguments to contain exactly what has been added by withArgument()' );

		$definition->withArgument( 'some string' );

		$arguments = $this->getPropertyValue( $definition, 'arguments' );

		$this->assertIsArray( $arguments, 'Expected the list of arguments to be an array.' );
		$this->assertSame( [ 42, 'some string' ], $arguments, 'Expected the arguments to contain exactly what has been added by withArgument()' );
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
