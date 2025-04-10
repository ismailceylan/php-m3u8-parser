<?php

namespace Iceylan\M3U8\Contracts;

/**
 * Interface for objects that can be serialized in the M3U8 format.
 *
 * Implementing classes must provide a toM3U8 method that returns a string
 * representation of the object in the M3U8 format.
 */
interface M3U8Serializable
{
	/**
	 * Returns a string representation of the object in the M3U8 format.
	 *
	 * @return string
	 */
	public function toM3U8(): string;
}
