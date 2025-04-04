<?php

namespace Iceylan\M3U8\Contracts;

interface M3U8Serializable
{
	public function toM3U8(): string;
}
