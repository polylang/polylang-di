<?php
/**
 * Class to use as item argument when we want to make sure this argument's value will not be mistaken with a definition.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Argument;

/**
 * Class to use as item argument when we want to make sure this argument's value will not be mistaken with a definition.
 *
 * @since 1.0
 */
class RawArgument implements RawArgumentInterface {
	/**
	 * The raw value.
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param  mixed $value A raw value.
	 * @return void
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Returns the value of the raw argument.
	 *
	 * @since 1.0
	 *
	 * @return mixed The raw value.
	 */
	public function getValue() {
		return $this->value;
	}
}
