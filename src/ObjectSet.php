<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use SplObjectStorage;

/**
 * Represents a set of objects.
 */
class ObjectSet implements JsonSerializable
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
	 * Serializes the list of audio streams to a value that can
	 * be serialized natively by json_encode().
	 *
	 * @return array The list of audio streams.
	 */
	public function jsonSerialize(): array
	{
		return [ ...$this->items ];
	}
}
