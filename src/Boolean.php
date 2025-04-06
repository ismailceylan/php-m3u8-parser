<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a boolean value.
 */
class Boolean implements M3U8Serializable, JsonSerializable
{
	/**
	 * The boolean value.
	 *
	 * @var boolean|null
	 */
	public ?bool $value = null;

	/**
	 * Initializes a Boolean object with the given value.
	 *
	 * @param string|bool $value The value to initialize the object with. 
	 *                           If a string is provided, it will be converted 
	 *                           to lowercase. The value can be 'yes', 'no', 
	 *                           true, or false. Other values will result in null.
	 */
	public function __construct( string|bool $value )
	{
		if( is_string( $value ))
		{
			$value = strtolower( $value );
		}
		
		$map =
		[
			'yes' => true,
			'no' => false,
			true => true,
			false => false
		];

		$this->value = $map[ $value ] ?? null;
	}

	/**
	 * Converts the boolean value to a string in the M3U8 format.
	 *
	 * The M3U8 format for the boolean is 'DEFAULT=YES' if the value is true,
	 * and 'DEFAULT=NO' if the value is false.
	 *
	 * @return string The boolean value in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'DEFAULT=' . $this->value? 'YES' : 'NO';
	}

	/**
	 * Serializes the boolean value for JSON encoding.
	 *
	 * Implements the JsonSerializable interface's jsonSerialize method
	 * to return the boolean value, allowing the object to be properly
	 * encoded to JSON.
	 *
	 * @return mixed The boolean value, which will be encoded in JSON.
	 */
	public function jsonSerialize(): mixed
	{
		return $this->value;
	}
}
