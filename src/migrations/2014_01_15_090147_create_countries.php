<?php

use Illuminate\Database\Migrations\Migration;

class CreateCountries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('countries', function($table) {
            $table->increments('id');
            $table->string('name', 60);
			$table->string('a2', 2);
            $table->string('a3', 3);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('countries');
	}

}