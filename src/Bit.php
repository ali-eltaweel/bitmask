<?php

namespace Bitmask;

/**
 * Bit class represents a single bit in a bitmask.
 * 
 * @api
 * @final
 * @since 1.0.0
 * @version 1.0.0
 * @package bitmask
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
final class Bit {

    /**
     * Creates a new Bit instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     * @param int $value
     */
    public final function __construct(public readonly string $name, public readonly int $value) {}
}
