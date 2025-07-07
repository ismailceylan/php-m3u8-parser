# Media
The Media class represents a single media entry in an HLS (HTTP Live Streaming) master playlist, specifically the `#EXT-X-MEDIA` tag. This class encapsulates all standard and non-standard attributes of a media track, such as audio, video, subtitles, or closed captions. It provides methods for parsing raw M3U8 media tag syntax, setting and retrieving media attributes, serializing to M3U8 and JSON formats, and handling custom properties. The class is designed for robust manipulation and inspection of media tracks in playlist generation, editing, or analysis tools.

---

## Properties
We will use the following media string as an example:

```php
$str = '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="audio-group-1",FOO=OKAY,NAME="English",LANGUAGE="en",DEFAULT=YES,AUTOSELECT=YES,URI="en.ac3"';
```

### `public array $nonStandardProps`
Stores any non-standard or custom attributes found in the media tag that are not explicitly handled by the class.

**Example:**
```php
echo ( new Media( $str ))->nonStandardProps[ 'FOO' ];
```

**Best Practice:**  
Use this property to preserve unknown or custom attributes during parsing and serialization.

---

### `public ?Boolean $default`
Represents the `DEFAULT` attribute as a [`Boolean`](Boolean.md) object, indicating if this media is the default selection.

**Example:**
```php
echo ( new Media( $str ))->default;
// YES
```
**Edge Case:**  
May be `null` if not set or not present in the playlist.

---

### `public ?Boolean $autoSelect`
Represents the `AUTOSELECT` attribute as a [`Boolean`](Boolean.md) object, indicating if the client should automatically select this media.

**Example:**
```php
echo ( new Media( $str ))->autoSelect;
// YES
```

**Edge Case:**  
May be `null` if not set.

---

### `public ?Name $name`
Represents the `NAME` attribute as a [`Name`](Name.md) object, a human-readable identifier for the media.

**Example:**
```php
echo ( new Media( $str ))->name;
// English
```

**Edge Case:**  
May be `null` if not set.

---

### `public ?Language $language`
Represents the LANGUAGE attribute as a [`Language`](Language.md) object, specifying the language of the media.

**Example:**
```php
echo ( new Media( $str ))->language->getFlagEmoji();
// 🇬🇧
```

**Edge Case:**  
May be `null` if not set.

---

### `public ?MediaType $type`
Represents the `TYPE` attribute as a [`MediaType`](MediaType.md) object, indicating the type of media (e.g., AUDIO, VIDEO, SUBTITLES).

**Example:**
```php
echo ( new Media( $str ))->mediaType;
// AUDIO
```

**Edge Case:**  
- May be `null` if not set.
- always returns upper-case.

---

### `public ?GroupId $groupId`
Represents the `GROUP-ID` attribute as a [`GroupId`](GroupId.md) object, grouping related media together.

**Example:**
```php
echo ( new Media( $str ))->groupId;
// audio-group-1
```

**Edge Case:**  
May be `null` if not set.

---

### `public ?Uri $uri`
Represents the `URI` attribute as a [`Uri`](Uri.md) object, specifying the location of the media resource.

**Example:**
```php
echo ( new Media( $str ))->uri;
// en.ac3
```

**Edge Case:**  
May be `null` for some media types (e.g., closed captions).

---

## Methods
### `__construct(string $rawMediaSyntax = '', int $options = 0)`
Initializes a Media object. Optionally parses a raw `#EXT-X-MEDIA` tag and sets options.

**Example:**
```php
$options = MasterPlaylist::HideNonStandardPropsInJson | MasterPlaylist::HideNullValuesInJson;

$subtitle = ( new Media( options: $options ))
    ->setType( 'SUBTITLE' )
    ->setLanguage( 'es' );
```

Or we can parse a raw media tag string:

```php
$media = new Media(
    '#EXT-X-MEDIA:TYPE=SUBTITLE,GROUP-ID="srt-group",NAME="English",LANGUAGE="en",URI="en.srt"',
	$options
);
```

**Best Practice:**  
Pass the raw tag string to auto-populate properties.

---

### `setDefault(string|bool $value): self`
Sets the `DEFAULT` attribute.

**Example:**
```php
$media
	->setDefault( 'YES' )
	->setDefault( 'true' )
	->setDefault( true );
```

**Best Practice:**
We can use `YES` or `NO` as strings, or `"true"` or `"false"` as strings or `true` or `false` booleans because when we set the default value, it will be converted into a [`Boolean`](Boolean.md) object and this class accepts those values.

---

### `setAutoSelect(string|bool $value): self`
Sets the `AUTOSELECT` attribute.

**Example:**
```php
$media->setAutoSelect( true );
```

**Best Practice:**
As above, we can use `YES` or `NO` as strings, or `"true"` or `"false"` as strings or `true` or `false` booleans because when we set the auto-select value, it will be converted into a [`Boolean`](Boolean.md) object and this class accepts those values.

---

### `setName(string $value): self`
Sets the `NAME` attribute.

**Example:**
```php
$media->setName( 'Spanish' );
```

---

### `setLanguage(string $value): self`
Sets the `LANGUAGE` attribute. It should be a 2-letter code.

**Example:**
```php
$media->setLanguage( 'es' );
```

---

### `setType(string $value): self`
Sets the `TYPE` attribute.

**Example:**
```php
$media->setType( 'SUBTITLES' );
```

---

### `setGroupId(string $value): self`
Sets the `GROUP-ID` attribute.

**Example:**
```php
$media->setGroupId( 'subs-group' );
```

---

### `setUri(string $value): self`
Sets the `URI` attribute.

**Example:**
```php
$media->setUri( 'subs/eng.m3u8' );
```

---

### `isOnSameGroup(?GroupId $target = null): bool`
Checks if this media belongs to the same group as the provided `GroupId`.

**Example:**
```php
$group = new GroupId( 'audio-group' );

if( $media->isOnSameGroup( $group ))
{
    // Same group
}
```

**Edge Case:**  
Returns `false` if `$target` is `null`.

---

### `parseRawSyntax(string $rawMediaSyntax): void`
Parses a raw `#EXT-X-MEDIA` tag and sets all recognized and custom properties.

**Example:**
```php
$media->parseRawSyntax(
	'#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="audio",NAME="English",LANGUAGE="en",DEFAULT=YES'
);
```

**Best Practice:**  
Call this method if you need to re-parse or update the object from a new tag.

---

## Serialization
### M3U8 Serialization
Serializes the media object to a valid `#EXT-X-MEDIA` tag.

**Example:**
```php
echo $media->toM3U8();
```

**Output:**
```
#EXT-X-MEDIA:TYPE=AUDIO,NAME="English",LANGUAGE="en",DEFAULT=YES,GROUP-ID="audio"
```

---

### String Conversion
It's an alias for `toM3U8()`. When we echo the media object, it will be converted to a string and output the M3U8 tag.

**Example:**
```php
echo $media;
```

---

### JSON Serialization
Serializes the media object to an array for use with `json_encode()`. Honors options for hiding properties.

**Example:**
```php
echo json_encode( $media, JSON_PRETTY_PRINT );
```

**Edge Case:**  
Depending on `$options`, some properties may be omitted from the output.

---

## Best Practices & Edge Cases

- Always check for `null` before accessing properties like `name`, language, `uri`, etc.
- Use the provided setter methods to ensure proper type wrapping (e.g., `setLanguage()` instead of setting `$language` directly).
- When parsing unknown or custom attributes, access them via `$nonStandardProps`.
- Use the `options` parameter to control JSON serialization output for different use cases (e.g., hiding nulls or non-standard properties).

---

The Media class is essential for any application that needs to read, write, or manipulate HLS master playlists, providing a flexible and extensible way to handle all attributes of the `#EXT-X-MEDIA` tag.
