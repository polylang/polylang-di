<?php
/**
 * Abstract class to use to create a definition.
 * A definition is a representation of the type of an item.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Definition;

use WP_Syntex\Polylang_DI\Argument\RawArgumentInterface;
use WP_Syntex\Polylang_DI\ContainerInterface;

/**
 * Abstract class to use to create a definition.
 * A definition is a representation of the type of an item.
 *
 * @since 1.0
 */
abstract class AbstractDefinitionType implements DefinitionTypeInterface {
	/**
	 * Alias used to store the item.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The item to store.
	 *
	 * @var mixed
	 */
	protected $concrete;

	/**
	 * List of arguments to use for the instanciation.
	 *
	 * @var array<mixed>
	 */
	protected $arguments = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param  string $id       Alias used to store the item.
	 * @param  mixed  $concrete The item to store.
	 * @return void
	 */
	public function __construct( $id, $concrete ) {
		$this->id       = $id;
		$this->concrete = $concrete;
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
		$this->arguments[] = $arg;

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
		array_map( [ $this, 'withArgument' ], $args );

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
		$this->arguments = $args;

		return $this;
	}

	/**
	 * Handles instantiation and manipulation of arguments.
	 *
	 * @since 1.0
	 *
	 * @param  ContainerInterface $container Instance of `ContainerInterface`.
	 * @param  array<mixed>       $args      Arguments to use for instanciation.
	 * @return mixed
	 */
	protected function buildArguments( ContainerInterface $container, array $args ) {
		foreach ( $args as $k => $arg ) {
			if ( $arg instanceof RawArgumentInterface ) {
				$args[ $k ] = $arg->getValue();
			} elseif ( is_string( $arg ) && $arg !== $this->id && $container->has( $arg ) ) { // `$arg !== $this->id` prevents an infinite loop.
				$args[ $k ] = $container->get( $arg );
			}
		}

		return $args;
	}
}
