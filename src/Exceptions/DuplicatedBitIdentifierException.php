<?php

namespace Bitmask\Exceptions;

/**
 * Exception thrown when a bit identifier (either a value or a name) is used for more than one bit.
 * 
 * @api
 * @final
 * @since 1.0.0
 * @version 1.0.0
 * @package bitmask
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
final class DuplicatedBitIdentifierException extends BitmaskException {

    /**
     * Creates a new DuplicatedBitIdentifierException instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param int|string $bit The bit identifier (either a value or a name) that caused the exception.
     */
    public final function __construct(public readonly int|string $bit) {
        
        parent::__construct(
            message: sprintf('The %s "%s" is used for more than one bit.', is_int($bit) ? 'value' : 'name', $bit)
        );
    }
}
