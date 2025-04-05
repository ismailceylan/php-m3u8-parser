<?php

namespace Iceylan\M3U8;

use Iceylan\M3U8\Contracts\M3U8Serializable;
use JsonSerializable;

/**
 * Represent a frame rate.
 */
class FrameRate implements M3U8Serializable, JsonSerializable
{
	/**
	 * The frame rate.
	 *
	 * @var float
	 */
	public float $fps;

	/**
	 * Constructor for the FrameRate class.
	 *
	 * @param string|float|int $fps The frame rate value, which can be
	 * provided as a string or float or integer.
	 */
	public function __construct( string|float|int $fps = 0.0 )
	{
		$this->fps = (float) $fps;
	}

	/**
	 * Converts the frame rate to a string in the M3U8 format.
	 * The M3U8 format for the frame rate is 'FRAME-RATE=<fps>'.
	 *
	 * @return string The frame rate in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return 'FRAME-RATE=' . $this->fps;
	}

	/**
	 * @inheritDoc
	 * 
	 * Serializes the frame rate to a value that can be serialized natively by json_encode().
	 * 
	 * @return float The frame rate.
	 */
	public function jsonSerialize(): float
	{
		return $this->fps;
	}
}
