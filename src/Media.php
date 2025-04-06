<?php

namespace Iceylan\M3U8;

/**
 * Represents a media in a master playlist.
 */
class Media
{
	/**
	 * The default attribute of the media.
	 *
	 * @var Boolean|null
	 */
	public ?Boolean $default = null;

	/**
	 * The autoSelect attribute of the media.
	 *
	 * @var Boolean|null
	 */
	public ?Boolean $autoSelect = null;

	/**
	 * The forced attribute of the media.
	 *
	 * @var Name|null
	 */
	public ?Name $name = null;
	
	/**
	 * Constructs a Media object from a raw M3U8 media syntax.
	 *
	 * @param string $rawMediaSyntax The raw M3U8 EXT-X-MEDIA syntax.
	 */
	public function __construct( string $rawMediaSyntax = '' )
	{
		if( $rawMediaSyntax )
		{
			$this->parseRawSyntax( $rawMediaSyntax );
		}
	}

	/**
	 * Parses a raw M3U8 EXT-X-MEDIA syntax and sets the properties of the Media instance.
	 *
	 * @param string $rawMediaSyntax The raw M3U8 EXT-X-MEDIA syntax.
	 * @return void
	 */
	public function parseRawSyntax( string $rawMediaSyntax ): void
	{
		$tag = new AttributedTag( $rawMediaSyntax );

		foreach( $tag->attributes as $key => $value )
		{
			$key = strtoupper( $key );

			if( $key === 'DEFAULT' )
			{
				$this->default = new Boolean( $value, $key );
			}
			else if( $key === 'AUTOSELECT' )
			{
				$this->autoSelect = new Boolean( $value, $key );
			}
			else if( $key === 'NAME' )
			{
				$this->name = new Name( $value );
			}
		}
	}
}
