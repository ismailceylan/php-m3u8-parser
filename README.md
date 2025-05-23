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

$master = ( new MasterPlaylist( MasterPlaylist::EagerLoadSegments ))
	->loadRemote( "https://video.domain.com/paths/master-playlist.m3u8" );
// MasterPlaylist instance
```

## Structure
* [`MasterPlaylist`](docs/MasterPlaylist.md): the playlist that contains all the stream and media variations
* [`Stream`](docs/Stream.md): 
* [`StreamList`](docs/StreamList.md):
* [`MediaList`](docs/MediaList.md):
