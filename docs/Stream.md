# `Stream` Class
Streams Represents a variant stream in an M3U8 (HLS) master playlist, encapsulating its attributes, associated media, and segment playlist. Provides general information about the video, such as its resolution, codecs, and frame rate, as well as the URL or URI for the playlist containing the video segments.

In this library, we represent streams with the `Stream` class.

There are 3 ways to interact with a stream:

#### Instantiating the class directly and building streams scratch
This library supports building a stream from scratch and cast them into M3U8 or JSON representation.

```php
$stream = new Stream;
```

With this method, we will get an empty stream and then we can set its properties by using setter methods. This is how we can build a stream from scratch.

#### Instantiating the class with a M3U8 stream tag syntax and parsing it
Stream class supports parsing a valid `#EXT-X-STREAM-INF` tag syntax directly.

```php
$stream = new Stream(
	'#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=2122548,AVERAGE-BANDWIDTH=1542558,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1,FRAME-RATE=30,AUDIO="main",SUBTITLES="srt"'
);
```

However, please note that the text to be parsed must not contain any URL or URI lines. Stream class won't parse these. You can set the URL or URI later using the relevant setter methods.

Now we can access the properties of the stream and manipulate them with setter methods or get values from getter methods. 

#### Parsing a master playlist through the MasterPlaylist class
The [`MasterPlaylist`](MasterPlaylist.md) class can be used to parse a master playlist and get the streams from it.

---

## Namespace

```php
namespace Iceylan\M3U8;
```

---

## Implements

- `Iceylan\M3U8\Contracts\M3U8Serializable`
- `JsonSerializable`

---

## Usage Example

```php
$stream = new Stream();
$stream->setBandwidth( 1500000 )
       ->setResolution( 1280, 720 )
       ->setCodecs( 'avc1.4d401f', 'mp4a.40.2' )
       ->setUri( 'video/720p.m3u8' )
       ->setAudioGroup( 'audio-group' );

$stream->push( new Media );

echo $stream;
echo json_encode( $stream, JSON_PRETTY_PRINT );
```

---

## Constructor

```php
public function __construct(
    string $rawStreamSyntax = '',
    ?Closure $syncMedias = null,
    ?Url $url = null,
    ?Hooks $hooks = null,
    int $options = 0
)
```

- **`$rawStreamSyntax`**: Raw `#EXT-X-STREAM-INF` line (optional).
- **`$syncMedias`**: Callback for syncing media groups strategy (optional).
- **`$url`**: Base URL for resolving relative URIs (optional).
- **`$hooks`**: Hook system for custom event handling (optional).
- **`$options`**: Bitmask for controlling behavior (see [`MasterPlaylist`](MasterPlaylist.md) constants).

**Example:**

```php
$raw = '#EXT-X-STREAM-INF:BANDWIDTH=1280000,RESOLUTION=1280x720,AUDIO="audio-group"';
$url = new Iceylan\Urlify\Url('https://example.com/master.m3u8');
```

```php
$stream = new Stream( $raw, null, $url );
```

To avoid unnecessary nullification for the arguments, you can use labeled arguments.

**Example:**

```php
$stream = new Stream(
    rawStreamSyntax: $raw,
    url: $url
);
```

---

## Customizing URI Resolution and Formatting
The `Stream` class supports two important hook events that allow you to externally control how URIs are resolved and formatted. These hooks provide advanced flexibility for integrating with custom CDN logic, URL signing, or other URI transformation needs.

---

### 1. `resolve.segment-playlist-uri`
This hook is triggered when the stream needs to resolve the full URL for its segments playlist (for example, when loading segments or serializing to JSON). By registering a handler for this hook, you can control how the base URL and the relative URI are combined or transformed.

**Use Case:**  
Combine the base [URL](https://github.com/ismailceylan/urlify) and the URI, or inject custom logic for CDN, authentication, or rewriting.

**Example:**

```php
use Iceylan\Eventor\Event;
use Iceylan\Urlify\Url;

$stream->hook( 'resolve.segment-playlist-uri', function( Event $event )
{
  // extract the base URL and URI
  // uri is string
  // url is instance of Urlify Url
  list( $url, $uri ) = $event->getPayload();

  // Custom logic to combine or rewrite the URL and URI
  return $url->path->append( $uri );
});
```

**How it works:**  
- The hook receives the base URL and the URI as payload.
- It should return a string or an instance of the `Url` class representing the resolved URL.
- This resolved URL will be used for loading segments and in JSON serialization.

---

### 2. `format.toM3U8.segment-uri`
This hook is triggered when converting the stream to its M3U8 string representation (via `toM3U8()` or `__toString()`). It allows you to control how the URI appears in the generated playlist text, which is useful for formatting, obfuscation, or localization.

**Use Case:**  
Format or rewrite the URI as it appears in the M3U8 output.

**Example:**

```php
use Iceylan\Urlify\Path;

$stream->hook( 'format.toM3U8.segment-uri', function( $event )
{
  list( $url, $uri ) = $event->getPayload();

  // Custom formatting for the playlist output
  return ( new Path( $uri ))->getSegment( -1 );
});
```

**How it works:**  
- The hook receives the base URL and the URI as arguments.
- It should return a string representing the formatted URI.
- This formatted URI will be used in the output of `toM3U8()` and `__toString()`.

---

### Best Practices

- **Return a string:** Your hook callback must return a string representing the resolved or formatted URI.
- **Chainable:** You can register multiple hooks for the same event; they will be called in order of priority.
- **Separation of concerns:** Use `resolve.segment-playlist-uri` for backend URL logic, and `format.toM3U8.segment-uri` for presentation-layer formatting.

---

### Example: Using Both Hooks

```php
$stream
    ->setBaseUrl( 'https://cdn.example.com/streams' )
    ->setUri( '720p.m3u8' )
    ->hook( 'resolve.segment-playlist-uri', fn( $event ) => $url->path->append( $uri ))
    ->hook( 'format.toM3U8.segment-uri', fn( $event ) => base64_encode( $uri ));

echo $stream->toM3U8();
// #EXT-X-STREAM-INF:...
// NzIwcC5tM3U4 // (base64 of '720p.m3u8')
```

These hooks make the `Stream` class highly extensible for real-world streaming scenarios, letting you adapt URI handling to your infrastructure and security requirements.

---

## M3U8 Serialization

The `Stream` class can be serialized to the standard M3U8 playlist format using the [`toM3U8()`](#tom3u8-string) method or by casting the object to a string. This produces a valid `#EXT-X-STREAM-INF` entry, including all relevant attributes (such as bandwidth, resolution, codecs, etc.) and the URI for the segment playlist.

**Example:**

```php
echo $stream->toM3U8();
```

or

Output:

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1,FRAME-RATE=30,AUDIO="main",SUBTITLES="srt"
https://video.domain.com/paths/to/stream/720p.m3u8
```

You can customize how the URI appears in the output by registering a [`format.toM3U8.segment-uri`](#2-formattom3u8segment-uri) hook.

---

## JSON Serialization
The Stream class implements the JsonSerializable interface, allowing you to serialize it to JSON using json_encode(). The resulting JSON object includes all the stream's properties, such as URI, bandwidth, codecs, audio/subtitle groups, and any non-standard attributes.

Example:

```php
echo json_encode( $stream, JSON_PRETTY_PRINT );
```

Output:

```json
{
  "uri": "720p.m3u8",
  "url": "https://video.domain.com/paths/to/stream/720p.m3u8",
  "audios": [ ... ],
  "subtitles": [ ... ],
  "codecs": [ "avc1.42001E", "mp4a.40.2" ],
  "bandwidth": 642155,
  "averageBandwidth": 642155,
  "resolution": "1280x720",
  "programID": 1,
  "frameRate": 30,
  "segments": null,
  "nonStandardProps": {},
  "audioGroup": "main",
  "subtitleGroup": "srt"
}
```

**Edge Case:**
Serialization options can be controlled via the `$options` bitmask, allowing you to hide null values, empty arrays, or non-standard properties as needed.

---

## Methods

### `parseRawSyntax(string $rawStreamSyntax): self`
Parses a raw `#EXT-X-STREAM-INF` line and sets properties accordingly.

**Example:**

```php
$stream->parseRawSyntax('#EXT-X-STREAM-INF:BANDWIDTH=2560000,RESOLUTION=1920x1080');
```

**Edge Case:**  
Unknown attributes are stored in [`$nonStandardProps`](#accesing-nonstandard-attributes).

---

### `setBaseUrl(string|Url $url): self`
When we build a stream from scratch, we should provide the base URL. So that `Stream` class can resolve the relative path uri to the [segments playlist](SegmentPlaylist.md).

Sets the base URL for resolving relative URIs.

**Example:**

```php
echo $stream->setBaseUrl( 'https://video.domain.com/paths/to/streams' );
```

```
#EXT-X-STREAM-INF:
https://video.domain.com/paths/to/streams
```

---

### `setUri(string $value): self`
Sets the URI of the stream's segments playlist. If the `EagerLoadSegments` option is set, loads segments immediately.

**Example:**

```php
echo $stream->setUri( '720p.m3u8' );
```

```
#EXT-X-STREAM-INF:
https://video.domain.com/paths/to/streams/720p.m3u8
```

**Edge Case:**  
If the URI is invalid or empty, segment loading may fail.

---

### `setResolution(string|int $width, string|int $height): self`
Streams are video streams. Therefore, we can provide the resolution of the video.

**Example:**

```php
echo $stream->setResolution( width: 1280, height: '720' );
```

The value of `width` and `height` can be a string or an integer. This library will convert it to an integer if it is a string.

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `setBandwidth(int $bandwidth): self`
We can set the peak bandwidth of the stream. The value is in bits per second.

**Example:**

```php
echo $stream->setBandwidth( 642155 );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `setAverageBandwidth(int $averageBandwidth): self`
We can set the average bandwidth of the stream. The value of the average bandwidth is in bits per second.

The AVERAGE-BANDWIDTH parameter in #EXT-X-STREAM-INF tags within an M3U8 playlist helps players determine which stream to use, especially when dealing with multiple streams with varying quality (e.g., different resolutions or bitrates).

When a player needs to choose a stream, it considers the BANDWIDTH (maximum bandwidth) and the AVERAGE-BANDWIDTH values to make its decision.

If a stream has a BANDWIDTH of 2000000 (2 Mbps) and an AVERAGE-BANDWIDTH of 1500000 (1.5 Mbps), it means that the stream is expected to consume around 1.5 Mbps on average, while potentially peaking at 2 Mbps.

**Example:**

```php
echo $stream->setAverageBandwidth( 642155 );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `setCodecs(...$codecs): self`
We can set the codecs of the stream. The codecs should be given as strings. Multiple codecs can be provided.

**Example:**

```php
echo $stream->setCodecs( 'avc1.42001E', 'mp4a.40.2' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2"
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `setProgramID(string $programID): self`
We can set the program ID of the stream. The program ID should be given as a string. It provides a unique identifier for the stream.

**Example:**

```php
echo $stream->setProgramId( '1' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `setFrameRate(int|float|string $frameRate): self`
We can set the frame rate of the stream. The frame rate should be given as a string, integer, or float. It provides the number of frames per second.

**Example:**

```php
echo $stream->setFrameRate( '30' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1,FRAME-RATE=30
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `setAudioGroup(string $audioGroup): self`
We can set the audio group of the stream. The audio group should be given as a string. With the declaration of the audio group of the stream, only the audio streams of the same group can be pushable to the stream's audio list.

**Example:**

```php
echo $stream->setAudioGroup( 'dolby-atmos' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1,FRAME-RATE=30,AUDIO="dolby-atmos"
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `setSubtitleGroup(string $subtitleGroup): self`
We can set the subtitles group of the stream. It should be given as a string. With the declaration of the subtitles group of the stream, only the subtitles of the same group can be pushable to the stream's subtitles list.

**Example:**

```php
echo $stream->setSubtitleGroup( 'srt' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1,FRAME-RATE=30,AUDIO="main",SUBTITLES="srt"
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `push(Media $media): self`
Adds a Media object to the stream if it matches the audio or subtitle group.

**Example:**

```php
$audioMedia = new Media('audio', ...);
$stream->push($audioMedia);
```

**Edge Case:**  
If the media's group does not match, it is ignored.

---

### `withSegments(): self`
On the stream class, we have a method called `withSegments` that allows us to load the segments playlist when we want to and if it is not loaded yet.

**Example:**

```php
$stream = new Stream( '#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=2122548,FRAME-RATE=30' );

$stream
    ->setBaseUrl( 'https://video.domain.com/paths/to/stream/segments/' )
    ->setUri( '720p.m3u8' )
    ->withSegments();
```

After loading the remote segments playlist, we can get the segments playlist as an instance of the [`SegmentsPlaylist`](SegmentsPlaylist.md) class.

**Example:**

```php
$secondSegment = $stream->segments->get( 2 );

echo $secondSegment->uri;
// 720p/seg-002.ts

echo $secondSegment->getResolvedUrl();
// https://video.domain.com/paths/to/stream/segments/720p/seg-002.ts
```

**Edge Case:**  
If the URI or base URL is missing, segments will not be loaded.

---

### `push(Media $media): self`
Videos and video streams can have many different types of media. For example, audios, subtitles and other camera angles. All of these are represented in the m3u format with the #EXT-X-MEDIA tag. In this library, they are represented by the [Media](Media.md) class.

We add these media to the stream using the push method.

**Example:**

```php
$stream = new Stream( '#EXT-X-STREAM-INF:RESOLUTION=1280x720,FRAME-RATE=30&AUDIO="audio-group-1"' );

$stream
    ->setSubtitleGroup( 'subtitles' )
    ->push( new Media( '#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="audio-group-1",NAME="English",LANGUAGE="en",DEFAULT=YES,AUTOSELECT=YES,URI="audio-en.m3u8"' ))
    ->push( new Media( '#EXT-X-MEDIA:TYPE=SUBTITLES,GROUP-ID="subtitles",NAME="Spanish",LANGUAGE="es",AUTOSELECT=NO,URI="es.srt"' ));
```

The push method returns the stream object, which is useful for chaining.

The media object is stored in the stream's [audio list](#get-audios) and [subtitle list](#get-subtitles) if the media type is audio or subtitle and the group ids are the same with the stream's audio group id and subtitle group id. If the group ids are not the same, the given media will be ignored.

---

### `hook(string $event, callable $listener, int $priority = 0): self`
Registers a hook for custom event handling.

**Example:**

```php
$stream->hook('format.toM3U8.segment-uri', fn( $event ) =>
    $event->getPayload()[ 0 ] . '/' . $event->getPayload()[ 1 ]
);
```

---

### `toM3U8(): string`
Returns the stream as an M3U8 playlist entry.

**Example:**

```php
echo $stream->toM3U8();
```

---

### `__toString(): string`
Alias for `toM3U8()`.

**Example:**

```php
echo $stream;
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1,FRAME-RATE=30,AUDIO="main",SUBTITLES="srt"
https://video.domain.com/paths/to/stream/720p.m3u8
```

---

### `jsonSerialize(): array`
Returns an array suitable for `json_encode()`, with options to hide empty/null fields.

**Example:**

```php
echo json_encode($stream, JSON_PRETTY_PRINT);
```

**Edge Case:**  
Serialization respects options such as hiding empty arrays, nulls, or non-standard props.

---

## Properties
Stream class provides some getter methods to get values of the stream. Either we set properties manually or we parse them from a valid #EXT-X-STREAM-INF tag syntax directly, we always get the same values from the getter methods.

### Get URI
We can get the URI of the stream as an instance of the [`Uri`](Uri.md) class. This class provides some useful methods to let us interact with the URI's.

```php
echo $stream->uri;
// 720p.m3u8
```

### Get Bandwidth & Average Bandwidth
We can get the bandwidth and average bandwidth of the stream as an instance of the [`Bandwidth`](Bandwidth.md) class. This class provides some useful methods to let us interact with the bandwidth.

```php
echo $stream->bandwidth->toBytes();
// [259.1, 'KB']

echo $stream->averageBandwidth->toBits();
// [1.54, 'Mb']
```

### Get Resolution
We can get the resolution of the stream as an instance of the [`Resolution`](Resolution.md) class. This class provides some useful methods to let us interact with the resolution.

```php
echo $stream->resolution->height;
// 720
```

### Get Codecs
We can get the codecs of the stream as an instance of the [`CodecList`](CodecList.md) class. This class provides some useful methods to let us interact with the codecs.

```php
echo json_encode( $stream->codecs );
```

```json
[ "avc1.42001E", "mp4a.40.2" ]
```

### Get Program ID
We can get the program ID of the stream as an instance of the [`ProgramID`](ProgramID.md) class. This class provides some useful methods to let us interact with the program ID.

```php
echo $stream->programId;
// 1
```

### Get Frame Rate
We can get the frame rate of the stream as an instance of the [`FrameRate`](FrameRate.md) class. This class provides some useful methods to let us interact with the frame rate.

```php
echo $stream->frameRate;
// 30
```

### Get Audio Group
We can get the audio group of the stream as an instance of the [`GroupId`](GroupId.md) class. This class provides some useful methods to let us interact with the audio group.

```php
echo $stream->audioGroup->isEqual( $audio->groupId );
// true
```

### Get Subtitles Group
We can get the subtitles group of the stream as an instance of the [`GroupId`](GroupId.md) class. This class provides some useful methods to let us interact with the subtitles group.

```php
echo $stream->subtitleGroup->isEqual( $subtitle->groupId );
// true
```

### Get Audios
We can get the audios of the stream as an instance of the [`ObjectSet`](ObjectSet.md) class. This class behaves just like an audio set and guarantees that an [audio](Media.md) object appears only once in the list, ensuring that there are no duplicates.

```php
echo $stream->audios;
```

```
#EXT-X-MEDIA:TYPE=Audio,NAME="English",LANGUAGE="en",GROUP-ID="main"
#EXT-X-MEDIA:TYPE=Audio,NAME="Turkish",LANGUAGE="tr",GROUP-ID="main"
```

### Get Subtitles
This is the same as the audios, but for subtitles. We can get the subtitles of the stream as an instance of the [`ObjectSet`](ObjectSet.md) class.

```php
$subtitles = $stream->subtitles->toArray();
$flatten = fn( Media $subtitle ) => $subtitle->toM3U8();

var_dump( 
    array_map( $flatten, $subtitles )
);
```

```php
[
    '#EXT-X-MEDIA:TYPE=SUBTITLES,NAME="English",LANGUAGE="en",GROUP-ID="main"',
    '#EXT-X-MEDIA:TYPE=SUBTITLES,NAME="Turkish",LANGUAGE="tr",GROUP-ID="main"',
]
```

### Get Segments
The stream class is designed to represent a video variant or live video streaming. Each variant refers to a set of segments. These segments must be in a separate playlist file in m3u format. This playlist is called a segments playlist, and in this libray, we represent it with the [`SegmentsPlaylist`](SegmentsPlaylist.md) class.

We can access this playlist via streams.

```php
echo $stream->segments;
// null
```

The default value of this property is null. This is because segments playlists are not loaded by default unless you specifically demand it.

There are three ways to demand loading of the segments playlist.

* Using the [`withSegments`](#loading-segments-playlist) method on the stream
* Activating the eager loading of segments with the modifier when instantiating the stream class
* Activating the eager loading of segments in the [`MasterPlaylist`](MasterPlaylist.md#masterplaylisteagerloadsegments) class by modifiers

Let's see how we can declare in advance that the segments playlist must also be downloaded when instantiating the Stream class. You can follow the relevant links to learn about other methods.

```php
$stream = new Stream(
    options: MasterPlaylist::EagerLoadSegments
);
```

The use of labeled parameters is recommended because the stream class has some optional technical dependencies that it obtains from the argument tunnel in order to work in multiple formats and scenarios. By specifying which argument you are assigning a value to, you do not have to satisfy these dependencies one by one in order.

---

## Edge Cases & Best Practices
- **Missing URI:**  
  If you forget to set the URI, segment loading and M3U8 output may be incomplete.
- **Unknown Attributes:**  
  Any unknown attributes in the EXT-X-STREAM-INF tag are preserved in [`$nonStandardProps`](#accesing-nonstandard-attributes).
- **Media Group Mismatch:**  
  Only media with matching group IDs are added to `$audios` or `$subtitles`.
- **Serialization Options:**  
  Use the `$options` bitmask to control JSON output (e.g., hide nulls, empty arrays).
- **Hooks:**  
  Hooks allow you to customize URI formatting and resolution for advanced use cases.

---

### Accesing Nonstandard Attributes
There are some standard attributes in the m3u format like BITRATE or RESOLUTION. We physically store most of them in the library and stream class. However, there may also be attributes that are not standard on the #EXT-X-STREAM-INF tag. We store these types of attributes as key=>value pairs on a special property.

```php
$stream = new Stream( '#EXT-X-STREAM-INF:RESOLUTION=1280x720,FOO="bar"' );

echo $stream->nonStandardProps[ 'FOO' ];
// bar
```

---

### Options Bitmasking
The `$options` parameter in the `Stream` class constructor allows you to control advanced behaviors using bitmask flags. These options are defined as constants in the [`MasterPlaylist`](MasterPlaylist.md) class and can be combined using the bitwise OR operator (`|`). Bitmasking enables you to enable or disable multiple features at once.

#### Available Option Flags
Common option constants include:

- **`MasterPlaylist::EagerLoadSegments`**  
  Automatically loads the segments playlist when the URI is set.
- **`MasterPlaylist::HideNullValuesInJson`**  
  Omits properties with `null` values from the JSON output.
- **`MasterPlaylist::HideEmptyArraysInJson`**  
  Omits properties that are empty arrays (e.g., no audios or subtitles) from the JSON output.
- **`MasterPlaylist::HideNonStandardPropsInJson`**  
  Omits non-standard attributes from the JSON output.
- **`MasterPlaylist::HideGroupsInJson`**  
  Omits audio and subtitle group properties from the JSON output.

Refer to the [`MasterPlaylist`](MasterPlaylist.md) documentation for the full list of available flags.

---

#### How to Use
You can combine multiple options using the bitwise OR operator (`|`):

```php
$options = MasterPlaylist::EagerLoadSegments | MasterPlaylist::HideNullValuesInJson;
$stream = new Stream(
    rawStreamSyntax: $raw,
    url: $url,
    options: $options
);
```

---

## See Also
- [HLS Specification](https://datatracker.ietf.org/doc/html/rfc8216)
- [`MasterPlaylist`](MasterPlaylist.md) for option constants and playlist management
- Media, [`ObjectSet`](ObjectSet.md), [`SegmentsPlaylist`](SegmentsPlaylist.md) for related classes

---

**Note:**  
This class is designed for extensibility and robust parsing of HLS master playlists. Always validate input and handle exceptions for production use.
