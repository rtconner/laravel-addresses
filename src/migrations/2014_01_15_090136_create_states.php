<?php

use Illuminate\Database\Migrations\Migration;

class CreateStates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('states', function($table) {
            $table->increments('id');
            $table->string('country_a2');  // country alpha-2
            $table->string('name', 60);
            $table->string('a2', 2); // alpha-2
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('states');
	}

}