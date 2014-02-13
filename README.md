Laravel Addresses Plugin
============

Billing / Shipping address storage. Links addresses to a given user_id. 

Currently this thing is linked closely with Sentry. I'm trying to remove that dependancy (but not sure how yet)

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
    
## View Templates

	@include('addresses::fields') <!-- bootstrap fields with no form tags -->
	
	{{ $address->toHtml() }} <!-- organized html of address --> 
	
You can make call direcly on the Conners\Addresses\Address model if you want. But you just have to be careful when reading/writing. The methods provided on \Addresses handle checks against the currently logged in user and making sure there are no duplicate primary addresses.