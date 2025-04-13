<?php

namespace Iceylan\M3U8;

use Closure;
use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represent a master playlist.
 */
class MasterPlaylist extends Playlist implements M3U8Serializable, JsonSerializable
{
    /**
     * The streams of the master playlist.
     *
     * @var StreamList
     */
    public StreamList $streams;

    /**
     * The medias of the master playlist.
     *
     * @var MediaList
     */
    public MediaList $medias;

    /**
     * The options of the master playlist.
     *
     * @var integer
     */
    private int $options;

    /**
     * The visibility option of the medias property in json.
     *
     * @var integer
     */
    public const HideMediasInJson = 1;

    /**
     * The visibility option of the non standard properties in json.
     * 
     * @var integer
     */
    public const HideNonStandardPropsInJson = 2;

    /**
     * The visibility option of the groups property in json.
     * 
     * @var integer
     */
    public const HideGroupsInJson = 4;

    /**
     * The visibility option of the null values in json.
     * 
     * @var integer
     */
    public const HideNullValuesInJson = 8;

    /**
     * Constructs a MasterPlaylist.
     * 
     * @param integer $options options of the master playlist
     * @return void
     */
    public function __construct( int $options = 0 )
    {
        $this->streams = new StreamList;
        $this->medias = new MediaList;
        $this->options = $options;
    }

    /**
     * Checks if the given data contains the '#EXT-X-STREAM-INF:' tag.
     *
     * @param string $data The data to be checked.
     * @return bool True if the tag is found, false otherwise.
     */
	public function test( $data ): bool
	{
		return strpos( $data, '#EXT-X-STREAM-INF:' ) !== false;
	}

    /**
     * Parses a master playlist content.
     *
     * @param string $content The content of the master playlist.
     * @return void
     */
    public function parse( string $content ): void
    {
        $lines = explode( "\n", trim( $content ));

        // first one is magic bytes
        array_shift( $lines );

        for( $i = 0; $i < count( $lines ); $i ++ )
        {
            $line = trim( $lines[ $i ]);

            // handle streams
            if( str_starts_with( $line, '#EXT-X-STREAM-INF' ))
            {
                $this->push(
                    $stream = new Stream(
                        rawStreamSyntax: $line,
                        syncMedias: Closure::fromCallable([ $this, 'syncStreamMedias' ]),
                        options: $this->options
                    )
                );

                $stream->setUri( $lines[ $i + 1 ]);
                $i++;
            }
            // handle media definitions
            else if( str_starts_with( $line, '#EXT-X-MEDIA' ))
            {
                $this->push(
                    new Media(
                        rawMediaSyntax: $line,
                        options: $this->options
                    )
                );
            }
            // skip comments or blank lines etc.
            else
            {
                // continue;
            }
        }
    }

    /**
     * Synchronizes the medias of the given stream with the master playlist.
     *
     * It loops through all the medias in the master playlist and adds them to
     * the given stream.
     *
     * @param Stream $stream The stream to be synced.
     * @return void
     */
    private function syncStreamMedias( Stream $stream )
    {
        foreach( $this->medias->getMedias() as $media )
        {
            $stream->push( $media );
        }
    }

    /**
     * Adds a stream or media to the master playlist.
     *
     * If the provided item is a Media instance, it is added to the medias array.
     * If it is a Stream instance, it is added to the streams array.
     *
     * @param Stream|Media $entity The stream or media to be added.
     * @return self Returns the instance of the MasterPlaylist class.
     */
    public function push( Stream|Media $entity ): self
    {
        if( $entity instanceof Media )
        {
            $this->medias->push( $entity );
            $this->streams->attach( $entity );
        }
        else if( $entity instanceof Stream )
        {
            $this->streams->push( $entity );
            $this->medias->attach( $entity );
        }

        return $this;
    }

    /**
     * Merges the given master playlist with the current one.
     *
     * It adds all the streams and medias from the given master playlist to the current one.
     *
     * @param MasterPlaylist ...$playlist The master playlist to be merged.
     * @return self
     */
    public function merge( MasterPlaylist ...$playlist ): self
    {
        foreach( $playlist as $list )
        {
            foreach( $list->streams as $stream )
            {
                $this->push( $stream );
            }

            foreach( $list->medias as $media )
            {
                $this->push( $media );
            }
        }

        return $this;
    }

    /**
     * Returns the content of the master playlist as a string.
     *
     * @return string
     */
    public function toM3U8(): string
    {
        return "#EXTM3U\n" .
            $this->streams->toM3U8() . "\n\n" .
            $this->medias->toM3U8();
    }

    /**
     * Serializes the master playlist to a value that can be serialized natively by json_encode().
     *
     * @return array The serialized representation of the master playlist.
     */
    public function jsonSerialize(): array
    {
        $data =
        [
            'streams' => $this->streams,
        ];

        if( ! ( $this->options & self::HideMediasInJson ))
        {
            $data[ 'medias' ] = $this->medias;
        }

        return $data;
    }
}
