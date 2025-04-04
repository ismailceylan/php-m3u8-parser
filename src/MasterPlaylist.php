<?php

namespace Iceylan\M3U8;

/**
 * Represent a master playlist.
 */
class MasterPlaylist extends Playlist
{
    public array $streams = [];

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
            $stream = $this->streams[] = new Stream( $lines[ $i ]);
            $stream->setUri( $lines[ $i + 1 ]);
            $i++;
        }
    }
}
