<?php
/**
 * Class to use as proxy for other definitions.
 * A definition is a representation of the type of an item.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Definition;

/**
 *  Class to use as proxy for other definitions.
 * A definition is a representation of the type of an item.
 *
 * @since 1.0
 */
class Definition implements DefinitionInterface {
	/**
	 * The definition to proxy.
	 *
	 * @var DefinitionInterface
	 */
	protected $definition;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param  DefinitionInterface $definition The definition to proxy.
	 * @return void
	 */
	public function __construct( DefinitionInterface $definition ) {
		$this->definition = $definition;
	}

	/**
	 * Adds an argument to be injected.
	 *
	 * @since 1.0
	 *
	 * @param  mixed $arg An argument.
	 * @return self
	 */
	public function withArgument( $arg ) {
		$this->definition->withArgument( $arg );

		return $this;
	}

	/**
	 * Adds multiple arguments to be injected.
	 *
	 * @since 1.0
	 *
	 * @param  array<mixed> $args List of arguments.
	 * @return self
	 */
	public function withArguments( array $args ) {
		array_map( [ $this->definition, 'withArgument' ], $args );

		return $this;
	}

	/**
	 * Replaces existing arguments by new ones.
	 *
	 * @since 1.0
	 *
	 * @param  array<mixed> $args List of arguments.
	 * @return self
	 */
	public function withNewArguments( array $args ) {
		$this->definition->withNewArguments( $args );

		return $this;
	}
}
