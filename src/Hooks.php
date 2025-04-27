<?php

namespace Iceylan\M3U8;

use Iceylan\Eventor\HasEvents;
use Iceylan\Eventor\ShouldDispatchEvents;

/**
 * The hooks class.
 */
class Hooks implements ShouldDispatchEvents
{
	use HasEvents
	{
		on as public add;
		off as public remove;
	}
}
