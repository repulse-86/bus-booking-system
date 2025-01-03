<?php

namespace App\Interfaces;
/**
 * interface for retrieving user data
 *
 * @package default
 * @author 
 **/
interface UserRepositoryInterface
{
	public function getCurrentWeekNewUsers(): array;
} // END interface UserRepositoryInterface