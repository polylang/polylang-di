<?php
/**
 * Tests for `Container->add()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Container;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Container;
use WP_Syntex\Polylang_DI\Definition\DefinitionInterface;
use WP_Syntex\Polylang_DI\Exception\InvalidArgumentException;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithNoParams;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithTwoParams;

/**
 * Tests for `Container->add()`.
 *
 * @group container
 */
class Add_Test extends TestCase {
	/**
	 * Test should throw an exception when the identifier is not a string.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenIdentifierIsNotAString() {
		$container = new Container();

		$this->expectException( InvalidArgumentException::class );

		$container->add( 42, 42 );
	}

	/**
	 * Test should add a non-instance and non-closure type.
	 *
	 * @return void
	 */
	public function testShouldAddNonObjectAndNonClosureValue() {
		$container = new Container();
		$expected  = [ 'key' => 42 ];

		$definition = $container->add( 'array', $expected );
		$this->assertNull( $definition, 'Expected add() to return null for a non-instance and non-closure type.' );
		$this->assertSame( $expected, $container->get( 'array' ), 'Expected get() to return the value stored with add().' );
	}

	/**
	 * Test should not instanciate when adding (lazy-loading).
	 *
	 * @return void
	 */
	public function testShouldNotInstanciateWhenAdding() {
		$container = new Container();

		$this->expectOutputString( '' );

		$container->add( 'fixture', WithNoParams::class );
	}

	/**
	 * Test should instanciate when getting (lazy-loading).
	 *
	 * @return void
	 */
	public function testShouldInstanciateWhenGetting() {
		$container = new Container();

		$container->add( 'fixture', WithNoParams::class );

		$this->expectOutputString( WithNoParams::OUTPUT );

		$fixture = $container->get( 'fixture' );
		$this->assertInstanceOf( WithNoParams::class, $fixture, 'Expected get() to return an instance of WithNoParams.' );
	}

	/**
	 * Test should return a definition when storing a class instance or a closure.
	 *
	 * @return void
	 */
	public function testShouldReturnDefinitionWhenValueIsObjectOrClosure() {
		$container = new Container();

		$definition = $container->add( 'fixture', WithNoParams::class );
		$this->assertInstanceOf( DefinitionInterface::class, $definition, 'Expected add() to return an instance of DefinitionInterface for a class instance type.' );

		$definition = $container->add( 'fixture', function () {} );
		$this->assertInstanceOf( DefinitionInterface::class, $definition, 'Expected add() to return an instance of DefinitionInterface for a closure type.' );
	}

	/**
	 * Test should create non-shared instanciates.
	 *
	 * @return void
	 */
	public function testShouldCreateNonSharedInstances() {
		$container      = new Container();
		$expectedInt    = 42;
		$expectedString = 'A string';

		$container->add( 'fixture', WithTwoParams::class )
			->withArguments( [ $expectedInt, $expectedString ] );

		$fixture1 = $container->get( 'fixture' );
		$fixture2 = $container->get( 'fixture' );

		$this->assertInstanceOf( WithTwoParams::class, $fixture1, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixture2, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertNotSame( $fixture1, $fixture2, 'Expected the instances to be different.' );
		$this->assertSame( $expectedInt, $fixture1->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $expectedString, $fixture1->string, 'Expected the instance property to be the same as the second argument.' );
		$this->assertSame( $expectedInt, $fixture2->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $expectedString, $fixture2->string, 'Expected the instance property to be the same as the second argument.' );
	}

	/**
	 * Test should evaluate closures as non-shared items.
	 *
	 * @return void
	 */
	public function testShouldEvaluateNonSharedClosures() {
		$container      = new Container();
		$expectedInt    = 42;
		$expectedString = 'A string';

		$container->add(
			'fixture',
			function ( $container, $argInt, $argString ) use ( $expectedInt, $expectedString ) {
				$this->assertInstanceOf( Container::class, $container, 'Expected the closure\'s first argument to be an instance of Container.' );
				$this->assertSame( $expectedInt, $argInt, 'Expected the closure\'s second argument to be the same as the first argument added to the container.' );
				$this->assertSame( $expectedString, $argString, 'Expected the closure\'s third argument to be the same as the second argument added to the container.' );

				return new WithTwoParams( $argInt, $argString );
			}
		)->withArguments( [ $expectedInt, $expectedString ] );

		$fixture1 = $container->get( 'fixture' );
		$fixture2 = $container->get( 'fixture' );

		$this->assertInstanceOf( WithTwoParams::class, $fixture1, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixture2, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertNotSame( $fixture1, $fixture2, 'Expected the instances to be different.' );
		$this->assertSame( $expectedInt, $fixture1->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $expectedString, $fixture1->string, 'Expected the instance property to be the same as the second argument.' );
		$this->assertSame( $expectedInt, $fixture2->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $expectedString, $fixture2->string, 'Expected the instance property to be the same as the second argument.' );
	}

	/**
	 * Test should use previously added items as argument.
	 *
	 * @return void
	 */
	public function testShouldUsePreviouslyAddedItems() {
		$container      = new Container();
		$expectedInt    = 42;
		$expectedString = 'A string';

		$container->add( 'fixture-int', $expectedInt );
		$container->add( 'fixture-string', $expectedString );
		$container->add( 'fixture', WithTwoParams::class )
			->withArguments( [ 'fixture-int', 'fixture-string' ] );

		$fixture = $container->get( 'fixture' );

		$this->assertInstanceOf( WithTwoParams::class, $fixture, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertSame( $expectedInt, $fixture->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $expectedString, $fixture->string, 'Expected the instance property to be the same as the second argument.' );
	}
}
