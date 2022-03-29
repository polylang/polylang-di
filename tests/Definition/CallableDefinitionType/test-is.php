<?php
/**
 * Tests for `CallableDefinitionType::is()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Definition\CallableDefinitionType;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Definition\CallableDefinitionType;

/**
 * Tests for `CallableDefinitionType::is()`.
 *
 * @group container
 */
class Is_Test extends TestCase {
	/**
	 * Test should return true when callable.
	 *
	 * @return void
	 */
	public function testShouldReturnTrueWhenCallable() {
		$definition = new CallableDefinitionType( 'foo', function () {} );

		$this->assertTrue( CallableDefinitionType::is( function () {} ), 'Expected to return true for a closure.' );
		$this->assertTrue( CallableDefinitionType::is( 'file_exists' ), 'Expected to return true for a function name.' );
		$this->assertTrue( CallableDefinitionType::is( [ $definition, 'build' ] ), 'Expected to return true for a object + method name array.' );
		$this->assertTrue( CallableDefinitionType::is( [ CallableDefinitionType::class, 'is' ] ), 'Expected to return true for a class name + method name array.' );
		$this->assertTrue( CallableDefinitionType::is( CallableDefinitionType::class . '::is' ), 'Expected to return true for a class name + method name string.' );
	}

	/**
	 * Test should return false when not callable.
	 *
	 * @return void
	 */
	public function testShouldReturnFalseWhenNotCallable() {
		$definition = new CallableDefinitionType( 'foo', function () {} );

		$this->assertFalse( CallableDefinitionType::is( 42 ), 'Expected to return false for an integer.' );
		$this->assertFalse( CallableDefinitionType::is( 'some string' ), 'Expected to return false for a string.' );
		$this->assertFalse( CallableDefinitionType::is( CallableDefinitionType::class ), 'Expected to return false for a class name.' );
		$this->assertFalse( CallableDefinitionType::is( $definition ), 'Expected to return false for an object.' );
	}
}
