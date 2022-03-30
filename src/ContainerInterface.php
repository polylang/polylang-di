<?php
/**
 * Interface for the container.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI;

use WP_Syntex\Polylang_DI\Definition\DefinitionInterface;
use WP_Syntex\Polylang_DI\Exception\InvalidArgumentException;
use WP_Syntex\Polylang_DI\Exception\NotFoundException;

/**
 * Interface for the container.
 *
 * @since 1.0
 */
interface ContainerInterface {
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
	public function get( $id );

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
	public function has( $id );

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
	public function addShared( $id, $concrete );

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
	public function add( $id, $concrete );

	/**
	 * Returns a definition of an item to be extended.
	 *
	 * @since  1.0
	 * @throws NotFoundException No entry was found for this identifier.
	 * @throws InvalidArgumentException The identifier is not a string.
	 *
	 * @param  string $id          Alias used to store the item.
	 * @return DefinitionInterface A `DefinitionInterface` object when matching one of the definitions.
	 */
	public function extend( $id );
}
