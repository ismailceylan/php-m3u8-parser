<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a segment.
 */
class Segment implements JsonSerializable, M3U8Serializable
{
	/**
	 * The duration of the segment.
	 *
	 * @var float
	 */
	public float $duration;

	/**
	 * The title of the segment.
	 *
	 * @var string|null
	 */
	public ?string $title;

	/**
	 * The URI associated with the segment.
	 *
	 * @var Uri|null
	 */
	public ?Uri $uri;

	/**
	 * Constructs a new Segment object.
	 *
	 * @param string|null $raw The raw text of the segment.
	 */
	public function __construct( ?string $raw )
	{
		if( $raw !== null )
		{
			$this->parse( $raw );
		}
	}

	/**
	 * Parses the given raw text into segment data.
	 *
	 * @param string $raw The raw text of the segment.
	 */
	public function parse( string $raw )
	{
		[, $data ] = explode( ':', $raw );
		[ $duration, $title ] = explode( ',', $data );
		
		$this->duration = (float) $duration;
		$this->title = trim( $title ) ?: null;
	}


	/**
	 * Sets the URI for the segment.
	 *
	 * @param string $uri The URI to associate with the segment.
	 * @return self Returns the instance of the Segment class.
	 */
	public function setUri( string $uri ): self
	{
		$this->uri = new Uri( $uri );
		return $this;
	}

	/**
	 * Converts the segment to a string in the M3U8 format.
	 *
	 * The format is '#EXTINF:<duration>,<title>\n<uri>'.
	 *
	 * @return string The segment in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return '#EXTINF:' . sprintf( '%.3f', $this->duration ) . ',' . $this->title . "\n" . $this->uri;
	}

	/**
	 * Converts the segment to its string representation in the M3U8 format.
	 *
	 * @return string The segment in the M3U8 format as a string.
	 */
	public function __toString(): string
	{
		return $this->toM3U8();
	}

	/**
	 * Returns an array of the segment's data.
	 *
	 * The returned array will contain the following keys:
	 *
	 * - `duration`: The duration of the segment in seconds, as a float.
	 * - `title`: The title of the segment, as a string.
	 * - `uri`: The URI associated with the segment, as a string.
	 *
	 * @return array The segment's data as an array.
	 */
	public function toArray()
	{
		return [
			'duration' => $this->duration,
			'title' => $this->title,
			'uri' => $this->uri,
		];
	}

	/**
	 * Converts the segment to a value that can be serialized natively by json_encode().
	 *
	 * The resulting value is an array that contains the segment's properties.
	 *
	 * @return array The segment's properties.
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}
}
