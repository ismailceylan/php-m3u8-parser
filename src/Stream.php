<?php

namespace Iceylan\M3U8;

use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represent a stream.
 */
class Stream implements M3U8Serializable
{
	/**
	 * The properties of the stream.
	 * 
	 * @var array
	 */
	public array $nonStandardProps = [];

	/**
	 * The URI of the stream.
	 * 
	 * @var string|null
	 */
	public ?string $uri = null;

	/**
	 * The bandwidth of the stream.
	 *
	 * @var Bandwidth|null
	 */
	public ?Bandwidth $bandwidth = null;

	/**
	 * The resolution of the stream.
	 *
	 * @var Resolution|null
	 */
	public ?Resolution $resolution = null;

	/**
	 * The codecs of the stream.
	 *
	 * @var CodecList|null
	 */
	public ?CodecList $codecs = null;

	/**
	 * The program ID of the stream.
	 *
	 * @var ProgramID|null
	 */
	public ?ProgramID $programID = null;
	
	/**
	 * The frame rate of the stream.
	 *
	 * @var FrameRate|null
	 */
	public ?FrameRate $frameRate = null;

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

	/**
	 * Parses the given M3U8 EXT-X-STREAM-INF syntax and
	 * sets the properties of the stream instance.
	 *
	 * @param string $rawM3U8StreamSyntax The raw M3U8 EXT-X-STREAM-INF syntax.
	 * @return self Returns the instance of the Stream class.
	 * @throws \Exception If the stream syntax does not start with magic bytes.
	 */
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
	 * Sets the resolution of the stream.
	 *
	 * @param string|int $width The width of the resolution.
	 * @param string|int $height The height of the resolution.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setResolution( string|int $width, string|int $height ): self
	{
		$this->resolution = new Resolution( "{$width}x{$height}" );
		return $this;
	}

	/**
	 * Sets the bandwidth of the stream.
	 *
	 * @param int $bandwidth The bandwidth value in bits per second.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setBandwidth( int $bandwidth ): self
	{
		$this->bandwidth = new Bandwidth( $bandwidth );
		return $this;
	}

	/**
	 * Sets the codecs of the stream.
	 *
	 * @param string ...$codecs The codecs to set. The codecs should be given as strings
	 * @return self Returns the instance of the Stream class.
	 */
	public function setCodecs( ...$codecs ): self
	{
		$this->codecs = new CodecList( implode( '|', $codecs ));
		return $this;
	}

	/**
	 * Sets the program ID of the stream.
	 *
	 * @param string $programID The program ID to set for the stream.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setProgramID( string $programID ): self
	{
		$this->programID = new ProgramID( $programID );
		return $this;
	}

	/**
	 * Sets the frame rate of the stream.
	 *
	 * @param int|float|string $frameRate The frame rate to set for the stream.
	 * @return self Returns the instance of the Stream class.
	 */
	public function setFrameRate( int|float|string $frameRate ): self
	{
		$this->frameRate = new FrameRate( $frameRate );
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
		else if( $key === 'PROGRAM-ID' )
		{
			$this->programID = new ProgramID( $value );
		}
		else if( $key === 'FRAME-RATE' )
		{
			$this->frameRate = new FrameRate( $value );
		}
		else
		{
			$this->nonStandardProps[ $key ] = $value;
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

	/**
	 * Converts the stream to a string in the M3U8 format.
	 *
	 * The M3U8 format is '#EXT-X-STREAM-INF:<program-id>,<resolution>,<bandwidth>,<codecs>'.
	 *
	 * @return string The stream in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		$data = [];

		if( $this->programID )
		{
			$data[] = $this->programID->toM3U8();
		}

		if( $this->bandwidth )
		{
			$data[] = $this->bandwidth->toM3U8();
		}

		if( $this->frameRate )
		{
			$data[] = $this->frameRate->toM3U8();
		}

		if( $this->resolution )
		{
			$data[] = $this->resolution->toM3U8();
		}

		if( $this->codecs )
		{
			$data[] = $this->codecs->toM3U8();
		}

		return '#EXT-X-STREAM-INF:' . implode( ',', $data );
	}
}
