<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a list of medias.
 */
class MediaList implements M3U8Serializable, JsonSerializable
{
	/**
	 * List of medias.
	 *
	 * @var array
	 */
	private array $medias = [];

	/**
	 * Adds a media to the list of medias.
	 *
	 * @param Media $media The media to be added.
	 * @return self Returns the instance of the MediaList class.
	 */
	public function push( Media $media ): self
	{
		$this->medias[] = $media;
		return $this;
	}

	/**
	 * Gets the list of medias.
	 *
	 * @return Media[] The list of medias.
	 */
	public function getMedias(): array
	{
		return $this->medias;
	}

	/**
	 * Attaches each medias to given stream in the list of medias.
	 *
	 * @param Stream $strema The stream to medias attached.
	 * @return self Returns the instance of the MediaList class.
	 */
	public function attach( Stream $stream ): self
	{
		foreach( $this->medias as $media )
		{
			$stream->push( $media );
		}

		return $this;
	}

	/**
	 * Checks if the list of medias is empty.
	 *
	 * @return bool True if the list of medias is empty, false otherwise.
	 */
	public function isEmpty(): bool
	{
		return empty( $this->medias );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function jsonSerialize(): array
	{
		return $this->medias;
	}

	/**
	 * Converts the list of medias to a string in the M3U8 format.
	 * The M3U8 format is '#EXT-X-MEDIA:<media-id>,<type>,<group-id>,<language>,<assoc-language>,<name>,<default>,<auto-select>,<forced>'.
	 *
	 * @return string The list of medias in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return implode(
			"\n",
			array_map( fn( $media ) => $media->toM3U8(), $this->medias )
		);
	}
}
