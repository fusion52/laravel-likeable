<?php

namespace Fusion52\Favoriteable;

use Illuminate\Support\ServiceProvider;

/**
 * Copyright (C) 2015 Fusion52 Team
 */
class FavoriteableServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
			realpath(__DIR__.'/../migrations') => database_path('migrations')
		], 'migrations');
	}

	public function register() {}
}
