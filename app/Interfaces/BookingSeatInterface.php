<?php

namespace App\Interfaces;

use App\Models\BookingSeat;

/**
 * interface for bookingseat repository
 *
 * @package default
 * @author
 **/
interface BookingSeatInterface
{
	public function store(array $data): BookingSeat;
} // END interface BookingSeatInterface