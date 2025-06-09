<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\Urlify\Url;
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
	 * The hooks.
	 *
	 * @var Hooks|null
	 */
	private ?Hooks $hooks;

	/**
	 * The url.
	 *
	 * @var Url|null
	 */
	private ?Url $url;

	/**
	 * The options.
	 *
	 * @var int|null
	 */
	private ?int $options;
	
	/**
	 * Constructs a new Segment object.
	 *
	 * @param string|null $raw The raw text of the segment.
	 * @param Hooks|null $hooks The hooks.
	 * @param Url|null $url The url.
	 * @param int|null $options The options.
	 */
	public function __construct( ?string $raw, ?Hooks $hooks, ?Url $url, ?int $options )
	{
		$this->options = $options;
		$this->hooks = $hooks;
		$this->url = $url;
	
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
	 * Retrieves the resolved URL of the segment.
	 *
	 * This method triggers the 'resolve.segment-uri' hook to attempt to resolve
	 * the segment's URI. If a resolved URL is obtained, it returns the first
	 * element of the resolved URL array. If not, it defaults to returning the
	 * segment's original URI.
	 *
	 * @return string The resolved URL of the segment or the original URI if resolution fails.
	 */
	public function getResolvedUrl(): string
	{
		$resolvedUrl = $this->hooks->trigger( 'resolve.segment-uri',
		[
			$this->url,
			$this->uri
		]);

		return $resolvedUrl
			? $resolvedUrl[ 0 ]
			: $this->uri;
	}

	/**
	 * Retrieves the formatted URI of the segment.
	 *
	 * This method triggers the 'format.toM3U8.segment-uri' hook to attempt to
	 * format the segment's URI. If a formatted URI is obtained, it returns the
	 * first element of the formatted URI array. If not, it defaults to returning
	 * the segment's original URI.
	 *
	 * @return string The formatted URI of the segment or the original URI if
	 *     formatting fails.
	 */
	public function getFormattedUri(): string
	{
		$formattedUrl = $this->hooks->trigger( 'format.toM3U8.segment-uri',
		[
			$this->url,
			$this->uri
		]);

		return $formattedUrl
			? $formattedUrl[ 0 ]
			: $this->uri;
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
		return '#EXTINF:' . sprintf( '%.3f', $this->duration ) . ',' . $this->title . "\n" . $this->getFormattedUri();
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
			'url' => $this->getResolvedUrl()
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
		$data = $this->toArray();

		if( $this->options & MasterPlaylist::HideNullValuesInJson )
		{
			if( is_null( $data[ 'title' ]))
			{
				unset( $data[ 'title' ]);
			}
		}

		return $data;
	}
}
