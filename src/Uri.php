<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a URI.
 */
class Uri implements M3U8Serializable, JsonSerializable
{
	/**
	 * The URI.
	 *
	 * @var string
	 */
	public string $uri;

	/**
	 * Constructs a Uri object.
	 *
	 * @param string $uri [optional] The URI to set for the object.
	 */
	public function __construct( string $uri = '' )
	{
		$this->uri = $uri;
	}

	/**
	 * Converts the URI to a string in the M3U8 format.
	 *
	 * @return string The URI in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'URI="' . $this->uri . '"';
	}

	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @return string the URI as a string.
	 */
	public function jsonSerialize(): string
	{
		return $this->uri;
	}

	/**
	 * Returns the URI as a string.
	 *
	 * @return string The URI as a string.
	 */
	public function __toString(): string
	{
		return $this->uri;
	}
}
