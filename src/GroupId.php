<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a GROUP-ID.
 */
class GroupId implements M3U8Serializable, JsonSerializable
{
	/**
	 * The value of the GROUP-ID.
	 *
	 * @var string
	 */
	public string $value;

	/**
	 * Initializes a new instance of the GroupId class.
	 *
	 * @param string $value The value of the GROUP-ID.
	 */
	public function __construct( string $value )
	{
		$this->value = $value;
	}

	/**
	 * Converts the GROUP-ID to a string in the M3U8 format.
	 * The M3U8 format for the GROUP-ID is 'GROUP-ID=<value>'.
	 *
	 * @return string The GROUP-ID in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'GROUP-ID="' . $this->value . '"';
	}

	/**
	 * Converts the GROUP-ID to a value that can be serialized natively by json_encode().
	 *
	 * @return string The GROUP-ID.
	 */
	public function jsonSerialize(): string
	{
		return $this->value;
	}
}
