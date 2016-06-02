<?php

namespace Fusion52\Followerable;

use Illuminate\Support\ServiceProvider;

/**
 * Copyright (C) 2015 Fusion52 Team
 */
class FollowerableServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			realpath(__DIR__.'/../migrations/2016_02_07_000000_create_followable_tables.php') => database_path('migrations')
		], 'migrations');
	}

	public function register() {}
}
