<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;

/**
 * implementation
 *
 * @package default
 * @author 
 **/
class UserRepository implements UserRepositoryInterface
{
	public function getCurrentWeekNewUsers(): array
	{
		$startOfWeek = Carbon::now()->startOfWeek();
		$endOfWeek = Carbon::now()->endOfWeek();

		$userStats = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
		    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
		    ->groupBy('date')
		    ->orderBy('date')
		    ->get();

		$categories = $userStats->pluck('date')->map(function ($date) {
		    return Carbon::parse($date)->format('d F');
		});

		$data = $userStats->pluck('count');

		return compact('userStats', 'data');
	}

	public function getRegisteredUsersCount(): int
	{
		return User::where('type', 'customer')->count();
	}
}