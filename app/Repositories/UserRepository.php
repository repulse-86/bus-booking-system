<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * implementation
 *
 * @package default
 * @author 
 **/
class UserRepository implements UserRepositoryInterface
{
	private string $currentYear;

	public function __construct()
    {
        $this->currentYear = date('Y');
    }

	public function getCurrentWeekNewUserCount(): array
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

	public function getRegisteredUserCount(): int
	{
		return User::where('type', 'customer')->count();
	}

	public function getCumulativePerMonthUserCount(): Collection
	{
		$cumulativeUserCountPerMonth = DB::table('users')
		    ->select(
		        DB::raw('strftime("%m", created_at) as month_number'),
		        DB::raw('count(*) as total_users')
		    )
		    ->whereRaw('strftime("%Y", created_at) = ?', [$this->currentYear])
		    ->where('type', 'customer')
		    ->groupBy(DB::raw('strftime("%m", created_at)'))
		    ->orderBy(DB::raw('strftime("%m", created_at)'))
		    ->get()
		    ->map(function ($item) {
		        $item->month_name = Carbon::createFromFormat('m', $item->month_number)->format('F');
		        return $item;
		    });

		$cumulativeUsers = 0;
		$cumulativeUserCountPerMonth = $cumulativeUserCountPerMonth->map(function ($item) use (&$cumulativeUsers) {
		    $cumulativeUsers += $item->total_users;
		    $item->total_users = $cumulativeUsers;
		    return $item;
		});

		return $cumulativeUserCountPerMonth;
	}
}