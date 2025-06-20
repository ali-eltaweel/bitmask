<?php

namespace Bitmask\Annotations;

use Attraction\Annotation;

use Attribute;

/**
 * The Bit annotation is used to define a bit in a bitmask class.
 * It can be applied to class constants to specify the name of the bit.
 * 
 * @api
 * @final
 * @since 1.0.0
 * @version 1.0.0
 * @package bitmask
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final class Bit extends Annotation {

    /**
     * Creates a new Bit annotation instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string|null $name The name of the bit. If not provided, the constant name will be used.
     */
    public final function __construct(public readonly ?string $name = null) {}
}
