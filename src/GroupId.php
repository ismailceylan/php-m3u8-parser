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
	 * The name of the boolean value.
	 *
	 * @var string|null
	 */
	public ?string $key = null;

	/**
	 * Initializes a new instance of the GroupId class.
	 *
	 * @param string $value The value of the GROUP-ID.
	 * @param ?string $key The name of the boolean value.
	 */
	public function __construct( string $value, ?string $key = 'GROUP-ID' )
	{
		$this->value = $value;
		$this->key = $key;
	}

	/**
	 * Checks if the given GroupId is equal to the current instance.
	 *
	 * @param GroupId $groupId The GroupId to compare with.
	 * @return bool True if the given GroupId is equal, false otherwise.
	 */
	public function isEqual( GroupId $groupId ): bool
	{
		return $this->value === $groupId->value;
	}

	
	/**
	 * Converts the GROUP-ID to a string in the M3U8 format.
	 * The M3U8 format for the GROUP-ID is '<key>="<value>"'.
	 *
	 * @return string The GROUP-ID in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return $this->key . '="' .  $this->value . '"';
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
