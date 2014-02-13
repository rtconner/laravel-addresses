<?php namespace Conner\Addresses;

use Conner\Addresses\Address;
use Addresses;

/**
 * This is mostly just meant as sample code for you to copy/paste and modify to your spec.
 * I'm not sure that I would recomend using this controller directly
 * 
Route::group(array('before' => 'auth'), function() {

	Route::group(array('prefix' => 'account'), function() {
		Route::bind('address', function($value, $route) {
			return Conner\Addresses\Address::where('user_id', \Sentry::getUser()->id)->where('id', $value)->first();
		});
		Route::any('addresses', 'Conner\Addresses\Controller@getIndex');
		Route::get('address/create', 'Conner\Addresses\Controller@getCreate');
		Route::post('address/create', array('before' => 'csrf', 'uses'=>'Conner\Addresses\Controller@postCreate'));
		Route::get('address/edit/{address}', 'Conner\Addresses\Controller@getEdit');
		Route::post('address/edit/{address}', array('before' => 'csrf', 'uses'=>'Conner\Addresses\Controller@postEdit'));
		Route::any('address/delete/{address}', 'Conner\Addresses\Controller@doDelete');
		Route::any('address/primary/{address}', 'Conner\Addresses\Controller@setPrimary');
		Route::any('address/shipping/{address}', 'Conner\Addresses\Controller@setShipping');
		Route::any('address/billing/{address}', 'Conner\Addresses\Controller@setBilling');
	});

});
 * 
 * 
 * @author rtconner
 */
class Controller extends \Illuminate\Routing\Controller {

	/**
	 * Show all addresses for the current user
	 */
	public function getIndex() {
		echo \View::make('addresses::index', array(
			'addresses'=>\Addresses::getAll()
		));
	}
	
	public function getCreate() {
		echo \View::make('addresses::create', array(
			'address'=>new Address()
		));
	}

	public function postCreate() {
		$input = \Input::all();
		
		$validator = Addresses::getValidator($input);
		
		if ($validator->fails()) {
			return \Redirect::to('account/address/create')->withInput()->withErrors($validator);
		}
		
		if($address = Addresses::createAddress($input)) {
			return \Redirect::to('account/addresses');
		} else {
			return \Redirect::to('account/address/create')->withInput()->withErrors('Unknown errors when saving');
		}
	}
	
	public function getEdit(Address $address) {
		echo \View::make('addresses::edit', array(
			'address'=>$address,
		));
	}
	
	public function postEdit(Address $address) {
		$input = \Input::all();
	
		$validator = Addresses::getValidator($input);
	
		if ($validator->fails()) {
			return \Redirect::to('account/address/edit')->withInput()->withErrors($validator);
		}
	
		if($address = Addresses::updateAddress($address, $input)) {
			return \Redirect::to('account/addresses');
		} else {
			return \Redirect::to('account/address/edit')->withInput()->withErrors('Unknown errors when saving');
		}
	}
	
	public function doDelete(Address $address) {
		Addresses::deleteAddress($address);
		
		return \Redirect::to('account/addresses')->withErrors('Address has been removed');
	}

	public function setPrimary(Address $address) {
		Addresses::setPrimary($address);
	
		return \Redirect::to('account/addresses');
	}

	public function setBilling(Address $address) {
		Addresses::setBilling($address);
	
		return \Redirect::to('account/addresses');
	}

	public function setShipping(Address $address) {
		Addresses::setShipping($address);
	
		return \Redirect::to('account/addresses');
	}
	
}