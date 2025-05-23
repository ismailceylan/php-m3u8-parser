## MediaList
This is a specialized list that manages the [media](media.md)s and allows us to perform batch operations on them.

### Initialization
If we want, we can instantiate the MediaList class directly.

```php
$medias = new MediaList;
```

But most of the time we don't need to, because we usually work directly with the [MasterPlaylist](master-playlist.md) class and it will generate a `MediaList` instance under the hood.

### Adding New Medias
Sometimes we need to add a new [media](media.md) to the list.

```php
$master->medias->push( new Media );
```

### Getting All Medias As Array
Sometimes we may only need the medias themselves as an array.

```php
$medias = $master->medias->all();
// [Media, Media, ...]
```

### Getting Media By Index
Sometimes we just need the media in a certain position.

```php
$media = $master->medias->get( 1 );
```

### Checking If Medias Empty
We can check if the list is empty or not.

```php
echo $master->medias->isEmpty();
// true
```

### Attaching Media To Medias
[Media](media.md) definitions are kept on the same level as medias on the `MasterPlaylist`. But this is not enough because media definitions depend on medias. For this reason Each `Media` keeps references to its associated media instances within itself.

Back to the media list scenario, we can attach a `Media` instance to the whole `MediaList`. If the [group ID](group-id.md) of the media we gave, matches the [group ID](group-id.md)s of the medias in the list, it will only be attached to these medias.

```php
$master->medias->attach( new Media );
```

It's useful for relating new media definitions to an existing playlist by manually. If media definitions already exist in the master playlist, this is done for you under the hood when parsing the playlist.

### JSON Serialization
This class supports direct json serialization.

```php
echo json_encode( $master->medias );
```

The output is:

```json
[
	{
		// Media properties...
	},
	{
		// Media properties...
	},
	//...
]
```

### M3U8 Serialization
This class can produce M3U8 output.

```php
echo $master->medias->toM3U8();
```

The output is:

```m3u8
#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=602673,RESOLUTION=854x480,CODECS="mp4a.40.2,avc1.4d4015"
#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=1302491,RESOLUTION=1280x720,CODECS="mp4a.40.2,avc1.4d4015"
```

Actually the class doesn't have a special M3U8 scheme. It just triggers the `toM3U8` method of all the medias it has, concatenates them with the newline character and returns it.
