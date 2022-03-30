<?php
/**
 * The container.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI;

use WP_Syntex\Polylang_DI\Definition\CallableDefinitionType;
use WP_Syntex\Polylang_DI\Definition\ClassDefinitionType;
use WP_Syntex\Polylang_DI\Definition\Definition;
use WP_Syntex\Polylang_DI\Definition\DefinitionInterface;
use WP_Syntex\Polylang_DI\Definition\DefinitionTypeInterface;
use WP_Syntex\Polylang_DI\Exception\InvalidArgumentException;
use WP_Syntex\Polylang_DI\Exception\NotFoundException;

/**
 * The container.
 *
 * @since 1.0
 */
class Container implements ContainerInterface {
	/**
	 * Contains the builded versions of shared items.
	 *
	 * @var array<mixed>
	 */
	protected $sharedBuilded = [];

	/**
	 * Contains the definitions of shared items.
	 *
	 * @var array<DefinitionTypeInterface>
	 */
	protected $sharedDefinitions = [];

	/**
	 * Contains the definitions of non-shared items.
	 *
	 * @var array<DefinitionTypeInterface>
	 */
	protected $singleDefinitions = [];

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @since  1.0
	 * @throws NotFoundException No entry was found for this identifier.
	 * @throws InvalidArgumentException The identifier is not a string.
	 *
	 * @param  string $id Identifier of the entry to look for.
	 * @return mixed      Entry.
	 */
	public function get( $id ) {
		if ( ! is_string( $id ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'The $id parameter must be of type string, %s given.',
					is_object( $id ) ? get_class( $id ) : gettype( $id )
				)
			);
		}

		if ( array_key_exists( $id, $this->sharedBuilded ) ) {
			// Shared: already builded.
			return $this->sharedBuilded[ $id ];
		}

		if ( array_key_exists( $id, $this->sharedDefinitions ) ) {
			// Shared: build, store, and return.
			$this->sharedBuilded[ $id ] = $this->sharedDefinitions[ $id ]->build( $this );

			unset( $this->sharedDefinitions[ $id ] );
			return $this->sharedBuilded[ $id ];
		}

		if ( array_key_exists( $id, $this->singleDefinitions ) ) {
			// Not shared: build and return.
			return $this->singleDefinitions[ $id ]->build( $this );
		}

		throw new NotFoundException(
			sprintf( "Alias '%s' is not being managed by the container.", $id )
		);
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * @since  1.0
	 * @throws InvalidArgumentException The identifier is not a string.
	 *
	 * @param  string $id Identifier of the entry to look for.
	 * @return bool
	 */
	public function has( $id ) {
		if ( ! is_string( $id ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'The $id parameter must be of type string, %s given.',
					is_object( $id ) ? get_class( $id ) : gettype( $id )
				)
			);
		}

		return array_key_exists( $id, array_merge( $this->singleDefinitions, $this->sharedBuilded, $this->sharedDefinitions ) );
	}

	/**
	 * Adds a shared item to the container.
	 *
	 * @since  1.0
	 * @throws InvalidArgumentException The identifier is not a string.
	 *
	 * @param  string     $id           Alias used to store the item.
	 * @param  mixed      $concrete     The item to store.
	 * @return DefinitionInterface|null A `DefinitionInterface` object when matching one of the definitions.
	 *                                  Null otherwise.
	 */
	public function addShared( $id, $concrete ) {
		if ( ! is_string( $id ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'The $id parameter must be of type string, %s given.',
					is_object( $id ) ? get_class( $id ) : gettype( $id )
				)
			);
		}

		return $this->store( $id, $concrete, true );
	}

	/**
	 * Adds an item to the container.
	 *
	 * @since  1.0
	 * @throws InvalidArgumentException The identifier is not a string.
	 *
	 * @param  string     $id           Alias used to store the item.
	 * @param  mixed      $concrete     The item to store.
	 * @return DefinitionInterface|null A `DefinitionInterface` object when matching one of the definitions.
	 *                                  Null otherwise.
	 */
	public function add( $id, $concrete ) {
		if ( ! is_string( $id ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'The $id parameter must be of type string, %s given.',
					is_object( $id ) ? get_class( $id ) : gettype( $id )
				)
			);
		}

		return $this->store( $id, $concrete, false );
	}

	/**
	 * Returns a definition of an item to be extended.
	 *
	 * @since  1.0
	 * @throws NotFoundException No entry was found for this identifier.
	 * @throws NotFoundException The entry is already builded.
	 * @throws InvalidArgumentException The identifier is not a string.
	 *
	 * @param  string $id          Alias used to store the item.
	 * @return DefinitionInterface A `DefinitionInterface` object when matching one of the definitions.
	 */
	public function extend( $id ) {
		if ( ! is_string( $id ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'The $id parameter must be of type string, %s given.',
					is_object( $id ) ? get_class( $id ) : gettype( $id )
				)
			);
		}

		if ( array_key_exists( $id, $this->sharedDefinitions ) ) {
			return new Definition( $this->sharedDefinitions[ $id ] );
		}

		if ( array_key_exists( $id, $this->singleDefinitions ) ) {
			return new Definition( $this->singleDefinitions[ $id ] );
		}

		if ( array_key_exists( $id, $this->sharedBuilded ) ) {
			throw new NotFoundException(
				sprintf( "Unable to extend alias '%s' as it is already builded and cannot be extended anymore.", $id )
			);
		}

		throw new NotFoundException(
			sprintf( "Unable to extend alias '%s' as it is not being managed as a definition.", $id )
		);
	}

	/**
	 * Adds an item to the container.
	 *
	 * @since 1.0
	 *
	 * @param  string     $id           Alias used to store the item.
	 * @param  mixed      $concrete     The item to store.
	 * @param  bool       $share        True to share the item. False otherwise.
	 * @return DefinitionInterface|null A `DefinitionInterface` object when matching one of the "definitions".
	 *                                  Null otherwise.
	 */
	protected function store( $id, $concrete, $share ) {
		unset( $this->singleDefinitions[ $id ], $this->sharedBuilded[ $id ], $this->sharedDefinitions[ $id ] );

		$definition = $this->getDefinition( $id, $concrete );

		if ( ! $definition instanceof DefinitionTypeInterface ) {
			// Doesn't match any definition: store the raw value.
			$this->sharedBuilded[ $id ] = $concrete;
			return null;
		}

		// Store the definition and return the proxy.
		if ( $share ) {
			$this->sharedDefinitions[ $id ] = $definition;
		} else {
			$this->singleDefinitions[ $id ] = $definition;
		}

		return new Definition( $definition );
	}

	/**
	 * Returns a definition based on type of concrete.
	 *
	 * @since 1.0
	 *
	 * @param  string     $id                Alias used to store the item.
	 * @param  mixed      $concrete          The item to store.
	 * @return DefinitionTypeInterface|mixed A `DefinitionTypeInterface` object when matching one of the "definitions".
	 *                                       `$concrete` otherwise.
	 */
	protected function getDefinition( $id, $concrete ) {
		if ( ClassDefinitionType::is( $concrete ) ) {
			return new ClassDefinitionType( $id, $concrete );
		}

		if ( CallableDefinitionType::is( $concrete ) ) {
			return new CallableDefinitionType( $id, $concrete );
		}

		/**
		 * If the item is not definable we just return the value to be stored in the container as an arbitrary
		 * value/instance.
		 */
		return $concrete;
	}
}
