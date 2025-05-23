## MasterPlaylist
Master playlist is the playlist that contains all the stream variations. It is used to show the videos in different qualities, audios and subtitles in different languages etc.

In this library, we represent master playlists with the `MasterPlaylist` class. This class can be used both to parse an existing playlist or to build a master playlist from scratch.

```php
$master = new MasterPlaylist;
```

Some providers sometimes offer a different master playlist for each stream variation instead of embedding all stream variations in a single master playlist. This is also a valid situation. With the power of this library, we can merge multiple MasterPlaylists into one and work with them easily.

After a playlist content is parsed, streams and medias can be accessed through the `MasterPlaylist` instance and we can also do some operations with them. Let's see what streams can do.

### Streams
`streams` property holds an instance of [`StreamList`](docs/stream-list.md) class. This is a specialized list that manages the streams and allows us to perform batch operations on them.

```php
$master->streams;
// StreamList instance
```

### Medias
`medias` property holds an instance of [`MediaList`](docs/media-list.md) class. This is a specialized list that manages the medias and allows us to perform batch operations on them.

```php
$master->medias;
// MediaList instance
```
