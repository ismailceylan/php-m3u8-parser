<?php

namespace Iceylan\M3U8;

use Exception;
use ReflectionClass;
use Iceylan\Urlify\Url;

/**
 * Represent a playlist.
 */
abstract class Playlist
{
    /**
     * The URL of the master playlist.
     *
     * @var Url|null
     */
    public ?Url $url = null;

	/**
	 * Test if the content is a valid M3U8 playlist.
	 *
	 * @param string $data
	 * @return boolean
	 */
	abstract function test( string $data ): bool;

	/**
	 * Parse the content of the playlist.
	 *
	 * @param string $content
	 * @return void
	 */
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
	 * Loads a playlist from a remote URL.
	 * 
	 * @param string $url The URL of the playlist.
	 * @throws \Exception if the content does not start with the M3U8 magic bytes
	 * @throws \Exception if the content does not pass the test implemented by the child class
	 * @throws \Exception if the remote playlist could not be loaded
	 */
	public function loadRemote( string $url )
	{
		$this->url = new Url( $url );

		set_error_handler( fn( $severity, $message ) =>
			error_reporting() & $severity &&
				throw new Exception( $message )
		);

		$content = file_get_contents( $url );

		restore_error_handler();

		if( $content === false )
		{
			throw new Exception( "Failed to load playlist from URL: $url" );
		}

		$this->loadRaw( $content );
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
