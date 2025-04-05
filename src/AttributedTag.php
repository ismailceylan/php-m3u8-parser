<?php

namespace Iceylan\M3U8;

/**
 * Parses and represents an EXT definition which has key-value pair attributes.
 */
class AttributedTag
{
	/**
	 * The tag of the EXT definition.
	 *
	 * @var string
	 */
	public string $tag;

	/**
	 * The attributes of the EXT definition.
	 *
	 * @var array
	 */
	public array $attributes = [];

    /**
     * Constructs an EXTParser instance from a raw string.
     *
     * Parses the main segments and pairs from the provided raw string.
     * Sets the tag, value, and properties of the EXT definition.
     *
     * @param string $raw The raw string representing the EXT definition.
     */
	public function __construct( string $raw )
	{
		[ $this->tag, $data ] = $this->parseMainSegments( $raw );
		$pairs = $this->parsePairs( $this->escapeCommasInsideQuotes( $data ));

		foreach( $pairs as $pair )
		{
			[ $key, $val ] = $this->parsePair( $pair );
			$this->attributes[ $key ] = $this->parsePipes( $val );
		}
	}

	/**
	 * Parses the main segments of the given raw syntax.
	 *
	 * Splits the raw syntax at the first occurrence of ':'.
	 * The part before ':' is considered as the tag, and the rest as data.
	 *
	 * @param string $rawSyntax The raw syntax to parse.
	 * @return array An array containing the tag and data segments.
	 */
	private function parseMainSegments( string $rawSyntax ): array
	{
		$parts = explode( ':', $rawSyntax );
		return [ array_shift( $parts ), implode( ':', $parts )];
	}

	/**
	 * Replaces commas with pipes inside double quotes in the given data.
	 *
	 * This is necessary because some M3U8 files contain commas inside double quotes
	 * which makes the data impossible to explode.
	 *
	 * @param string $data The data to normalize.
	 * @return string The normalized data.
	 */
	private function escapeCommasInsideQuotes( string $data ): string
	{
		return preg_replace_callback(
			'/"(.*)"/',
			fn( $m ) => str_replace( ',', '|', $m[ 1 ]),
			$data
		);
	}

	/**
	 * Parses a string into an array of pairs.
	 *
	 * Splits the provided string by commas to generate an array of key-value pairs.
	 *
	 * @param string $data The data string to parse into pairs.
	 * @return array An array of key-value pairs.
	 */
	private function parsePairs( string $data ): array
	{
		return explode( ',', $data );
	}

	/**
	 * Parses a key-value pair into an array of two elements.
	 *
	 * The first element is the key and the second element is the value.
	 *
	 * @param string $pair The key-value pair to explode.
	 * @return array The exploded array of key-value pair.
	 */
	private function parsePair( string $pair ): array
	{
		return explode( '=', $pair );
	}

	/**
	 * Parses a string into an array by splitting it at pipe characters.
	 *
	 * This method is used to transform a string where elements are separated
	 * by pipe ('|') characters into an array of those elements.
	 *
	 * @param string $data The string to be split by pipe characters.
	 * @return array|string An array of elements extracted from the string.
	 */
	private function parsePipes( string $data ): array|string
	{
		return strpos( $data, '|' ) !== false
			? explode( '|', $data )
			: $data;
	}
}
