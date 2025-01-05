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

	public function getCurrentWeekNewUserCount(): array
	{
		return $this->userRepository->getCurrentWeekNewUserCount();
	}

	public function getRegisteredUserCount(): int
	{
		return $this->userRepository->getRegisteredUserCount();
	}

	public function getCumulativePerMonthUserCount(): Collection
	{
		return $this->userRepository->getCumulativePerMonthUserCount();
	}
}