<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class UserService
{
	public function __construct(protected UserRepository $userRepository)
	{
		
	}

	public function getCurrentWeekUsers(): array
	{
		return $this->userRepository->getCurrentWeekNewUsers();
	}

	public function getRegisteredUserCount(): int
	{
		return $this->userRepository->getRegisteredUsersCount();
	}

	public function getCumulativeUsersCountPerMonth(): Collection
	{
		return $this->userRepository->getCumulativeUsersCountPerMonth();
	}
}