<?php
/**
 * Fixtures.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Tests\Fixtures;

use WP_Syntex\Polylang_DI\ContainerInterface;
use WP_Syntex\Polylang_DI\Definition\AbstractDefinitionType;

/**
 * An empty class.
 */
class WithNoParams {
	const OUTPUT = 'Output WithNoParams';

	public function __construct() {
		echo self::OUTPUT;
	}
};

/**
 * A class with 2 basic parameters.
 */
class WithTwoParams {
	public $int;
	public $string;

	public function __construct( $int, $string = null ) {
		$this->int    = $int;
		$this->string = $string;
	}
}

/**
 * A class with a class instance as parameter.
 */
class WithAnInstance {
	public $inst;

	public function __construct( WithTwoParams $inst ) {
		$this->inst = $inst;
	}
}

/**
 * A dummy class extending AbstractDefinitionType.
 */
class DummyDefinition extends AbstractDefinitionType {

	public static function is( $concrete ) {
		return true;
	}

	public function build( ContainerInterface $container, array $args = [] ) {}
}
