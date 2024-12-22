<?php

namespace App\Interfaces;

use Carbon\Carbon;

/**
 * 
 */
interface BusRepositoryInterface
{
	public function getBuses(Carbon $filterTravelDate, string $filterDestinationLocation, string $filterBus, array $busesToRetrieve);
	/*public function getBusById(Bus $bus);
	public function deleteBus(Bus $bus);
	public function createBus(array $busDetails);
	public function updateBus(Bus $bus, $newDetails);*/
}