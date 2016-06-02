<?php

namespace Fusion52\Followerable;

use Illuminate\Database\Eloquent\Model as Eloquent;

class FollowerCounter extends Eloquent
{
	protected $table = 'followerable_follower_counters';
	public $timestamps = false;
	protected $fillable = ['followerable_id', 'followerable_type', 'count'];

	public function followerable()
	{
		return $this->morphTo();
	}

	/**
	 * Delete all counts of the given model, and recount them and insert new counts
	 *
	 * @param string $model (should match Model::$morphClass)
	 */
	public static function rebuild($modelClass)
	{
		if(empty($modelClass)) {
			throw new \Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
		}
		
		$builder = Follower::query()
			->select(\DB::raw('count(*) as count, followerable_type, followerable_id'))
			->where('followerable_type', $modelClass)
			->groupBy('followerable_id');

		$results = $builder->get();

		$inserts = $results->toArray();

		\DB::table((new static)->table)->insert($inserts);
	}

}
