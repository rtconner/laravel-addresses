Laravel Addresses Plugin
============

Link a list of addresses to a user. Allows to flag address as primary, billing, or shipping. Users have many addresses, but only one primary or shipping or billing.

Currently this thing is linked to Sentry. I'm trying to remove that dependancy (but not sure how yet)

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
	
#### Setup your User (this is not required, just a perk)

    class User extends \Eloquent {
		use \Conner\Addresses\Addressable;
    }

#### Sample Usage

    \Addresses::getAll($user->id); // get all users
    
    if(Addresses::getValidator()->fails()) { } // validate before saving
    
    $address = Addresses::createAddress(\Input::all()); // this does not auto-validate
    
    $address = Addresses::updateAddress(\Input::all()); // this does not auto-validate
    
    Addresses::deleteAddress($address);
    
    Addresses::setPrimary($address); // set address as primary (and unset the others)
    
	Addresses::getPrimary($user);    
    
	Addresses::getShipping($user);    

	Addresses::getBilling($user);    

#### View Templates

	@include('addresses::fields') <!-- bootstrap fields with no form tags -->
	
	@foreach($addresses as $address)
		@include('addresses::view', compact('separator'=>'<br>')) <!-- read-only html of address -->
	@endforeach 
	
You can make call direcly on the Conners\Addresses\Address model if you want. But you just have to be careful when reading/writing. The methods provided on \Addresses handle checks against the currently logged in user and making sure there are no duplicate primary addresses.