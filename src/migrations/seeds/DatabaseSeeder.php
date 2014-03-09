<?php namespace Conner\Addresses;

class DatabaseSeeder extends \Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$this->call('CountryTableSeeder');
		$this->call('StateTableSeeder');
	}

}