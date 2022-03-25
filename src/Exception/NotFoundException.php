<?php
/**
 * Exception used when an item has not been found.
 * php version 5.6
 *
 * @package Polylang-DI
 */

namespace WP_Syntex\Polylang_DI\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Exception used when an item has not been found.
 *
 * @since 1.0
 */
class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface {}
