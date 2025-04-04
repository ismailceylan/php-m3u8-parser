<?php

namespace Iceylan\M3U8;

/**
 * Represent a stream.
 */
class Stream
{
	/**
	 * The properties of the stream.
	 * 
	 * @var array
	 */
	public array $properties = [];

	/**
	 * The URI of the stream.
	 * 
	 * @var string|null
	 */
	public ?string $uri = null;

	/**
	 * The bandwidth of the stream.
	 *
	 * @var Bandwidth
	 */
	public Bandwidth $bandwidth;

	/**
	 * The resolution of the stream.
	 *
	 * @var Resolution
	 */
	public Resolution $resolution;

	/**
	 * The codecs of the stream.
	 *
	 * @var CodecList
	 */
	public CodecList $codecs;

	/**
	 * Construct a stream from a raw M3U8 stream syntax.
	 *
	 * @param string $rawM3U8StreamSyntax The raw M3U8 EXT-X-STREAM-INF syntax.
	 */
	public function __construct( string $rawM3U8StreamSyntax = '' )
	{
		if( $rawM3U8StreamSyntax )
		{
			$this->parseXStreamInf( $rawM3U8StreamSyntax );
		}
	}

	public function parseXStreamInf( string $rawM3U8StreamSyntax ): self
	{
		if( ! $this->hasMagicBytes( $rawM3U8StreamSyntax ))
		{
			throw new \Exception( "Stream syntax does not start with magic bytes!" );
		}

		$data = $this->getDataSegment( $rawM3U8StreamSyntax );
		$normalized = $this->normalizeQuotes( $data );
		$pairs = $this->getPairs( $normalized );

		foreach( $pairs as $pair )
		{
			$this->setProperty( ...$this->parsePair( $pair ));
		}

		return $this;
	}

	/**
	 * Set the URI of the stream.
	 * 
	 * @param string $uri The URI to set for the stream.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setUri( string $uri ): self
	{
		$this->uri = $uri;
		return $this;
	}

	/**
	 * Sets a property for the stream.
	 * 
	 * @param string $key The key of the property to set. The key will be converted to uppercase.
	 * @param string $value The value of the property to set.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setProperty( string $key, string $value ): self
	{
		$key = strtoupper( $key );
		$this->properties[ $key ] = $value;

		if( $key === 'BANDWIDTH' )
		{
			$this->bandwidth = new Bandwidth( $value );
		}
		else if( $key === 'RESOLUTION' )
		{
			$this->resolution = new Resolution( $value );
		}
		else if( $key === 'CODECS' )
		{
			$this->codecs = new CodecList( $value );
		}

		return $this;
	}

	/**
	 * Checks if the given M3U8 stream syntax starts with the magic bytes '#EXT-X-STREAM-INF:'.
	 * 
	 * @param string $rawSyntax The M3U8 stream syntax to check.
	 * @return bool True if the syntax starts with the magic bytes, false otherwise.
	 */
	private function hasMagicBytes( string $rawSyntax ): bool
	{
		return strpos( $rawSyntax, '#EXT-X-STREAM-INF:' ) !== false;
	}

	/**
	 * Returns the data segment of the given M3U8 stream syntax.
	 * 
	 * @param string $rawSyntax The M3U8 stream syntax to get the data segment from.
	 * @return string The data segment of the given M3U8 stream syntax.
	 */
	private function getDataSegment( string $rawSyntax ): string
	{
		return explode( ':', $rawSyntax )[ 1 ];
	}

	/**
	 * Replace commas with pipe characters inside double quotes in the given data.
	 * This is necessary because some M3U8 files contain commas inside double quotes
	 * which makes the data impossible to explode.
	 * 
	 * @param string $data The data to normalize.
	 * @return string The normalized data.
	 */
	private function normalizeQuotes( string $data ): string
	{
		return preg_replace_callback(
			'/"(.*)"/',
			fn( $m ) => str_replace( ',', '|', $m[ 1 ]),
			$data
		);
	}

	/**
	 * Explodes the given data into an array of key-value pairs.
	 *
	 * @param string $data The data to explode.
	 * @return array The exploded array of key-value pairs.
	 */
	private function getPairs( string $data ): array
	{
		return explode( ',', $data );
	}

	/**
	 * Explodes the given key-value pair into an array of two elements.
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
}
