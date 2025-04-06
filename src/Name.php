<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a name.
 */
class Name implements M3U8Serializable, JsonSerializable
{
	/**
	 * The name.
	 *
	 * @var string|null
	 */
	public ?string $value = null;

	/**
	 * Initializes a new instance of the Name class.
	 *
	 * @param string|null $value The value of the name.
	 */
	public function __construct( ?string $value = null )
	{
		$this->value = $value;
	}

	/**
	 * Converts the name to a string in the M3U8 format.
	 *
	 * The M3U8 format for the name is 'NAME="<name>"'.
	 *
	 * @return string The name in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'NAME="' . $this->value . '"';
	}

	/**
	 * Serializes the name for JSON encoding.
	 *
	 * Implements the JsonSerializable interface's jsonSerialize method
	 * to return the name, allowing the object to be properly
	 * encoded to JSON.
	 *
	 * @return string The name, which will be encoded in JSON.
	 */
	public function jsonSerialize(): string
	{
		return $this->value;
	}
}
