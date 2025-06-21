<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use SplObjectStorage;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a set of objects.
 */
class ObjectSet implements JsonSerializable, M3U8Serializable
{
	/**
	 * The list of audio streams.
	 *
	 * @var SplObjectStorage
	 */
	private SplObjectStorage $items;

	/**
	 * Constructs an ObjectSet instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->items = new SplObjectStorage;
	}

	/**
	 * Sets an audio to the list of audio streams.
	 *
	 * @param Media $audio The audio to be attached.
	 * @return self Returns the instance of the AudioList class.
	 */
	public function set( Media $audio ): self
	{
		$this->items->attach( $audio );
		return $this;
	}

	/**
	 * Removes an audio from the list of audio streams.
	 *
	 * @param Media $audio The audio to be detached.
	 * @return self Returns the instance of the AudioList class.
	 */
	public function delete( Media $audio ): self
	{
		$this->items->detach( $audio );
		return $this;
	}

	/**
	 * Checks if the given audio is in the list of audio streams.
	 *
	 * @param Media $audio The audio to be checked.
	 * @return bool True if the audio is in the list of audio streams, false otherwise.
	 */
	public function has( Media $audio ): bool
	{
		return $this->items->contains( $audio );
	}

	/**
	 * Gets the number of objects in the set.
	 *
	 * @return int The number of objects in the set.
	 * @todo rename method name to count
	 */
	public function length(): int
	{
		return $this->items->count();
	}

	/**
	 * Serializes the list of audio streams to a value that can
	 * be serialized natively by json_encode().
	 *
	 * @return array The list of audio streams.
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}

	/**
	 * Returns the list of audio streams as an array.
	 *
	 * @return array The list of audio streams.
	 */
	public function toArray(): array
	{
		return [ ...$this->items ];
	}

	/**
	 * Converts the list of objects to a string in the M3U8 format.
	 *
	 * The M3U8 format is a string of newline-separated lines, where each line
	 * is the M3U8 representation of an object in the list.
	 *
	 * @return string The list of objects in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return implode(
			separator: "\n",
			array: array_map(
				callback: fn( $media ) => $media->toM3U8(),
				array: $this->toArray()
			)
		);
	}

	/**
	 * Converts the list of objects to a string in the M3U8 format.
	 *
	 * This method returns the result of calling the toM3U8 method.
	 *
	 * @return string The list of objects in the M3U8 format.
	 */
	public function __toString(): string
	{
		return $this->toM3U8();
	}
}
