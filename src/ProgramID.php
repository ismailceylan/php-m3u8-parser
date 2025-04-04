<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represent a program ID.
 */
class ProgramID implements M3U8Serializable, JsonSerializable
{
	/**
	 * The ID of the program.
	 *
	 * @var string
	 */
	public string $id;

	/**
	 * Initializes a new instance of the ProgramID class.
	 *
	 * @param string $value The value of the program ID.
	 */
	public function __construct( string $value )
	{
		$this->id = $value;
	}

	/**
	 * Converts the program ID to a string in the M3U8 format.
	 *
	 * The M3U8 format for the program ID is 'PROGRAM-ID=<id>'.
	 *
	 * @return string The program ID in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'PROGRAM-ID=' . $this->id;
	}

	/**
	 * @inheritDoc
	 * 
	 * Converts the program ID to a value that can be serialized natively by json_encode().
	 *
	 * @return string The program ID.
	 */
	public function jsonSerialize(): string
	{
		return $this->id;
	}
}
