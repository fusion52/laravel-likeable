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
			realpath(__DIR__.'/../migrations') => database_path('migrations')
		], 'migrations');
	}

	public function register() {}
}
