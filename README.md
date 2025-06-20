# Bitmask

**A simple bitmask library for PHP**

- [Bitmask](#bitmask)
  - [Installation](#installation)
  - [Usage](#usage)
    - [Declaring Bitmask](#declaring-bitmask)
    - [Working with Bitmasks](#working-with-bitmasks)

***

## Installation

Install *bitmask* via Composer:

```bash
composer require ali-eltaweel/bitmask
```

## Usage

### Declaring Bitmask

```php
use Bitmask\{ Annotations\Bit, Bitmask };

class JsonOptions extends Bitmask {

  #[Bit] const PRETTY_PRINT = JSON_PRETTY_PRINT;

  #[Bit] const UNESCAPED_SLASHES = JSON_UNESCAPED_SLASHES;

  #[Bit] const OBJECT_AS_ARRAY = JSON_OBJECT_AS_ARRAY;
}
```

Using the `#[Bit]` annotation with no arguments will result in the name of the constant being used as the bit's name. You can also pass a string to the annotation to specify a custom name for the bit:

```php
use Bitmask\{ Annotations\Bit, Bitmask };

class JsonOptions extends Bitmask {

  #[Bit('pretty')] const PRETTY_PRINT = JSON_PRETTY_PRINT;

  #[Bit('forceArray')] const OBJECT_AS_ARRAY = JSON_OBJECT_AS_ARRAY;
}
```

### Working with Bitmasks

```php
$jsonOptions = new JsonOptions(JsonOptions::PRETTY_PRINT);

$jsonOptions->has(JsonOptions::PRETTY_PRINT); // true
$jsonOptions->has(JsonOptions::UNESCAPED_SLASHES); // false

$jsonOptions = $jsonOptions->add(JsonOptions::UNESCAPED_SLASHES);
$jsonOptions->has(JsonOptions::UNESCAPED_SLASHES); // true

$jsonOptions = $jsonOptions->remove(JsonOptions::PRETTY_PRINT);
$jsonOptions->has('pretty'); // false

$jsonOptions = $jsonOptions->add('pretty|forceArray');
$jsonOptions->has('pretty'); // true
$jsonOptions->has(JsonOptions::OBJECT_AS_ARRAY); // true

$jsonOptions->toArray(); // [ 128 => 'pretty', 64 => 'UNESCAPED_SLASHES', 1 => 'forceArray' ]

$jsonOptions->toInt(); // 193
```
