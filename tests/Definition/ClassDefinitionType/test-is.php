<?php
/**
 * Tests for `ClassDefinitionType::is()`.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Definition\ClassDefinitionType;

use PHPUnit\Framework\TestCase;
use WP_Syntex\Polylang_DI\Definition\ClassDefinitionType;

/**
 * Tests for `ClassDefinitionType::is()`.
 *
 * @group container
 */
class Is_Test extends TestCase {
	/**
	 * Test should return true when class name.
	 *
	 * @return void
	 */
	public function testShouldReturnTrueWhenClassName() {
		$this->assertTrue( ClassDefinitionType::is( ClassDefinitionType::class ), 'Expected to return true for a class name.' );
	}

	/**
	 * Test should return false when not class name.
	 *
	 * @return void
	 */
	public function testShouldReturnFalseWhenNotClassName() {
		$definition = new ClassDefinitionType( 'foo', CallableDefinitionType::class );

		$this->assertFalse( ClassDefinitionType::is( 42 ), 'Expected to return false for an integer.' );
		$this->assertFalse( ClassDefinitionType::is( 'some string' ), 'Expected to return false for a string.' );
		$this->assertFalse( ClassDefinitionType::is( function () {} ), 'Expected to return false for a closure.' );
		$this->assertFalse( ClassDefinitionType::is( $definition ), 'Expected to return false for an object.' );
	}
}
