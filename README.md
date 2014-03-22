Laravel Addresses Plugin
============

Link a list of addresses to a user. Allows to flag address as primary, billing, or shipping. Users have many addresses, but only one primary or shipping or billing.

Sample views and controller are included. I don't recoment you use them directly, butjust use them as example code to copy and paste.


#### Composer Install

    "require": {
        "rtconner/laravel-addresses": "dev-master"
    }

#### Run the migrations

	php artisan migrate --package=rtconner/laravel-addresses
	
#### Install the Service Provider 

	'providers' => array(
		'Conner\Addresses\AddressesServiceProvider',
	),
	
#### Sample Usage

    \Addresses::getAll($user->id); // get all users
    
    if(Addresses::getValidator()->fails()) { } // validate before saving
    
    $address = Addresses::createAddress(\Input::all()); // this does not auto-validate
    
    $address = Addresses::updateAddress(\Input::all()); // this does not auto-validate
    
    Addresses::deleteAddress($address);
    
    Addresses::setPrimary($address); // set address as primary (and unset the others)
    
	Addresses::getPrimary();    
    
	Addresses::getShipping($userId);    

	Addresses::getBilling();    

#### View Templates

	@include('addresses::fields') <!-- bootstrap fields with no form tags -->
	
	@foreach($addresses as $address)
		@include('addresses::view', compact('separator'=>'<br>')) <!-- read-only html of address -->
	@endforeach 
	
You can make call direcly on the Conner\Addresses\Address model if you want. But you just have to be careful when reading/writing. The methods provided on \Addresses handle checks against the currently logged in user and making sure there are no duplicate primary addresses.
