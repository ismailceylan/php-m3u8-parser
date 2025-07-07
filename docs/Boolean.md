# Boolean Class
The `Boolean` class represents a boolean value (like "yes" & "no" or `true` & `false`) in the context of M3U8 playlists and attributes. It provides utility methods for converting between PHP boolean values and the M3U8 string representation (`YES`/`NO`), as well as for serializing the value to JSON. This class is useful for encapsulating boolean attributes in M3U8 tags, ensuring consistent formatting and serialization throughout the library.

The class accepts both string and boolean inputs, automatically mapping common M3U8 boolean representations (`'YES'`, `'NO'`) and PHP booleans to the correct internal value. It also supports an optional key, which is used when serializing the value for M3U8 output.

---

## Properties
### `bool|null $value`
The boolean value represented by this instance. Can be `true`, `false`, or `null` (if the input is not recognized).

### `string|null $key`
The name or key associated with the boolean value, used when serializing to M3U8 format (e.g., `"DEFAULT"`).

---

## Constructor
Creates a new `Boolean` instance.

**Signature:**
```php
public function __construct(
	string|bool $value,
	?string $key
);
```

- **Parameters:**
  - `$value` (`string|bool`): The value to initialize with. Accepts `'yes'`, `'no'`, `true`, or `false` (case-insensitive for strings). Any other value results in `null`.
  - `$key` (`string|null`): The key or attribute name for the boolean (e.g., `"DEFAULT"`).

**Example:**
```php
$bool1 = new Boolean( 'YES', 'DEFAULT' );
$bool2 = new Boolean( false, 'AUTOSELECT' );
$bool3 = new Boolean( 'no', 'FORCED' );
```

**Edge Case:**
If an unrecognized value is provided (e.g., `'maybe'`), `$value` will be `null`.

---

## Serialization
This class can be serialized into JSON and M3U8 attributes.

### M3U8 Serialization
Converts the boolean value to a string in the M3U8 attribute format.

**Returns:**
`string` — The key and value in M3U8 format, e.g., `"DEFAULT=YES"` or `"DEFAULT=NO"`.

**Example:**
```php
echo ( new Boolean( true, 'DEFAULT' ))->toM3U8();
// "DEFAULT=YES"
```

**Best Practice:**
Always provide a valid key for correct M3U8 serialization.

---

### JSON Serialization
Serializes the boolean value for JSON encoding.

**Returns:**
`mixed` — The boolean value (`true` or `false`), or `null` if the value is not recognized.

**Example:**
```php
json_encode(
[
	'default' => new Boolean( 'no' )
]);
```

**Output:**
```json
{
	"default": false
}
```

**Note:**
This method is called automatically when using `json_encode()`.
