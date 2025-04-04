<?php

namespace Iceylan\M3U8;

use ReflectionClass;

/**
 * Represent a playlist.
 */
abstract class Playlist
{
	abstract function test( string $data ): bool;
	abstract function parse( string $content ): void;

	/**
	 * Load a playlist from a raw string content.
	 * 
	 * @param string $content the content of the playlist
	 * 
	 * @throws \Exception if the content does not start with the M3U8 magic bytes
	 * @throws \Exception if the content does not pass the test implemented by the child class
	 */
	public function loadRaw( $content )
	{
		if( ! $this->hasMagicBytes( $content ))
		{
			throw new \Exception( "M3U8 content does not start with magic bytes!" );
		}

		if( ! $this->test( $content ))
		{
			$className = ( new ReflectionClass( $this ))->getShortName();
			throw new \Exception( "M3U8 content that cannot be considered $className!" );
		}

		$this->parse( $content );
	}

	/**
	 * Checks if the provided content starts with the M3U8 magic bytes.
	 *
	 * @param string $content The content to check.
	 * @return bool True if the content starts with the M3U8 magic bytes, false otherwise.
	 */
	private function hasMagicBytes( string $content ): bool
	{
		return substr( $content, 0, 8 ) === "#EXTM3U\n";
	}
}
