# FrameRate
The `FrameRate` class, encapsulates the concept of a video frame rate for use in M3U8 playlists (HLS streaming). Frame rate, measured in frames per second (fps), is a key attribute in video streaming that affects playback smoothness and compatibility. This class provides a type-safe, serializable, and M3U8-compliant way to represent and manipulate frame rates in PHP applications.

The class implements both the `M3U8Serializable` and `JsonSerializable` interfaces, making it easy to convert frame rate values to M3U8 attribute strings and JSON, respectively.

---

## Properties
### `float $fps`
Stores the frame rate value as a floating-point number.

**Usage Example:**
```php
echo ( new FrameRate( 29.97 ))->fps;
// 29.97
```

---

## Methods
### `__construct(string|float|int $fps = 0.0)`
Initializes the `FrameRate` object with a frame rate value. Accepts string, float, or integer input and casts it to float.

**Parameters:**
- `$fps`: The frame rate value (string, float, or int). Defaults to `0.0`.

**Usage Example:**
```php
$frameRate1 = new FrameRate( 24 );
$frameRate2 = new FrameRate( "29.97" );
$frameRate3 = new FrameRate( 60.0 );
```

**Edge Cases:**  
- Passing a non-numeric string will result in `0.0`.
- Negative values are accepted but may not be meaningful for video.

---

## Serialization
### M3U8 Serialization
Returns the frame rate formatted as an M3U8 attribute string, e.g., `FRAME-RATE=29.97`.

**Returns:**  
- `string`: The frame rate in M3U8 format.

**Usage Example:**
```php
echo ( new FrameRate( 29.97 ))->toM3U8();
// FRAME-RATE=29.97
```

**Best Practices:**  
- Use this method when generating M3U8 playlists or attributes.

---

### JSON Serialization
Serializes the frame rate value for use with `json_encode()`.

**Returns:**  
- `float`: The frame rate value.

**Usage Example:**
```php
echo json_encode(
[
	'fps' => new FrameRate( 23.976 )
]);
// Output: 24
```

**Best Practices:**  
- Useful for APIs or storage where the frame rate needs to be represented as a number.
