<?php

namespace Fusion52\Likeable;

use Illuminate\Support\ServiceProvider;

/**
 * Copyright (C) 2015 Fusion52 Team
 */
class LikeableServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			realpath(__DIR__.'/../migrations/2016_02_07_000000_create_likeable_tables.php') => database_path('migrations')
		], 'migrations');
	}

	public function register() {}
}
