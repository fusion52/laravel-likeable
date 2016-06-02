<?php

namespace Fusion52\Followerable;

/**
 * Copyright (C) 2016 Fusion52 Team
 */
trait Followerable
{
	/**
	 * Boot the soft taggable trait for a model.
	 *
	 * @return void
	 */
	public static function bootFollowerable()
	{
		if(static::removeFollowersOnDelete()) {
			static::deleting(function($model) {
				$model->removeFollowers();
			});
		}
	}

	/**
	 * Fetch records that are followed by a given user.
	 * Ex: Book::whereFollowedBy(123)->get();
	 */
	public function scopeWhereFollowedBy($query, $userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}

		return $query->whereHas('followers', function($q) use($userId) {
			$q->where('user_id', '=', $userId);
		});
	}


	/**
	 * Populate the $model->followers attribute
	 */
	public function getFollowerCountAttribute()
	{
		return $this->followerCounter ? $this->followerCounter->count : 0;
	}

	/**
	 * Collection of the followers on this record
	 */
	public function followers()
	{
		return $this->morphMany(Follower::class, 'followerable');
	}

	/**
	 * Counter is a record that stores the total followers for the
	 * morphed record
	 */
	public function followerCounter()
	{
		return $this->morphOne(FollowerCounter::class, 'followerable');
	}

	/**
	 * Add a follower for this record by the given user.
	 * @param $userId mixed - If null will use currently logged in user.
	 */
	public function follower($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}

		if($userId) {
			$follower = $this->followers()
				->where('user_id', '=', $userId)
				->first();

			if($follower) return;

			$follower = new Follower();
			$follower->user_id = $userId;
			$this->followers()->save($follower);
		}

		$this->incrementFollowerCount();
	}

	/**
	 * Remove a follower from this record for the given user.
	 * @param $userId mixed - If null will use currently logged in user.
	 */
	public function unfollower($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}

		if($userId) {
			$follower = $this->followers()
				->where('user_id', '=', $userId)
				->first();

			if(!$follower) { return; }

			$follower->delete();
		}

		$this->decrementFollowerCount();
	}

	/**
	 * Has the currently logged in user already "followed" the current object
	 *
	 * @param string $userId
	 * @return boolean
	 */
	public function followed($userId=null)
	{
		if(is_null($userId)) {
			$userId = $this->loggedInUserId();
		}

		return (bool) $this->followers()
			->where('user_id', '=', $userId)
			->count();
	}

	/**
	 * Private. Increment the total follower count stored in the counter
	 */
	private function incrementFollowerCount()
	{
		$counter = $this->followerCounter()->first();

		if($counter) {
			$counter->count++;
			$counter->save();
		} else {
			$counter = new FollowerCounter;
			$counter->count = 1;
			$this->followerCounter()->save($counter);
		}
	}

	/**
	 * Private. Decrement the total follower count stored in the counter
	 */
	private function decrementFollowerCount()
	{
		$counter = $this->followerCounter()->first();

		if($counter) {
			$counter->count--;
			if($counter->count) {
				$counter->save();
			} else {
				$counter->delete();
			}
		}
	}

	/**
	 * Fetch the primary ID of the currently logged in user
	 * @return number
	 */
	public function loggedInUserId()
	{
		return auth()->id();
	}

	/**
	 * Did the currently logged in user follower this model
	 * Example : if($book->followed) { }
	 * @return boolean
	 */
	public function getFollowerdAttribute()
	{
		return $this->followed();
	}

	/**
	 * Should remove followers on model row delete (defaults to true)
	 * public static removeFollowersOnDelete = false;
	 */
	public static function removeFollowersOnDelete()
	{
		return isset(static::$removeFollowersOnDelete)
			? static::$removeFollowersOnDelete
			: true;
	}

	/**
	 * Delete followers related to the current record
	 */
	public function removeFollowers()
	{
		Follower::where('followerable_type', $this->morphClass)->where('followerable_id', $this->id)->delete();

		FollowerCounter::where('followerable_type', $this->morphClass)->where('followerable_id', $this->id)->delete();
	}
}
