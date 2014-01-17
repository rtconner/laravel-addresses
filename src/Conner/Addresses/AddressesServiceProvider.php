<?php namespace Conner\Addresses;

use Illuminate\Support\ServiceProvider;

class AddressesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 */
// 	protected $defer = false;
	
	/**
	 * Bootstrap the application events.
	 */
	public function boot() {
		$this->package('conner/addresses');
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		
		$this->app['addresses'] = $this->app->share(function($app) {
			return new Addresses();
		});
		
		$this->app->booting(function() {

			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Addresses', 'Conner\Addresses\AddressesFacade');
		
		});

	}
	
}