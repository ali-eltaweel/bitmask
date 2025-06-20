<?php

namespace Bitmask;

use ReflectionClass, ReflectionClassConstant;

/**
 * Bitmask class provides a way to handle bitmask operations.
 * It allows you to create bitmasks, check for specific bits, add or remove bits,
 * and convert the bitmask to an integer or an associative array.
 * 
 * @api
 * @abstract
 * @since 1.0.0
 * @version 1.0.0
 * @package bitmask
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
abstract class Bitmask {

    /**
     * Holds the bits of the bitmask classes.
     * 
     * @static
     * @internal
     * @since 1.0.0
     * 
     * @var array<class-string<Bitmask>, array<int, Bit>>
     */
    private static array $BITS = [];

    /**
     * The integer value of the bitmask.
     * 
     * @api
     * @readonly
     * 
     * @var int
     */
    public readonly int $value;

    /**
     * Creates a new Bitmask instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param int|string|Bitmask $value
     */
    public final function __construct(int|string|Bitmask $value) {

        if (is_string($value)) {

            $value = array_reduce(
                static::getBits($value),
                fn (int $carry, Bit $bit) => $carry | $bit->value,
                0
            );
        }

        static::getBits($this->value = $value instanceof Bitmask ? $value->value : $value);
    }

    /**
     * Checks if this bitmask has the specified bits enabled.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param int|string $bits
     * @return bool
     */
    public final function has(int|string $bits): bool {

        foreach (static::getBits($bits) as $bit) {

            if (($bit->value & $this->value) == 0) {

                return false;
            }
        }
        
        return true;
    }

    /**
     * Creates a new bitmask with the specified bits added.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param int|string $bits
     * @return Bitmask
     */
    public final function add(int|string $bits): static {

        $value = $this->value;

        foreach (static::getBits($bits) as $bit) {

            $value |= $bit->value;
        }

        return new static($value);
    }

    /**
     * Creates a new bitmask with the specified bits removed.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param int|string $bits
     * @return Bitmask
     */
    public final function remove(int|string $bits): static {

        $value = $this->value;

        foreach (static::getBits($bits) as $bit) {

            $value &= ~$bit->value;
        }

        return new static($value);
    }

    /**
     * Converts this bitmask to an integer.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @return int
     */
    public final function toInt(): int {

        return $this->value;
    }

    /**
     * Converts this bitmask to an associative array.
     * The keys are the bit values and the values are the bit names.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @return array<int, string>
     */
    public final function toArray(): array {

        return array_combine(
            
            array_map(fn (Bit $bit) => $bit->value, static::getBits($this->value)),
            
            array_map(fn (Bit $bit) => $bit->name, static::getBits($this->value))
        );
    }

    /**
     * Retrieves the bits of this bitmask class.
     * 
     * @api
     * @final
     * @static
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @throws Exceptions\DuplicatedBitIdentifierException
     * @return array<int, Bit>
     */
    public static final function bits(): array {

        if (!is_null($bits = self::$BITS[ static::class ] ?? null)) {
            
            return $bits;
        }

        $bits = [];

        $classReflection = new ReflectionClass(static::class);

        foreach (array_keys($classReflection->getConstants()) as $constantName) {

            if (is_null($bit = Annotations\Bit::annotatedOn($constantReflection = new ReflectionClassConstant(static::class, $constantName)))) {

                continue;
            }

            $bits[] = new Bit(static::getBitName($bit, $constantName), $constantReflection->getValue());
        }

        foreach (array_count_values(array_column($bits, 'name')) as $name => $count) {

            if ($count == 1) continue;

            throw new Exceptions\DuplicatedBitIdentifierException($name);
        }

        foreach (array_count_values(array_column($bits, 'value')) as $value => $count) {

            if ($count == 1) continue;

            throw new Exceptions\DuplicatedBitIdentifierException($value);
        }

        $values = array_column($bits, 'value');
        rsort($values);

        return self::$BITS[ static::class ] = array_map(
            fn (int $bit) => array_values(array_filter($bits, fn (Bit $b) => $b->value == $bit))[0],
            $values
        );
    }

    /**
     * Retrieves the known bits from the specified bits.
     * 
     * @api
     * @final
     * @static
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param int|string $bits
     * @throws Exceptions\UnknownBitException
     * @return array<Bit>
     */
    public static final function getBits(int|string $bits): array {

        if (is_string($bits)) {

            if (str_contains($bits, '|')) {

                return array_merge(...array_map(static::getBits(...), explode('|', $bits)));
            }

            if (is_null($bit = array_values(array_filter(static::bits(), fn (Bit $bit) => $bit->name == $bits))[0] ?? null)) {

                throw new Exceptions\UnknownBitException($bits);
            }
            
            return [ $bit ];
        }

        switch ($bits <=> 0) {

            case 0:  return [];
            case -1: throw new Exceptions\UnknownBitException($bits);
        }

        $bit = null;

        foreach (static::bits() as $_bit) {

            if (($_bit->value & $bits) == 0) continue;

            $bit = $_bit;

            $bits -= $_bit->value;

            break;
        }

        if (is_null($bit)) {

            throw new Exceptions\UnknownBitException(pow(2, floor(log($bits, 2))));
        }

        if ($bits == 0) {

            return [ $bit ];
        }

        return array_merge([ $bit ], static::getBits($bits));
    }

    /**
     * Retrieves the name of a bit.
     * 
     * @static
     * @internal
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param Annotations\Bit $bit
     * @param string $constantName
     * @return string
     */
    protected static function getBitName(Annotations\Bit $bit, string $constantName): string {

        return $bit->name ?? $constantName;
    }
}
