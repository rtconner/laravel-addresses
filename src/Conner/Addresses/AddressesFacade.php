<?php namespace Conner\Addresses;
 
use Illuminate\Support\Facades\Facade;
 
class AddressesFacade extends Facade {

	protected static function getFacadeAccessor() { return 'addresses'; }

}