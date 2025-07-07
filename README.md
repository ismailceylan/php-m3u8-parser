# M3U8 Parser and Generator Library for PHP
With the help of this library, we can easily parse or generate M3U8 playlists for HTTP Live Streaming. It supports master playlists, segments playlists, and all standard attributes.

## Installation
Install via Composer:

```bash
composer require iceylan/m3u8
```

## Usage
We can easily parse an existing M3U8 playlist:

```php
use Iceylan\M3U8\MasterPlaylist;

$url = "https://video.domain.com/paths/master-playlist.m3u8";
$options = MasterPlaylist::EagerLoadSegments | MasterPlaylist::PurifiedJson;

new MasterPlaylist( $options )->loadRemote( $url );
```

## Structure
* [`MasterPlaylist`](docs/MasterPlaylist.md): the playlist that contains all the stream and media variations
* [`SegmentsPlaylist`](docs/SegmentsPlaylist.md): the playlist that contains all the segments
* [`StreamList`](docs/StreamList.md): the list of streams
* [`MediaList`](docs/MediaList.md): the list of medias
* [`Segment`](docs/Segment.md): the segment that represents a video segment
* [`Stream`](docs/Stream.md): the stream that represents a video and its attributes and it's medias
* [`Media`](docs/Media.md): the media that represents a video and its attributes
