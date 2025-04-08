<?php

namespace Iceylan\M3U8;

use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represent a master playlist.
 */
class MasterPlaylist extends Playlist implements M3U8Serializable
{
    /**
     * The streams of the master playlist.
     *
     * @var array
     */
    public array $streams = [];

    /**
     * The medias of the master playlist.
     *
     * @var array
     */
    public array $medias = [];

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
                $stream = $this->streams[] = new Stream( $line );
                $stream->setUri( $lines[ $i + 1 ]);
                $i++;
            }
            // handle media definitions
            else if( str_starts_with( $line, '#EXT-X-MEDIA' ))
            {
                $this->medias[] = new Media( $line );
            }
            // skip comments or blank lines etc.
            else
            {
                // continue;
            }
        }
    }

    /**
     * Pushes a stream into the master playlist.
     *
     * @param Stream $stream The stream to be pushed.
     * @return self
     */
    public function pushStream( Stream $stream ): self
    {
        $this->streams[] = $stream;
        return $this;
    }

    /**
     * Pushes a media into the master playlist.
     *
     * @param Media $media The media to be pushed.
     * @return self
     */
    public function pushMedia( Media $media ): self
    {
        $this->medias[] = $media;
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
            $this->streams = array_merge( $this->streams, $list->streams );
            $this->medias = array_merge( $this->medias, $list->medias );
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
        implode(
            "\n",
            array_map(
                fn( $stream ) => $stream->toM3U8() . "\n" . $stream->uri,
                $this->streams
            )
        ) . "\n\n" .
        implode(
            "\n",
            array_map(
                fn( $media ) => $media->toM3U8(),
                $this->medias
            )
        );
    }
}
