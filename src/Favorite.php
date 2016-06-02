<?php

namespace Fusion52\Favoriteable;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Favorite extends Eloquent
{
	protected $table = 'favoriteable_favorites';
	public $timestamps = true;
	protected $fillable = ['favoriteable_id', 'favoriteable_type', 'user_id'];

	public function favoriteable()
	{
		return $this->morphTo();
	}
}
