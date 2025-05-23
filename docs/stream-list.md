## StreamList
This is a specialized list that manages the [stream](stream.md)s and allows us to perform batch operations on them.

### Initialization
If we want, we can instantiate the StreamList class directly.

```php
$streams = new StreamList();
```

But most of the time we don't need to, because we usually work directly with the [MasterPlaylist](master-playlist.md) class and it will generate a `StreamList` instance under the hood.

### Adding New Streams
Sometimes we might need to add a new [stream](stream.md) to the list.

```php
$streams->push( new Stream());
```

### Getting All Streams As Array
Sometimes we may only need the streams themselves as an array.

```php
$streams = $streams->all();
// [Stream, Stream, ...]
```

### Getting Stream By Index
Sometimes we just need the stream in a certain position.

```php
$stream = $streams->get( 1 );
```

### Checking If Streams Empty
We can check if the list is empty or not.

```php
echo $streams->isEmpty();
// true
```

### Attaching Media To Streams
[Media](media.md) definitions are kept on the same level as streams on the `MasterPlaylist`. But this is not enough because media definitions depend on streams. For this reason Each `Stream` keeps references to its associated media instances within itself.

Back to the stream list scenario, we can attach a `Media` instance to the whole `StreamList`. If the [group ID](group-id.md) of the media we gave, matches the [group ID](group-id.md)s of the streams in the list, it will only be attached to these streams.

```php
$streams->attach( new Media());
```

It's useful for relating new media definitions to an existing playlist by manually. If media definitions already exist in the master playlist, this is done for you under the hood when parsing the playlist.

### JSON Serialization
This class supports direct json serialization.

```php
echo json_encode( $streams );
```

The output is:

```json
[
	{
		// Stream properties...
	},
	{
		// Stream properties...
	},
	//...
]
```

### M3U8 Serialization
This class can produce M3U8 output.

```php
echo $streams->toM3U8();
```

The output is:

```m3u8
#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=602673,RESOLUTION=854x480,CODECS="mp4a.40.2,avc1.4d4015"
#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=1302491,RESOLUTION=1280x720,CODECS="mp4a.40.2,avc1.4d4015"
```

Actually the class doesn't have a special M3U8 scheme. It just triggers the `toM3U8` method of all the streams it has, concatenates them with the newline character and returns it.
