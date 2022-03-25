<?php
/**
 * Interface for the container.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use WP_Syntex\Polylang_DI\Definition\DefinitionInterface;
use WP_Syntex\Polylang_DI\Exception\NotFoundException;

/**
 * Interface for the container.
 *
 * @since 1.0
 */
interface ContainerInterface extends PsrContainerInterface {
	/**
	 * Adds a shared item to the container.
	 *
	 * @since 1.0
	 *
	 * @param  string     $id           Alias used to store the item.
	 * @param  mixed|null $concrete     The item to store. If omitted, `$id` is used.
	 * @return DefinitionInterface|null A `DefinitionInterface` object when matching one of the definitions.
	 *                                  Null otherwise.
	 */
	public function addShared( $id, $concrete = null );

	/**
	 * Adds an item to the container.
	 *
	 * @since 1.0
	 *
	 * @param  string     $id           Alias used to store the item.
	 * @param  mixed|null $concrete     The item to store. If omitted, `$id` is used.
	 * @return DefinitionInterface|null A `DefinitionInterface` object when matching one of the definitions.
	 *                                  Null otherwise.
	 */
	public function add( $id, $concrete = null );

	/**
	 * Returns a definition of an item to be extended.
	 *
	 * @since 1.0
	 * @throws NotFoundException No entry was found for this identifier.
	 *
	 * @param  string $id          Alias used to store the item.
	 * @return DefinitionInterface A `DefinitionInterface` object when matching one of the definitions.
	 */
	public function extend( $id );
}
