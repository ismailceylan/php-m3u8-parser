<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a media type.
 */
class MediaType implements M3U8Serializable, JsonSerializable
{
	/**
	 * The type name of the media.
	 *
	 * @var string
	 */
	public string $type;

	/**
	 * Constructs a MediaType object from a type name.
	 *
	 * @param string $type the type name of the media
	 */
	public function __construct( string $type )
	{
		$this->type = $type;
	}

	/**
	 * Returns the media type as a string.
	 *
	 * @return string The media type.
	 */
	public function __toString()
	{
		return strtolower( $this->type );
	}

	/**
	 * Converts the media type to a string in the M3U8 format.
	 *
	 * The M3U8 format is 'TYPE=<type>'.
	 *
	 * @return string The media type in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'TYPE=' . $this->type;
	}

	/**
	 * @inheritDoc
	 * 
	 * Converts the media type to a value that can be serialized natively by json_encode().
	 * 
	 * @return string The media type.
	 */
	public function jsonSerialize(): string
	{
		return $this->__toString();
	}
}
