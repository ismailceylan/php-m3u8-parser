# GroupId
The `GroupId` class represents the concept of a group identifier in the context of M3U8 playlists (used in HTTP Live Streaming, or HLS). In M3U8 playlists, a group ID is used to associate related media streams, such as audio, video, or subtitles, allowing players to switch between different renditions or languages. This class provides a type-safe, serializable, and M3U8-compliant way to represent and manipulate group IDs in PHP applications.

The class is designed to be compatible with M3U8 serialization and JSON serialization, making it easy to integrate with playlist generation, APIs, and storage solutions.

---

## Properties
### `string $value`
Stores the group identifier as a string. This value uniquely identifies a group of related media streams in an M3U8 playlist.

**Usage Example:**
```php
echo ( new GroupId( 'audio-main' ))->value;
// audio-main
```

**Best Practices:**  
- Use descriptive and unique group IDs to avoid conflicts in playlists.
- Follow naming conventions (e.g., lowercase, hyphens) for consistency.

---

### `string $key`
Represents the name of the group identifier attribute in M3U8 playlists. This attribute is used to specify the group ID for related media streams.

**Usage Example:**
```php
echo ( new GroupId( 'audio-main' ))->key;
// GROUP-ID

echo ( new GroupId( 'audio-main', 'FOO' ))->key;
// FOO
```

## Methods
### `__construct(string $id = '',string $key = 'GROUP-ID')`
Initializes the `GroupId` object with a group identifier. Accepts a string input and assigns it to the `$id` property.

**Parameters:**
- `$id`: The group identifier (string). Defaults to an empty string.
- `$key`: The name of the group identifier attribute. Defaults to 'GROUP-ID'.

**Usage Example:**
```php
$groupId1 = new GroupId( 'audio-main' );
$groupId2 = new GroupId( 'subs-en', 'SUBS' );
```

**Edge Cases:**  
- Passing an empty string is allowed but may not be meaningful in a playlist context.
- Ensure the group ID matches the references used in other playlist tags.

---

## Serialization
### String Concatenation
Returns the group ID as a string.

**Returns:**
- `string`: The group ID value.

**Usage Example:**
```php
echo new GroupId( 'audio-main' );
// audio-main
```

**Best Practices:**  
- Use this method when directly working with the group ID value.

---

### M3U8 Serialization
Returns the group ID formatted as an M3U8 attribute string, e.g., `GROUP-ID="audio-main"`.

**Returns:**  
- `string`: The group ID in M3U8 attribute format.

**Usage Example:**
```php
echo ( new GroupId( 'audio-main' ))->toM3U8();
// GROUP-ID="audio-main"
```

We can change the name of the attribute using the `$key` parameter in the constructor.

```php
echo ( new GroupId( 'sub', 'SUBTITLES' ))->toM3U8();
// SUBTITLES="sub"
```

**Best Practices:**  
- Use this method when generating M3U8 playlists or attributes to ensure proper formatting.

---

### JSON Serialization
Serializes the group ID value for use with `json_encode()`.

**Returns:**  
- `string`: The group ID value.

**Usage Example:**
```php
echo json_encode(
[
	'id' => new GroupId( 'audio-main' )
]);
```

**Output:**  
```json
{
	"id": "audio-main"
}
```

**Best Practices:**  
- Useful for APIs or storage where the group ID needs to be represented as a string.
