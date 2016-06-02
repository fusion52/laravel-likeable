<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFollowerableTables extends Migration
{
	public function up()
	{
		Schema::create('followerable_followers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('followerable_id', 36);
			$table->string('followerable_type', 255);
			$table->string('user_id', 36)->index();
			$table->timestamps();
			$table->unique(['followerable_id', 'followerable_type', 'user_id'], 'followerable_followers_unique');
		});

		Schema::create('followerable_follower_counters', function(Blueprint $table) {
			$table->increments('id');
			$table->string('followerable_id', 36);
			$table->string('followerable_type', 255);
			$table->unsignedInteger('count')->default(0);
			$table->unique(['followerable_id', 'followerable_type'], 'followerable_counts');
		});

	}

	public function down()
	{
		Schema::drop('followerable_followers');
		Schema::drop('followerable_follower_counters');
	}
}
