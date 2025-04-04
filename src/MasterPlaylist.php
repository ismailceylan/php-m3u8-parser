<?php

namespace Iceylan\M3U8;

/**
 * Represent a master playlist.
 */
class MasterPlaylist extends Playlist
{
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
}
