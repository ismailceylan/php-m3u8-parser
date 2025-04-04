<?php

namespace Iceylan\M3U8;

/**
 * Represents a bandwidth value.
 */
class Bandwidth
{
	/**
	 * The bandwidth value in bits per second.
	 * 
	 * @var int
	 */
	public int $bps = 0;

	/**
	 * Constructor.
	 *
	 * @param int|string $bitsPerSecond The bandwidth value in bits per second.
	 */
	public function __construct( int|string $bitsPerSecond )
	{
		$this->bps = (int) $bitsPerSecond;
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
}
