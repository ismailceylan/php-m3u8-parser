<?php

namespace Iceylan\M3U8;

use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a media in a master playlist.
 */
class Media implements M3U8Serializable
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
	 * The language attribute of the media.
	 *
	 * @var Language|null
	 */
	public ?Language $language = null;

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
			else if( $key === 'LANGUAGE' )
			{
				$this->language = new Language( $value );
			}
		}
	}

	/**
	 * Returns the content of the media as a M3U8 string.
	 *
	 * @return string
	 */
	public function toM3U8(): string
	{
		$data = [];

		if( $this->default )
		{
			$data[] = $this->default->toM3U8();
		}

		if( $this->autoSelect )
		{
			$data[] = $this->autoSelect->toM3U8();
		}

		if( $this->name )
		{
			$data[] = $this->name->toM3U8();
		}

		if( $this->language )
		{
			$data[] = $this->language->toM3U8();
		}

		return '#EXT-X-MEDIA:' . implode( ',', $data );
	}
}
