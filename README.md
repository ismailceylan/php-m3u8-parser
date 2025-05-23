# M3U8 Parser and Generator Library for PHP
With the help of this library, we can easily parse or generate M3U8 playlists for HTTP Live Streaming. It supports master playlists, media playlists, and all standard attributes.

## Installation
Install via Composer:

```bash
composer require iceylan/m3u8
```

## Usage
```php
use Iceylan\M3U8\MasterPlaylist;

$master = ( new MasterPlaylist( MasterPlaylist::PurifiedJson | MasterPlaylist::EagerLoadSegments ))
	->loadRemote( "https://video.domain.com/paths/master-playlist.m3u8" );
```

## Documentation
### Master Playlist
Master playlist is the playlist that contains all the stream variations. It is used to show the videos in different qualities, sounds and subtitles in different languages etc.

In this library, we represent master playlists with the `MasterPlaylist` class. This class can also used to generate a master playlist from scratch.

Some providers sometimes offer a different master playlist for each stream variation instead of embedding all stream variations in a single master playlist. This is also a valid situation.

After a playlist content is parsed, streams and medias can be accessed through the `MasterPlaylist` instance.

#### Streams
`streams` property holds an instance of `StreamList` class. It helps us to access all the streams in the master playlist and also helps us to add streams to the master playlist or attach medias with streams.

#### Stream
streams represent videos. They provide general information about the video, such as resolution, codecs, frame rate and the url or uri for the playlist containing the video segments.

In this library, we represent streams with the `Stream` class.

Each stream can also declare a different group ID's for a subtitle group and an audio group. These IDs are used to associate the stream with its subtitles and audio files.

We can access the all streams through the `MasterPlaylist` instance.

```php
echo $master->streams->isEmpty();
```

streams property holds a `StreamList` class' instance 