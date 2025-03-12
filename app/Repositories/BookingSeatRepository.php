<?php

namespace App\Repositories;

use App\Interfaces\BookingSeatInterface;
use App\Models\BookingSeat;

/**
 * repository layer for booking seat
 *
 * @package default
 * @author
 **/
class BookingSeatRepository implements BookingSeatInterface
{
	public function store(array $data): BookingSeat
	{
		return BookingSeat::create($data);
	}
} // END class BookingSeatRepository