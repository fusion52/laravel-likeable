<?php

namespace Fusion52\Followerable;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Follower extends Eloquent
{
	protected $table = 'followerable_followers';
	public $timestamps = true;
	protected $fillable = ['followerable_id', 'followerable_type', 'user_id'];

	public function followerable()
	{
		return $this->morphTo();
	}
}
