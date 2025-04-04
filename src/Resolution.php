<?php

namespace Iceylan\M3U8;

/**
 * Represent a resolution.
 */
class Resolution
{
	/**
	 * The width.
	 *
	 * @var integer
	 */
	public int $width = 0;

	/**
	 * The height.
	 *
	 * @var integer
	 */
	public int $height = 0;

	/**
	 * Class constructor to initialize the resolution.
	 *
	 * @param string $resolution The resolution in the format widthxheight.
	 */

	public function __construct( string $resolution )
	{
		[ $this->width, $this->height ] = explode( 'x', $resolution );
	}

	/**
	 * Get the resolution as a string.
	 * The resolution is returned in the format widthxheight.
	 *
	 * @return string The resolution as a string.
	 */
	public function __toString(): string
	{
		return $this->width . 'x' . $this->height;
	}

	/**
	 * Get the progressive name for the resolution.
	 * The progressive name is the height with a 'P' suffix.
	 *
	 * @return string The progressive name.
	 */
	public function getPName(): string
	{
		return $this->height . 'P';
	}

	/**
	 * Get the total number of pixels.
	 *
	 * @return int The number of pixels.
	 */
	public function getPixels(): int
	{
		return $this->width * $this->height;
	}
}
