<?php
/**
 * Interface to implement as item argument when we want to make sure this argument's value will not be mistaken with
 * a definition.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Argument;

/**
 * Interface to implement as item argument when we want to make sure this argument's value will not be mistaken with
 * a definition.
 *
 * @since 1.0
 */
interface RawArgumentInterface {
	/**
	 * Returns the value of the raw argument.
	 *
	 * @since 1.0
	 *
	 * @return mixed The raw value.
	 */
	public function getValue();
}
