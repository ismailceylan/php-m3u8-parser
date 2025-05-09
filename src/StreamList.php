<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a list of streams.
 */
class StreamList implements M3U8Serializable, JsonSerializable
{
	/**
	 * List of streams.
	 *
	 * @var array
	 */
	private array $streams = [];

	/**
	 * Adds a stream to the list of streams.
	 *
	 * @param Stream $stream The stream to be added.
	 * @return self Returns the instance of the StreamList class.
	 */
	public function push( Stream $stream ): self
	{
		$this->streams[] = $stream;
		return $this;
	}

	/**
	 * Gets the list of streams.
	 *
	 * @return Stream[] The list of streams.
	 */
	public function all(): array
	{
		return $this->streams;
	}

	/**
	 * Retrieves a stream from the list of streams by its index.
	 *
	 * @param int $index The index of the stream to retrieve.
	 * @return Stream The stream at the specified index.
	 * @throws OutOfBoundsException If the index is out of range.
	 */
	public function get( int $index ): Stream
	{
		return $this->streams[ $index ];
	}

	/**
	 * Attaches a media to each stream in the list of streams.
	 *
	 * @param Media $media The media to be attached.
	 * @return self Returns the instance of the StreamList class.
	 */
	public function attach( Media $media ): self
	{
		foreach( $this->streams as $stream )
		{
			$stream->push( $media );
		}

		return $this;
	}

	/**
	 * Checks if the list of streams is empty.
	 *
	 * @return bool True if the list of streams is empty, false otherwise.
	 */
	public function isEmpty(): bool
	{
		return empty( $this->streams );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function jsonSerialize(): array
	{
		return $this->streams;
	}

	/**
	 * Converts the list of streams to a string in the M3U8 format.
	 * The M3U8 format is '#EXT-X-STREAM-INF:<program-id>,<resolution>,<bandwidth>,<codecs>'.
	 *
	 * @return string The list of streams in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return implode(
            "\n",
            array_map(
                fn( $stream ) => $stream->toM3U8(),
                $this->all()
            )
		);
	}
}
