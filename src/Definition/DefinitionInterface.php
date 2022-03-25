<?php
/**
 * Interface to implement to create a definition.
 * A definition is a representation of the type of an item.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Definition;

use WP_Syntex\Polylang_DI\ContainerInterface;

/**
 * Interface to implement to create a definition.
 * A definition is a representation of the type of an item.
 *
 * @since 1.0
 */
interface DefinitionInterface {

	/**
	 * Adds an argument to be injected.
	 *
	 * @since 1.0
	 *
	 * @param  mixed $arg An argument.
	 * @return self
	 */
	public function withArgument( $arg );

	/**
	 * Adds multiple arguments to be injected.
	 *
	 * @since 1.0
	 *
	 * @param  array<mixed> $args List of arguments.
	 * @return self
	 */
	public function withArguments( array $args );

	/**
	 * Replaces existing arguments by new ones.
	 *
	 * @since 1.0
	 *
	 * @param  array<mixed> $args List of arguments.
	 * @return self
	 */
	public function newArguments( array $args );
}
