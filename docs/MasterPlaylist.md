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
$master->push([ $stream, $lq_eng_sound, $hq_eng_sound, $subtitle ]);
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
Some providers sometimes offer a different master playlist for each stream variation instead of embedding all stream variations in a single master playlist. This is also a valid situation. In this case we can merge multiple master playlists into one and access all streams and medias in a single master playlist.

```php
$master = ( new MasterPlaylist )->merge(
    ( new MasterPlaylist )->loadRemote( "https://video.domain.com/path/to/720p.m3u8" ),
    ( new MasterPlaylist )->loadRemote( "https://video.domain.com/path/to/1080p.m3u8" )
);
```

The merge method accepts any number of master playlists as arguments and merges their streams and medias into current master playlist.

Please keep in mind that the group ID matching procedure will be run as a result of this operation. If you do not want the media in one master playlist to automatically match the streams in another master playlist, use the relevant setter methods to finalize the group definitions in these master playlists before merging, and then finally merge them.

If the provider has already defined group IDs that will not match between master playlists, you may not need to do anything.

### Exporting Master Playlists (Serialization)
Some times we may need to export the master playlist to a file or response a HTTP request with the m3u8 content.

```php
echo $master->toM3U8();
```

The `toM3U8` method returns the m3u8 content as a string. 

The output is:

```m3u8
#EXTM3U
#EXT-X-STREAM-INF:CODECS="avc1.42E01E,mp4a.40.2",RESOLUTION=1920x1080,AUDIO="5.1-surround",SUBTITLES="srt"
https://video.domain.com/path/to/1080p.m3u8
#EXT-X-STREAM-INF:CODECS="avc1.42E01E,mp4a.40.2",RESOLUTION=1280x720,AUDIO="5.1-surround",SUBTITLES="srt"
https://video.domain.com/path/to/720p.m3u8

#EXT-X-MEDIA:TYPE=AUDIO,GROUP-ID="5.1-surround",NAME="English",LANGUAGE="en",DEFAULT=YES,AUTOSELECT=YES,URI="audio-en.m3u8"
#EXT-X-MEDIA:TYPE=SUBTITLE,GROUP-ID="srt",NAME="Spanish",LANGUAGE="es",AUTOSELECT=NO,URI="es.srt"
```

### JSON Serialization
This class supports direct json serialization. 

```php
echo json_serialize( $master );
```

The output is:

```json
{
  "medias": [
    {
      "default": true,
      "autoSelect": true,
      "name": "English",
      "language": "en",
      "type": "audio",
      "uri": "audio-en.m3u8",
      "nonStandardProps": [],
      "groupId": "audio-group-1"
    }
  ],
  "streams": [
    {
      "uri": "240p.av1.mp4.m3u8",
      "url": "https://video-cf.xhcdn.com/eP6ub%2F8tcJS6He4htvSbnpM%2F0OFlf3rfLuECyhnL6VI%3D/104/1745946000/media=hls4/multi=256x144:144p:,426x240:240p:,854x480:480p:,1280x720:720p:,1920x1080:1080p:/024/096/596/240p.av1.mp4.m3u8",
      "audios": [],
      "subtitles": [],
      "codecs": null,
      "bandwidth": 162415,
      "averageBandwidth": null,
      "resolution": { "width": 426, "height": 240 },
      "programID": "1",
      "frameRate": null,
      "segments": null,
      "nonStandardProps": [],
      "audioGroup": null,
      "subtitleGroup": null
    },
    {
      "uri": "480p.av1.mp4.m3u8",
      "url": "https://video-cf.xhcdn.com/eP6ub%2F8tcJS6He4htvSbnpM%2F0OFlf3rfLuECyhnL6VI%3D/104/1745946000/media=hls4/multi=256x144:144p:,426x240:240p:,854x480:480p:,1280x720:720p:,1920x1080:1080p:/024/096/596/480p.av1.mp4.m3u8",
      "audios": [],
      "subtitles": [],
      "codecs": null,
      "bandwidth": 407523,
      "averageBandwidth": null,
      "resolution": { "width": 854, "height": 480 },
      "programID": "1",
      "frameRate": null,
      "segments": {
        "type": "VOD",
        "mapUri": "480p.av1.mp4/init-v1-a1.mp4",
        "mapUrl": "https://video-cf.xhcdn.com/eP6ub%2F8tcJS6He4htvSbnpM%2F0OFlf3rfLuECyhnL6VI%3D/104/1745946000/media=hls4/multi=256x144:144p:,426x240:240p:,854x480:480p:,1280x720:720p:,1920x1080:1080p:/024/096/596/480p.av1.mp4/init-v1-a1.mp4",
        "version": "6",
        "duration": 5,
        "segments": [
          {
            "duration": 4,
            "title": null,
            "uri": "480p.av1.mp4/seg-1-v1-a1.m4s",
            "url": "https://video-cf.xhcdn.com/eP6ub%2F8tcJS6He4htvSbnpM%2F0OFlf3rfLuECyhnL6VI%3D/104/1745946000/media=hls4/multi=256x144:144p:,426x240:240p:,854x480:480p:,1280x720:720p:,1920x1080:1080p:/024/096/596/480p.av1.mp4/seg-1-v1-a1.m4s"
          },
          {
            "duration": 4,
            "title": null,
            "uri": "480p.av1.mp4/seg-2-v1-a1.m4s",
            "url": "https://video-cf.xhcdn.com/eP6ub%2F8tcJS6He4htvSbnpM%2F0OFlf3rfLuECyhnL6VI%3D/104/1745946000/media=hls4/multi=256x144:144p:,426x240:240p:,854x480:480p:,1280x720:720p:,1920x1080:1080p:/024/096/596/480p.av1.mp4/seg-2-v1-a1.m4s"
          }
        ],
        "allowCache": true,
        "mediaSequence": 1,
        "mapByterange": null
      },
      "nonStandardProps": [],
      "audioGroup": null,
      "subtitleGroup": null
    },
    {
      "uri": "720p.av1.mp4.m3u8",
      "url": "https://video-cf.xhcdn.com/eP6ub%2F8tcJS6He4htvSbnpM%2F0OFlf3rfLuECyhnL6VI%3D/104/1745946000/media=hls4/multi=256x144:144p:,426x240:240p:,854x480:480p:,1280x720:720p:,1920x1080:1080p:/024/096/596/720p.av1.mp4.m3u8",
      "audios": [],
      "subtitles": [],
      "codecs": null,
      "bandwidth": 701183,
      "averageBandwidth": null,
      "resolution": { "width": 1280, "height": 720 },
      "programID": "1",
      "frameRate": null,
      "segments": null,
      "nonStandardProps": [],
      "audioGroup": null,
      "subtitleGroup": null
    }
  ]
}
```

#### JSON Modifiers
This library supports a few json modifiers that can be used to modify the output of the json representation of the master playlist.

We can use these modifiers one at a time or combine them with bitwise operations to get the desired json output.

##### MasterPlaylist::HideMediasInJson
In the master playlist we collect all the medias on the `medias` property. Because medias are part of the master playlist. On the other hand, medias are attached to streams theoretically by group IDs in m3u files. So we mimic that attachment under the hood by linking the medias to the streams as audios or subtitles if they're matched.

In light of this information, when we serialize the master playlist, the json output, by default, will contain all the medias duplicated, both those kept on the master playlist and those kept under streams. In some cases, this can be useful, but some cases, it can be a problem.

The `HideMediasInJson` modifier will hide the medias from the json output.

```php
$master = new MasterPlaylist(
    options: MasterPlaylist::HideMediasInJson
);
```

This way, only medias under streams will appear in the JSON output under audios or subtitles properties. As a result of this process, any media not owned by a stream will be lost. Therefore, you should use this feature with caution. If you need to access ownerless medias in the JSON output, you shouldn't use this modifier.

##### MasterPlaylist::HideNonStandardPropsInJson
The m3u format provides the features of an object with properties. Some of these properties are standard, and this library defines them natively on its classes. However, since you are not prohibited from creating your own properties in m3u, this library collects them under a special property so that you can access them.

If you are only working with standard features, you can hide this property and save a little space in the JSON output.

```php
$master = new MasterPlaylist(
    options: MasterPlaylist::HideNonStandardPropsInJson
);
```

##### MasterPlaylist::HideGroupsInJson
Streams declare the media groups they want to own, and medias declare the group they belong to. This library automatically matches these two definitions with each other using this information. When JSON output is generated, medias are stored under streams as "audios" or "subtitles". The entire hierarchy is available and clear under JSON. If this hierarchy is sufficient, you may not need the group IDs anymore. In this case, you can reduce the output size by removing them from the JSON output.

```php
$master = new MasterPlaylist(
    options: MasterPlaylist::HideGroupsInJson
);
```

As a result of this operation, the `audioGroup` and `subtitleGroup` properties on the streams, and the `groupId` property on the medias are removed from the JSON.

##### MasterPlaylist::HideNullValuesInJson
We defined the known attributes in the m3u format as physical properties in this library, but since some of them are not required by the m3u format, we set the default value of such properties to `null`.

The `HideNullValuesInJson` modifier will remove the properties that have `null` values from the JSON output.

```php
$master = new MasterPlaylist(
    options: MasterPlaylist::HideNullValuesInJson
);
```

This way, we can reduce the size of the JSON output a little bit.

##### MasterPlaylist::HideEmptyArraysInJson
The `HideEmptyArraysInJson` modifier will remove the properties that have empty arrays like `audios`, `subtitles`, `streams` and `medias` as values from the JSON output.

```php
$master = new MasterPlaylist(
    options: MasterPlaylist::HideEmptyArraysInJson
);
```

##### MasterPlaylist::PurifiedJson
This modifier activates all of the above modifiers at once. So, if you want to activate all JSON-related modifiers, instead of writing the following code:

```php
$master = new MasterPlaylist(
    options:
        MasterPlaylist::HideMediasInJson |
        MasterPlaylist::HideNonStandardPropsInJson |
        MasterPlaylist::HideGroupsInJson |
        MasterPlaylist::HideNullValuesInJson |
        MasterPlaylist::HideEmptyArraysInJson
);
```

You can write the following code:

```php
$master = new MasterPlaylist(
    options: MasterPlaylist::PurifiedJson
);
```

##### MasterPlaylist::EagerLoadSegments
This modifier activates the eager loading of segments. If you want to load the segments of all the streams in the master playlist at once, you can use this modifier. With other words, this modifier lets you load the segment playlists of streams eagerly.

```php
$master = new MasterPlaylist(
    options: MasterPlaylist::EagerLoadSegments
);
```

If you just want to load the segments of a specific stream, you can use the [`withSegments`](Stream.md#loading-segments) method on the specific stream.

### Properties
Master playlist has two properties that hold the streams and medias. These properties are both of type `StreamList` and `MediaList`. Through these specialized list classes, we can perform batch operations on the streams and medias. By accessing the properties we can access all streams and medias in the master playlist.

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
