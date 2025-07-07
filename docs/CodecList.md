# CodecList
The `CodecList` class, represents a collection of media codecs, typically used in the context of M3U8 playlists (HLS streaming). Codecs are strings that describe how audio and video streams are encoded, and are often listed in the `CODECS` attribute of M3U8 playlist files. This class provides a structured way to manage, serialize, and format codec lists for use in PHP applications that work with M3U8 files.

The class implements both the `JsonSerializable` and `M3U8Serializable` interfaces, allowing it to be easily converted to JSON and to the M3U8-specific string format. It encapsulates an array of codec strings and provides utility methods for conversion and serialization.

---

# Methods
## `__construct(array $codecs)`
Initializes the `CodecList` with an array of codec strings.

**Parameters:**
- `array $codecs`: An array of codec strings.

**Usage Example:**
```php
echo new CodecList([ 'avc1.4d401f', 'mp4a.40.2' ]);
```

**Edge Cases:**  
- Passing an empty array will result in an empty codec list.
- Non-string values in the array may cause unexpected behavior; always use strings.

---

# Serialization
## String Concatenation
Returns the codecs as a single comma-separated string.

**Returns:**  
- `string`: The codecs concatenated with commas.

**Usage Example:**
```php
echo (string)$codecList; // Output: avc1.4d401f,mp4a.40.2
```

**Best Practices:**  
- Use this method when you need the codecs in the format required by M3U8 `CODECS` attributes.

---

## JSON Serialization
Serializes the codec list to an array for use with `json_encode()`.

**Returns:**  
- `array`: The array of codec strings.

**Usage Example:**
```php
echo json_encode( $codecList );
```

**Output:**
```json
[
    "avc1.4d401f",
    "mp4a.40.2"
]
```

**Best Practices:**  
- Useful for APIs or storage where codecs need to be represented as JSON.

---

## M3U8 Serialization
Formats the codec list as a string suitable for the M3U8 `CODECS` attribute.

**Returns:**  
- `string`: The codecs wrapped in `CODECS="..."`.

**Usage Example:**
```php
echo $codecList->toM3U8();
```

**Output:**
```
CODECS="avc1.4d401f,mp4a.40.2"
```

**Edge Cases:**  
- If the codec list is empty, the output will be `CODECS=""`.

---

## Array Conversion
Returns the internal array of codec strings.

**Returns:**  
- `array`: The list of codecs.

**Usage Example:**
```php
var_dump( $codecList->toArray()); // Output: ['avc1.4d401f', 'mp4a.40.2']
```

**Output:**
```php
array(2) {
  [0]=> string(12) "avc1.4d401f"
  [1]=> string(9) "mp4a.40.2"	
}
```

**Best Practices:**  
- Use this method when you need to manipulate or inspect the codec list as an array.
