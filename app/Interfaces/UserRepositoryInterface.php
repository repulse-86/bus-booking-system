<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

/**
 * interface for retrieving user data
 *
 * @package default
 * @author 
 **/
interface UserRepositoryInterface
{
	public function getCurrentWeekNewUsers(): array;
	public function getRegisteredUsersCount(): int;
	public function getCumulativeUsersCountPerMonth(): Collection;
} // END interface UserRepositoryInterface