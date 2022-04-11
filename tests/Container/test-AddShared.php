<?php
/**
 * Tests for `Container->addShared()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Container;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Container;
use WP_Syntex\Polylang_DI\Definition\DefinitionInterface;
use WP_Syntex\Polylang_DI\Exception\InvalidArgumentException;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithAnInstance;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithNoParams;
use WP_Syntex\Polylang_DI\Tests\Fixtures\WithTwoParams;

/**
 * Tests for `Container->addShared()`.
 *
 * @group container
 */
class AddShared_Test extends TestCase {
	/**
	 * Test should throw an exception when the identifier is not a string.
	 *
	 * @return void
	 */
	public function testShouldThrowErrorWhenIdentifierIsNotAString() {
		$container = new Container();

		$this->expectException( InvalidArgumentException::class );

		$container->addShared( 42, 42 );
	}

	/**
	 * Test should add a non-instance and non-closure type.
	 *
	 * @return void
	 */
	public function testShouldAddNonObjectAndNonClosureValue() {
		$container = new Container();
		$expected  = [ 'key' => 42 ];

		$definition = $container->addShared( 'array', $expected );
		$this->assertNull( $definition, 'Expected addShared() to return null for a non-instance and non-closure type.' );
		$this->assertSame( $expected, $container->get( 'array' ), 'Expected get() to return the value stored with addShared().' );
	}

	/**
	 * Test should not instanciate when adding (lazy-loading).
	 *
	 * @return void
	 */
	public function testShouldNotInstanciateWhenAdding() {
		$container = new Container();

		$this->expectOutputString( '' );

		$container->addShared( 'fixture', WithNoParams::class );
	}

	/**
	 * Test should instanciate when getting (lazy-loading).
	 *
	 * @return void
	 */
	public function testShouldInstanciateWhenGetting() {
		$container = new Container();

		$container->addShared( 'fixture', WithNoParams::class );

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

		$definition = $container->addShared( 'fixture', WithNoParams::class );
		$this->assertInstanceOf( DefinitionInterface::class, $definition, 'Expected addShared() to return an instance of DefinitionInterface for a class instance type.' );

		$definition = $container->addShared( 'fixture', function () {} );
		$this->assertInstanceOf( DefinitionInterface::class, $definition, 'Expected addShared() to return an instance of DefinitionInterface for a closure type.' );
	}

	/**
	 * Test should create shared instanciates.
	 *
	 * @return void
	 */
	public function testShouldCreateSharedInstances() {
		$container      = new Container();
		$expectedInt    = 42;
		$expectedString = 'A string';

		$container->addShared( 'fixture', WithTwoParams::class )
			->withArguments( [ $expectedInt, $expectedString ] );

		$fixture1 = $container->get( 'fixture' );
		$fixture2 = $container->get( 'fixture' );

		$this->assertInstanceOf( WithTwoParams::class, $fixture1, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertSame( $fixture1, $fixture2, 'Expected the instances to be identical.' );
		$this->assertSame( $expectedInt, $fixture1->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $expectedString, $fixture1->string, 'Expected the instance property to be the same as the second argument.' );
	}

	/**
	 * Test should evaluate closures as shared items.
	 *
	 * @return void
	 */
	public function testShouldEvaluateSharedClosures() {
		$container      = new Container();
		$expectedInt    = 42;
		$expectedString = 'A string';

		$container->addShared(
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
		$this->assertSame( $fixture1, $fixture2, 'Expected the instances to be identical.' );
		$this->assertSame( $expectedInt, $fixture1->int, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $expectedString, $fixture1->string, 'Expected the instance property to be the same as the second argument.' );
	}

	/**
	 * Test should use previously added items as argument.
	 *
	 * @return void
	 */
	public function testShouldUsePreviouslyAddedItems() {
		$container = new Container();

		// Fixture not shared, fixture-inst shared.
		$container->add( 'fixture', WithAnInstance::class )
			->withArgument( 'fixture-inst' );
		$container->addShared( 'fixture-inst', WithTwoParams::class )
			->withArgument( 42 );

		$fixture1    = $container->get( 'fixture' );
		$fixture2    = $container->get( 'fixture' );
		$fixtureInst = $container->get( 'fixture-inst' );

		$this->assertInstanceOf( WithAnInstance::class, $fixture1, 'Expected get() to return an instance of WithAnInstance.' );
		$this->assertInstanceOf( WithAnInstance::class, $fixture2, 'Expected get() to return an instance of WithAnInstance.' );
		$this->assertNotSame( $fixture1, $fixture2, 'Expected the instances of WithAnInstance to be different.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixtureInst, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixture1->inst, 'Expected the instance property to be an instance of WithTwoParams.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixture2->inst, 'Expected the instance property to be an instance of WithTwoParams.' );
		$this->assertSame( $fixtureInst, $fixture1->inst, 'Expected the instance property to be the same as the first argument.' );
		$this->assertSame( $fixtureInst, $fixture2->inst, 'Expected the instance property to be the same as the first argument.' );

		// Fixture shared, fixture-inst not shared.
		$container->addShared( 'fixture', WithAnInstance::class )
			->withArgument( 'fixture-inst' );
		$container->add( 'fixture-inst', WithTwoParams::class )
			->withArgument( 42 );

		$fixture1    = $container->get( 'fixture' );
		$fixture2    = $container->get( 'fixture' );
		$fixtureInst = $container->get( 'fixture-inst' );

		$this->assertInstanceOf( WithAnInstance::class, $fixture1, 'Expected get() to return an instance of WithAnInstance.' );
		$this->assertInstanceOf( WithAnInstance::class, $fixture2, 'Expected get() to return an instance of WithAnInstance.' );
		$this->assertSame( $fixture1, $fixture2, 'Expected the instances of WithAnInstance to be identical.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixtureInst, 'Expected get() to return an instance of WithTwoParams.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixture1->inst, 'Expected the instance property to be an instance of WithTwoParams.' );
		$this->assertInstanceOf( WithTwoParams::class, $fixture2->inst, 'Expected the instance property to be an instance of WithTwoParams.' );
		$this->assertNotSame( $fixtureInst, $fixture1->inst, 'Expected the instance property to be not the same as the first argument.' );
		$this->assertSame( $fixture1->inst, $fixture2->inst, 'Expected the instance properties to be the same.' );
	}
}
