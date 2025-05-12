<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;
use Iceylan\Urlify\Url;

/**
 * Represents a segments playlist.
 */
class SegmentsPlaylist extends Playlist implements JsonSerializable, M3U8Serializable
{
	/**
	 * The segments of the playlist.
	 *
	 * @var Segment[]
	 */
	public $segments = [];

	/**
	 * The duration of the segments playlist.
	 *
	 * @var float|null
	 */
	public ?float $duration;

	/**
	 * Indicates if the segments playlist allows caching.
	 *
	 * @var boolean|null
	 */
	public ?bool $allowCache;

	/**
	 * The type of the segments playlist.
	 *
	 * @var string|null
	 */
	public ?string $type;

	/**
	 * The version of the segments playlist.
	 *
	 * @var string|null
	 */
	public ?string $version;

	/**
	 * The media sequence of the segments playlist.
	 *
	 * @var integer|null
	 */
	public ?int $mediaSequence;

	/**
	 * The map URI of the segments playlist.
	 *
	 * @var string|null
	 */
	public ?string $mapUri;

	/**
	 * The map byterange of the segments playlist.
	 *
	 * @var string|null
	 */
	public ?string $mapByterange;

	/**
	 * The hooks of the segments playlist.
	 *
	 * @var Hooks|null
	 */
	private ?Hooks $hooks;

	/**
	 * The master playlist's url.
	 *
	 * @var Url|null
	 */
	public ?Url $url = null;

	/**
	 * Constructs a segments playlist.
	 *
	 * @param Hooks|null $hooks The hooks of the segments playlist.
	 * @param Url|null $url The master playlist's url.
	 * @return void
	 */
	public function __construct( ?Hooks $hooks, ?Url $url )
	{
		$this->hooks = $hooks;
		$this->url = $url;
	}

    /**
     * Checks if the given data contains the '#EXTINF:' tag.
     *
     * @param string $data The data to be checked.
     * @return bool True if the tag is found, false otherwise.
     */
	public function test( string $data ): bool
	{
		return strpos( $data, '#EXTINF:' ) !== false;
	}

    /**
     * Parses the given content into a segments playlist.
     *
     * @param string $content The content of the playlist.
     * @return void
     */
	public function parse( string $content ): void
	{
		$lines = explode( "\n", trim( $content ));

        // first one is magic bytes
        array_shift( $lines );

		for( $i = 0; $i < count( $lines ); $i ++ )
        {
            $line = trim( $lines[ $i ]);

			if( str_starts_with( $line, '#EXT-X-TARGETDURATION:' ))
			{
				$this->setDuration((float) explode( ':', $line )[ 1 ]);
			}
			else if( str_starts_with( $line, '#EXT-X-ALLOW-CACHE:' ))
			{
				$this->setAllowCache( explode( ':', $line )[ 1 ] == 'YES' );
			}
			else if( str_starts_with( $line, '#EXT-X-PLAYLIST-TYPE:' ))
			{
				$this->setType( explode( ':', $line )[ 1 ]);
			}
			else if( str_starts_with( $line, '#EXT-X-VERSION:' ))
			{
				$this->setVersion( explode( ':', $line )[ 1 ]);
			}
			else if( str_starts_with( $line, '#EXT-X-MEDIA-SEQUENCE:' ))
			{
				$this->setMediaSequence( (int) explode( ':', $line )[ 1 ]);
			}
			else if( str_starts_with( $line, '#EXT-X-MAP' ))
			{
				$attrs = new AttributedTag( $line );

				$this->setMapUri( $attrs->get( 'URI' ));
				$this->setMapByterange( $attrs->get( 'BYTERANGE' ));
			}
			// handle segment
            else if( str_starts_with( $line, '#EXTINF:' ))
            {
				$this->push( 
					$segment = new Segment( 
						raw: $line, 
						hooks: $this->hooks, 
						url: $this->url 
					)
				);

				$segment->setUri( $lines[ $i + 1 ]);
				$i++;
			}
			// skip comments or blank lines etc.
			else
			{
				
			}
		}
	}

	/**
	 * Adds a Segment to the segments playlist.
	 *
	 * @param Segment $segment The Segment to be added.
	 * @return self Returns the current instance for method chaining.
	 */
	public function push( Segment $segment )
	{
		$this->segments[] = $segment;
		return $this;
	}

    /**
     * Sets the duration of the segments playlist.
     *
     * @param float $duration The duration in seconds.
     * @return self Returns the current instance for method chaining.
     */
	public function setDuration( float $duration ): self
	{
		$this->duration = $duration;
		return $this;
	}

    /**
     * Sets whether the client is allowed to cache the segments.
     *
     * @param bool $allowCache Whether the client is allowed to cache the segments.
     * @return self Returns the current instance for method chaining.
     */
	public function setAllowCache( bool $allowCache ): self
	{
		$this->allowCache = $allowCache;
		return $this;
	}

    /**
     * Sets the type of the segments playlist.
     *
     * @param string $type The type to set for the segments playlist.
     * @return self Returns the current instance for method chaining.
     */
	public function setType( string $type ): self
	{
		$this->type = $type;
		return $this;
	}

    /**
     * Sets the version of the segments playlist.
     *
     * @param string $version The version to set for the segments playlist.
     * @return self Returns the current instance for method chaining.
     */
	public function setVersion( string $version ): self
	{
		$this->version = $version;
		return $this;
	}

    /**
     * Sets the media sequence of the segments playlist.
     *
     * @param int $mediaSequence The media sequence to set for the segments playlist.
     * @return self Returns the current instance for method chaining.
     */
	public function setMediaSequence( int $mediaSequence ): self
	{
		$this->mediaSequence = $mediaSequence;
		return $this;
	}

    /**
     * Sets the URI of the Media Playlist that contains the I-Frame segment of the media.
     *
     * @param string $uri The URI of the Media Playlist that contains the I-Frame segment of the media.
     * @return self Returns the current instance for method chaining.
     */
	public function setMapUri( string $uri ): self
	{
		$this->mapUri = $uri;
		return $this;
	}

	/**
	 * Sets the byte range for the map URI of the segments playlist.
	 *
	 * @param ?string $byterange The byte range to set for the map URI.
	 * @return self Returns the current instance for method chaining.
	 */
	public function setMapByterange( ?string $byterange ): self
	{
		$this->mapByterange = $byterange;
		return $this;
	}

	/**
	 * Retrieves all segments in the playlist.
	 *
	 * @return Segment[] An array of segments contained in the playlist.
	 */
	public function all()
	{
		return $this->segments;
	}

	/**
	 * Retrieves the segment at the specified index in the playlist.
	 *
	 * @param int $index The index of the segment to retrieve.
	 * @return Segment The segment at the specified index.
	 */
	public function get( int $index ): Segment
	{
		return $this->segments[ $index ];
	}

	/**
	 * Resolves the map URI using a predefined hook.
	 *
	 * @return ?string The resolved URI if available, otherwise the original map URI.
	 */
	public function getResolvedMapUri(): ?string
	{
		$resolvedUrl = $this->hooks->trigger( 'resolve.segment-uri',
		[
			$this->url,
			$this->mapUri
		]);

		return $resolvedUrl
			? $resolvedUrl[ 0 ]
			: $this->mapUri;
	}

	/**
	 * Retrieves the number of segments in the playlist.
	 *
	 * @return int The number of segments in the playlist.
	 */
	public function count(): int
	{
		return count( $this->segments );
	}

	/**
	 * Converts the segments playlist to a string in the M3U8 format.
	 *
	 * The M3U8 format is a simple text format that consists of a list of lines
	 * where each line represents a segment in the playlist. Each line must
	 * contain the EXTINF and URI attributes of the segment separated by commas.
	 *
	 * @return string The segments playlist in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		$meta = [];

		if( isset( $this->duration ))
		{
			$meta[] = "#EXT-X-TARGETDURATION:" . $this->duration;
		}

		if( isset( $this->allowCache ))
		{
			$meta[] = "#EXT-X-ALLOW-CACHE:" . ( $this->allowCache? 'YES' : 'NO' );
		}

		if( isset( $this->type ))
		{
			$meta[] = "#EXT-X-PLAYLIST-TYPE:" . $this->type;
		}

		if( isset( $this->version ))
		{
			$meta[] = "#EXT-X-VERSION:" . $this->version;
		}

		if( isset( $this->mediaSequence ))
		{
			$meta[] = "#EXT-X-MEDIA-SEQUENCE:" . $this->mediaSequence;
		}

		if( isset( $this->mapUri ))
		{
			$byterange = isset( $this->mapByterange )
				? ',BYTERANGE="' . $this->mapByterange . '"'
				: '';
			
			$meta[] = '#EXT-X-MAP:URI="' . $this->mapUri . '"' . $byterange;
		}

		return "#EXTM3U\n" .
			implode( "\n", $meta ) . "\n" .
			implode( "\n", $this->segments ) . 
		"\n#EXT-X-ENDLIST";
	}

	/**
	 * Converts the segments playlist to an array.
	 *
	 * @return array The segments playlist as an array.
	 */
	public function toArray(): array
	{
		return $this->segments;
	}

	/**
	 * Converts the segments playlist to an array for JSON serialization.
	 *
	 * The resulting array contains the segments of the playlist.
	 *
	 * @return array The segments playlist as an array.
	 */
	public function jsonSerialize(): array
	{
		return [
			'duration' => $this->duration,
			'allowCache' => $this->allowCache,
			'type' => $this->type,
			'version' => $this->version,
			'mediaSequence' => $this->mediaSequence,
			'mapUri' => $this->mapUri,
			'mapByterange' => $this->mapByterange,
			'segments' => $this->toArray()
		];
	}
}
