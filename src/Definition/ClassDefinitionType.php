<?php
/**
 * Class to use to create a definition for a class instance.
 * A definition is a representation of the type of an item.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Definition;

use WP_Syntex\Polylang_DI\ContainerInterface;

/**
 * Class to use to create a definition for a class instance.
 * A definition is a representation of the type of an item.
 *
 * @since 1.0
 */
class ClassDefinitionType extends AbstractDefinitionType {
	/**
	 * Tells if the given value corresponds to this implementation type.
	 *
	 * @since 1.0
	 *
	 * @param  mixed $concrete The item to store.
	 * @return bool
	 */
	public static function is( $concrete ) {
		return is_string( $concrete ) && class_exists( $concrete );
	}

	/**
	 * Handles instantiation and manipulation of the definition.
	 *
	 * @since 1.0
	 *
	 * @param  ContainerInterface $container Instance of `ContainerInterface`.
	 * @param  array<mixed>       $args      Arguments to use for instanciation.
	 * @return mixed
	 */
	public function build( ContainerInterface $container, array $args = [] ) {
		$classname = $this->concrete;
		$args      = ! empty( $args ) ? $args : $this->arguments;
		$args      = $this->buildArguments( $container, $args );

		return new $classname( ...$args ); // phpcs:ignore NeutronStandard.Functions.VariableFunctions.VariableFunction
	}
}
