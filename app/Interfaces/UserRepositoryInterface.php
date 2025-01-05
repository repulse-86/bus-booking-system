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
	public function getCurrentWeekNewUserCount(): array;
	public function getRegisteredUserCount(): int;
	public function getCumulativePerMonthUserCount(): Collection;
} // END interface UserRepositoryInterface