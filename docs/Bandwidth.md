# Bandwidth
The `Bandwidth` class represents a bandwidth value in bits per second (bps) and provides utility methods for converting and formatting bandwidth into human-readable units. It is used throughout the M3U8 parser library to encapsulate and manipulate bandwidth values for streams and media playlists.

This class makes it easy to work with bandwidth values, convert them to different units (bits, bytes, kilobits, megabits, etc.), and display them in a user-friendly format. It is especially useful when serializing or displaying bandwidth information in UIs or logs.

---

## Properties
### `int $bps`
The bandwidth value in bits per second (bps). This is the core value stored by the class and is set during construction.

---

## Constructor
Creates a new `Bandwidth` instance.

**Signature:**
```php
public function __construct(
  int|string $bitsPerSecond,
  ?string $key
);
```

**Parameters:**
- `$bitsPerSecond` (`int|string`): The bandwidth value in bits per second. Can be provided as an integer or a numeric string.
- `$key` (`string`): M3U8 key or label for the bandwidth value.

**Example:**
```php
$bw = new Bandwidth( 1500000, 'BANDWIDTH' );
$bw2 = new Bandwidth( '2560000', 'AVG-BANDWIDTH' );
```

---

## Methods
### `convert(array $units, int $base): array`
Converts the bandwidth value to the most appropriate unit based on the provided units and base.

**Parameters:**
* `$units` (`array`): List of unit names (e.g., `['b', 'Kb', 'Mb', ...]`).
* `$base` (`int`): The base for conversion (`1000` for bits, `1024` for bytes).

**Returns:**
* `array` — `[value, unit]` where value is the converted number and `unit` is the unit string.

**Note:**
This is a low-level utility method. You will typically use `toBits()` or `toBytes()` instead.

---

### `toBits(bool $longUnitNames = false): array`
Converts the bandwidth to the most appropriate bits unit (e.g., b, Kb, Mb, etc.).

**Parameters:**
* `$longUnitNames` (`bool`): If `true`, uses long unit names (e.g., "Kilobits"); otherwise, uses short names (e.g., "Kb").

**Returns:**
`array` — `[value, unit]`

**Example:**
```php
$bw = new Bandwidth( 1540000 );
list( $value, $unit ) = $bw->toBits();
echo "$value $unit";
// e.g., "1.54 Mb"
```

**Best Practice:**
Use this method when you want to display bandwidth in bits per second, which is standard for network speeds.

---

### `toBytes(bool $longUnitNames = false): array`
Converts the bandwidth to the most appropriate bytes unit (e.g., B, KB, MB, etc.).

**Parameters:**
* `$longUnitNames` (`bool`): If `true`, uses long unit names (e.g., "Kilobytes"); otherwise, uses short names (e.g., "Kb").

**Returns:**
`array` — `[value, unit]`

**Example:**
```php
$bw = new Bandwidth( 1540000 );
list( $value, $unit ) = $bw->toBytes();
echo "$value $unit";
// e.g., "187.99 MB"
```

**Edge Case:**
Conversion to bytes divides by 8 and then applies the base 1024 for unit scaling.

---

### `toString(): string`
Returns a human-readable string representation of the bandwidth in bytes per second, rounded to two decimals, with the unit and "ps" suffix.

**Returns:**
`string` — e.g., `"183.11 KBps"`

**Example:**
```php
$bw = new Bandwidth( 1540000 );
echo $bw->toString();
// e.g., "183.11 KBps"
```

**Best Practice:**
Use this for quick display or logging. For more control, use toBits() or toBytes().

---

## Serialization
This class can be serialized into JSON and m3u8 attributes.

### JSON Serialization
If we put the `Bandwidth` instance into an array or object and serialize it to JSON, it will be serialized as the `bps` value, which is the core value.

**Example:**
```php
json_encode(
[
  'bw' => new Bandwidth( 1540000 )
]);
```

**Output:**
```json
{
  "bw": 1540000
}
```

---

### M3U8 Serialization
As part of this library, the `Bandwidth` instance can be serialized into M3U8 attributes.

**Example:**
```php
echo ( new Bandwidth( 1540000, 'BW' ))->toM3U8();
// "BANDWIDTH=1540000"
```
