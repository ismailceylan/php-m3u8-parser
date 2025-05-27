## MasterPlaylist
Master playlist is the playlist that contains all the stream variations. It is used to show the videos in different qualities, audios and subtitles in different languages etc.

In this library, we represent master playlists with the `MasterPlaylist` class. This class can be used both to parse an existing playlist or to build a master playlist from scratch.

Some providers sometimes offer a different master playlist for each stream variation instead of embedding all stream variations in a single master playlist. This is also a valid situation. With the power of this library, we can merge multiple MasterPlaylists into one and work with them easily.

After a playlist content is parsed, streams and medias can be accessed through the `MasterPlaylist` instance and we can also do some operations with them. Let's see what streams can do.

### Building Mode
We can build master playlists from scratch.

```php
$master = new MasterPlaylist;
```

Now we are ready to add [Stream](Stream.md)s and [Media](Media.md)s to the playlist.

```php
$stream = ( new Stream )
    ->setResolution( 1980, 1080 )
    ->setAudioGroup( "5.1-surround" ) // only accepts 5.1 surround audios
    ->setSubtitleGroup( "srt" ); // only accepts srt subtitles
```

We created a stream and set the resolution and group IDs. With these IDs, the stream declares that only 5.1 surround audios and srt subtitles can belong to it.

Now let's create a subtitle.

```php
$subtitle = ( new Media )
    ->setType( "Subtitle" )
    ->setGroupId( "srt" );
```

Now we have created a subtitle with the help of the media class and put it in a group called srt. Let's now define a high quality 5.1 sound.

```php
$hq_eng_sound = ( new Media )
    ->setName( "English" )
    ->setLanguage( "en" )
    ->setType( "Audio" )
    ->setGroupId( "5.1-surround" );
```

We have now defined an audio that belongs to the `5.1-surround` group, in English. Now let's define a low quality sound to understand the difference.

```php
$lq_eng_sound = ( new Media )
    ->setName( "English" )
    ->setLanguage( "en" )
    ->setType( "Audio" )
    ->setGroupId( "stereo" );
```

This time we created an audio, again in English, but in the stereo group. Now we are ready to place all pieces on the master playlist.

```php
$master
    ->push( $stream )
    ->push( $lq_eng_sound )
    ->push( $hq_eng_sound )
    ->push( $subtitle );
```

The push method recognizes the type of object being sent and adds it to the list held on the streams or medias properties on `MasterPlaylist`. 

Also the subtitle and audio we added was associated with the matching stream under the hood.

```php
echo json_serialize( $stream->audios );
```

The output is:

```json
[
    {
        "name": "English",
        "language": "en",
        "type": "audio",
        "groupId": "5.1-surround"
    }
]
```

As you noticed, `stereo` audio was not attached to the stream because the stream only wanted a relationship with the `5.1-surround` audio group.

```php
echo json_serialize( $stream->subtitles );
```

The output is: 

```json
[
    {
        "type": "subtitle",
        "groupId": "srt"
    }
]
```

### Parsing Mode
If we have the m3u file physically, we can parse it and access all stream and media definitions as native PHP resources.

If we have the file physically on our server, we need to read its contents from disk.

```php
$master = ( new MasterPlaylist )->loadRaw(
    file_get_content( "path/to/master-playlist.m3u8" )
);
```

If the source is on a remote server, the library will download the source for us.

```php
$master = ( new MasterPlaylist )->loadRemote(
    "https://video.domain.com/path/to/master-playlist.m3u8"
);
```

The given url is then also used to resolve the relative uri's in the master playlist and segments playlist.

### Merging Master Playlists
This library makes it easy to create multiple master playlists and merge them into one.

```php
$master = ( new MasterPlaylist )->merge(
    ( new MasterPlaylist )->loadRemote( "https://video.domain.com/path/to/master-playlist-1.m3u8" ),
    ( new MasterPlaylist )->loadRemote( "https://video.domain.com/path/to/master-playlist-2.m3u8" )
);
```

The merge method returns a new master playlist that contains all the streams and medias from the given master playlists.

### Properties
#### Streams
`streams` property holds an instance of [`StreamList`](StreamList.md) class. This is a specialized list that manages the streams and allows us to perform batch operations on them.

We can access all streams in the master playlist through the `streams` property.

```php
$master->streams;
// StreamList instance
```

#### Medias
`medias` property holds an instance of [`MediaList`](MediaList.md) class. This is a specialized list that manages the medias and allows us to perform batch operations on them.

We can access all medias in the master playlist through the `medias` property.

```php
$master->medias;
// MediaList instance
```
