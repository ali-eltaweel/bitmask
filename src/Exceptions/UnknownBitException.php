<?php

namespace Bitmask\Exceptions;

/**
 * Exception thrown when an unknown bit is encountered in a bitmask.
 * 
 * @api
 * @final
 * @since 1.0.0
 * @version 1.0.0
 * @package bitmask
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
final class UnknownBitException extends BitmaskException {

    /**
     * Creates a new UnknownBitException instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param int|string $bit The unknown bit that caused the exception.
     */
    public final function __construct(public readonly int|string $bit) {
        
        parent::__construct(message: sprintf('Unknown bit "%s".', $bit));
    }
}
