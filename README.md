# M3U8 Parser and Generator Library for PHP
With the help of this library, we can easily parse or generate M3U8 playlists for HTTP Live Streaming. It supports master playlists, segments playlists, and all standard attributes.

## Installation
Install via Composer:

```bash
composer require iceylan/m3u8
```

## Usage
```php
use Iceylan\M3U8\MasterPlaylist;

$master = ( new MasterPlaylist( MasterPlaylist::EagerLoadSegments ))
	->loadRemote( "https://video.domain.com/paths/master-playlist.m3u8" );
// MasterPlaylist instance
```

## Documentation
### Master Playlist
Master playlist is the playlist that contains all the stream variations. It is used to show the videos in different qualities, audios and subtitles in different languages etc.

In this library, we represent master playlists with the `MasterPlaylist` class. This class can also used to generate a master playlist from scratch.

```php
$master = new MasterPlaylist;
```

Some providers sometimes offer a different master playlist for each stream variation instead of embedding all stream variations in a single master playlist. This is also a valid situation. With the power of this library, we can merge multiple master playlists into one and work with them easily.

After a playlist content is parsed, streams and medias can be accessed through the `MasterPlaylist` instance and we can also do some operations with them. Let's see what streams can do.

#### Streams
`streams` property holds an instance of `StreamList` class. This is a specialized list that manages the streams and allows us to perform batch operations on them.

```php
$master->streams;
// StreamList instance
```

##### Getting All Streams As Array
Sometimes we may only need the streams themselves as an array.

```php
$streams = $master->streams->all();
// [Stream, Stream, ...]
```

##### Getting Stream By Index
Sometimes we just need the stream in a certain position.

```php
$stream = $master->streams->get( 1 );
```

##### Checking If Streams Empty
We can check if the list is empty or not.

```php
echo $master->streams->isEmpty();
// true
```

##### Adding New Streams
Sometimes we need to add a new stream to the list.

```php
$master->streams->push( new Stream());
```

##### Attaching Media To Streams
Media definitions are kept on the same level as streams on the `MasterPlaylist`. But this is not enough because media definitions depend on streams. For this reason Each `Stream` keeps references to its associated media instances within itself.

Back to the stream list scenario, we can attach a `Media` instance to the whole `StreamList`. If the group ID of the media we gave, matches the group IDs of the streams in the list, it will only be attached to these streams.

```php
$master->streams->attach( new Media());
```

It's useful for adding new media definitions to an existing playlist by manually. If media definitions already exist in the master playlist, this is done for you under the hood when parsing the playlist.

#### Medias
`medias` property holds an instance of `MediaList` class. This is a specialized list that manages the medias and allows us to perform batch operations on them.

```php
$master->medias;
// MediaList instance
```

#### Stream
streams represent videos. They provide general information about the video, such as resolution, codecs, frame rate and the url or uri for the playlist containing the video segments.

In this library, we represent streams with the `Stream` class.

Each stream can also declare a different group ID's for a subtitle group and an audio group. These IDs are used to associate the stream with its subtitles and audio files.

We can access the all streams through the `MasterPlaylist` instance.

```php
echo $master->streams->isEmpty();
```

streams property holds a `StreamList` class' instance 