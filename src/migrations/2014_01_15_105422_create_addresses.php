<?php

use Illuminate\Database\Migrations\Migration;

class CreateAddresses extends Migration {

	public function up() {
		
		Schema::create('addresses', function($table) {
			$table->increments('id');

			$userModel = \Config::get('addresses::user.model');
			$userIdType = DB::connection()->getDoctrineColumn(with(new $userModel)->getTable(), 'id')->getType();
			$userIdType->getBindingType() == 1 // test if integer
				? $table->integer('user_id')->unsigned()->index()
				: $table->string('user_id', 36)->index();
			
			$table->string('addressee', 50)->nullable();
			$table->string('organization', 50)->nullable();
			$table->string('street', 50);
			$table->string('street_extra', 50)->nullable();
			$table->string('city', 50);
			$table->string('state_a2', 2);
			$table->string('state_name', 60);
			$table->string('zip', 11);
			$table->string('country_a2', 2)->default(\Config::get('addresses::default_country'));
			$table->string('country_name', 60)->default(\Config::get('addresses::default_country_name'));
			$table->string('phone', 20)->nullable();
			
			foreach(\Config::get('addresses::flags') as $flag) {
				$table->boolean('is_'.$flag)->default(false)->index();
			}
			
			$table->float('latitude')->nullable();
			$table->float('longitude')->nullable();
		});
		
	}

	public function down() {
		Schema::drop('addresses');
	}

}