<?php

namespace Iceylan\M3U8;

use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represent a resolution.
 */
class Resolution implements M3U8Serializable
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

	/**
	 * Convert the resolution to the M3U8 format.
	 * The M3U8 format is 'RESOLUTION=<width>x<height>'.
	 *
	 * @return string The resolution in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'RESOLUTION=' . $this->__toString();
	}
}
