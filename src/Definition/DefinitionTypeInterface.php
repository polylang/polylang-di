<?php
/**
 * Interface to implement to create a type definition.
 * A definition is a representation of the type of an item.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Definition;

use WP_Syntex\Polylang_DI\ContainerInterface;

/**
 * Interface to implement to create a type definition.
 * A definition is a representation of the type of an item.
 *
 * @since 1.0
 */
interface DefinitionTypeInterface extends DefinitionInterface {
	/**
	 * Tells if the given value corresponds to this definition type.
	 *
	 * @since 1.0
	 *
	 * @param  mixed $concrete The item to store.
	 * @return bool
	 */
	public static function is( $concrete );

	/**
	 * Handles instantiation and manipulation of the definition.
	 *
	 * @since 1.0
	 *
	 * @param  ContainerInterface $container Instance of `ContainerInterface`.
	 * @param  array<mixed>       $args      Optionnal. Arguments to use for instanciation.
	 * @return mixed
	 */
	public function build( ContainerInterface $container, array $args = [] );
}
