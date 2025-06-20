# Stream
Streams represent videos. They provide general information about the video, such as its resolution, codecs, and frame rate, as well as the URL or URI for the playlist containing the video segments.

In this library, we represent streams with the `Stream` class.

There are 3 ways to interact with a stream:

* Instantiating the class directly and building streams scratch
* Instantiating the class with a M3U8 stream tag syntax and parsing it
* parsing a master playlist through the `MasterPlaylist` class

## Building a Stream From Scratch
This library supports building a stream from scratch and cast them into M3U8 or JSON representation.

```php
$stream = new Stream;
```

With this method, we will get an empty stream and then we can set its properties manually. This is how we can build a stream from scratch.

### Base URL
When we build a stream from scratch, we should provide the base URL. So that `Stream` class can resolve the relative path uri to the [segments playlist](SegmentPlaylist.md).

```php
$stream->setBaseUrl( 'https://video.domain.com/paths/to/stream' );
```

```
#EXT-X-STREAM-INF:
https://video.domain.com/paths/to/stream
```

### Stream URI
Stream uri holds the relative path to the segments playlist.

```php
$stream->setUri( '720p.m3u8' );
```

```
#EXT-X-STREAM-INF:
https://video.domain.com/paths/to/stream/720p.m3u8
```

### Resolution
Streams are video streams. Therefore, we can provide the resolution of the video.

```php
$stream->setResolution( width: 1280, height: '720' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720
https://video.domain.com/paths/to/stream/720p.m3u8
```

The value of `width` and `height` can be a string or an integer. This library will convert it to an integer if it is a string.

### Bandwidth
We can set the bandwidth of the stream. The value of the bandwidth is in bits per second.

```php
$stream->setBandwidth( 642155 );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155
https://video.domain.com/paths/to/stream/720p.m3u8
```

### Average Bandwidth
We can set the average bandwidth of the stream. The value of the average bandwidth is in bits per second.

The AVERAGE-BANDWIDTH parameter in #EXT-X-STREAM-INF tags within an M3U8 playlist helps players determine which stream to use, especially when dealing with multiple streams with varying quality (e.g., different resolutions or bitrates).

When a player needs to choose a stream, it considers the BANDWIDTH (maximum bandwidth) and the AVERAGE-BANDWIDTH values to make its decision.

If a stream has a BANDWIDTH of 2000000 (2 Mbps) and an AVERAGE-BANDWIDTH of 1500000 (1.5 Mbps), it means that the stream is expected to consume around 1.5 Mbps on average, while potentially peaking at 2 Mbps.

```php
$stream->setAverageBandwidth( 642155 );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155
https://video.domain.com/paths/to/stream/720p.m3u8
```

### Codecs
We can set the codecs of the stream. The codecs should be given as strings. Multiple codecs can be provided.

```php
$stream->setCodecs( 'avc1.42001E', 'mp4a.40.2' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2"
https://video.domain.com/paths/to/stream/720p.m3u8
```

### Program ID
We can set the program ID of the stream. The program ID should be given as a string. It provides a unique identifier for the stream.

```php
$stream->setProgramId( '1' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1
https://video.domain.com/paths/to/stream/720p.m3u8
```

### Frame Rate
We can set the frame rate of the stream. The frame rate should be given as a string, integer, or float. It provides the number of frames per second.

```php
$stream->setFrameRate( '30' );
```

```
#EXT-X-STREAM-INF:RESOLUTION=1280x720,BANDWIDTH=642155,AVERAGE-BANDWIDTH=642155,CODECS="avc1.42001E,mp4a.40.2",PROGRAM-ID=1,FRAME-RATE=30
https://video.domain.com/paths/to/stream/720p.m3u8
```

### Audio Group
We can set the audio group of the stream. The audio group should be given as a string. It provides a unique identifier for the audio group.