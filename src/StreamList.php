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
	 * @return array The list of streams.
	 */
	public function getStreams(): array
	{
		return $this->streams;
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
                fn( $stream ) => $stream->toM3U8() . "\n" . $stream->uri,
                $this->getStreams()
            )
		);
	}
}
