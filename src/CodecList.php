<?php

namespace Iceylan\M3U8;

use JsonSerializable;

/**
 * Represents a list of codecs.
 */
class CodecList implements JsonSerializable
{
	/**
	 * The list of codecs.
	 *
	 * @var array
	 */
	public array $codecs = [];

	/**
	 * Construct a CodecList from a raw string.
	 *
	 * @param string $codecString The raw string to parse.
	 */
	public function __construct( string $codecString )
	{
		$this->codecs = explode( '|', $codecString );
	}

	/**
	 * Converts the list of codecs to a string format.
	 *
	 * @return string The codecs concatenated into a single string separated by commas.
	 */
	public function __toString()
	{
		return implode( ',', $this->codecs );
	}

	/**
	 * @inheritDoc
	 * 
	 * Serializes the list of codecs to a value that can be serialized natively by json_encode().
	 */
	public function jsonSerialize(): array
	{
		return $this->codecs;
	}
}
