<?php

namespace Iceylan\M3U8;

use JsonSerializable;
use Iceylan\M3U8\Contracts\M3U8Serializable;

/**
 * Represents a bandwidth value.
 */
class Bandwidth implements JsonSerializable, M3U8Serializable
{
	/**
	 * The bandwidth value in bits per second.
	 * 
	 * @var int
	 */
	public int $bps = 0;

	/**
	 * The name of the bandwidth value.
	 *
	 * @var string|null
	 */
	public ?string $key = null;

	/**
	 * Constructor.
	 *
	 * @param int|string $bitsPerSecond The bandwidth value in bits per second.
	 * @param ?string $key The name of the bandwidth value.
	 */
	public function __construct( int|string $bitsPerSecond, ?string $key )
	{
		$this->bps = (int) $bitsPerSecond;
		$this->key = $key;
	}

	/**
	 * Converts the bandwidth value to bytes per second and returns it as a string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		[ $size, $unit ] = $this->toBytes();

		return round( $size, 2 ) . ' ' . $unit . "ps";
	}

	/**
	 * @inheritDoc
	 * 
	 * Serializes the bandwidth value as an integer.
	 */
	public function jsonSerialize(): int
	{
		return $this->bps;
	}

	/**
	 * Converts the bandwidth value in bits per second to the given unit.
	 *
	 * @param array $units An array of unit names.
	 * @param int $base The base value for the unit. 1000 for decimal system, 1024 for binary system.
	 * @return array [value, unit]
	 */
	public function convert( array $units, int $base ): array
	{
		$current = $this->bps;

		if( $base === 1024 )
		{
			$current /= 8;
		}

		foreach( $units AS $unit )
		{
			if( $current > $base )
			{
				$current /= $base;
			}
			else
			{
				break;
			}
		}

		return [ $current, $unit ];
	}

	/**
	 * Converts the bandwidth value to bits per second and returns it in the given unit.
	 *
	 * @param bool $longUnitNames Whether to use long unit names (default: false).
	 * @return array [value, unit]
	 */
	public function toBits( bool $longUnitNames = false ): array
	{
		$units = $longUnitNames
			? [ 'bits', 'Kilobits', 'Megabits', 'Gigabits', 'Terabits' ]
			: [ 'b', 'Kb', 'Mb', 'Gb', 'Tb' ];

		return $this->convert( $units, 1000 );
	}

	/**
	 * Converts the bandwidth value to bytes per second and returns it in the given unit.
	 *
	 * @param bool $longUnitNames Whether to use long unit names (default: false).
	 * @return array [value, unit]
	 */
	public function toBytes( bool $longUnitNames = false ): array
	{
		$units = $longUnitNames
			? [ 'bytes', 'Kilobytes', 'Megabytes', 'Gigabytes', 'Terabytes' ]
			: [ 'B', 'KB', 'MB', 'GB', 'TB' ];

		return $this->convert( $units, 1024 );
	}

	/**
	 * Converts the bandwidth value to a string in the M3U8 format.
	 *
	 * The M3U8 format is '<key>=<value>'.
	 *
	 * @return string The bandwidth value in the M3U8 format.
	 */
	public function toM3U8(): string
	{
		return "$this->key=" . $this->bps;
	}
}
