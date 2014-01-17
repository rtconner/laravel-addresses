<?php namespace Conner\Addresses;

use Illuminate\Database\Eloquent\Collection;
use Cache;

/**
 * Primary handler for managing addresses
 */
class Addresses {

	/**
	 * Return Collection of Addresses owned by the given userID.
	 * 
	 * @param Collection
	 */
	public function getAll($userId) {
		return Address::where('user_id', $userId)
			->orderBy('is_primary', 'ASC')
			->orderBy('is_shipping', 'ASC')
			->orderBy('is_billing', 'ASC')
			->get();
	}

	/**
	 * Return Collection of Addresses owned by the given userID
	 *
	 * @param Collection
	 */
	public function getPrimary($userId) {
		return Address::where('user_id', $userId)
		->where('is_primary', true)
		->first();
	}
	
	/**
	 * Return Collection of Addresses owned by the given userID
	 *
	 * @param Collection
	 */
	public function getBilling($userId) {
		return Address::where('user_id', $userId)
			->where('is_billing', true)
			->first();
	}
	
	/**
	 * Return Collection of Addresses owned by the given userID
	 *
	 * @param Collection
	 */
	public function getShipping($userId) {
		return Address::where('user_id', $userId)
			->where('is_shipping', true)
			->first();
	}
	
	/**
	 * Set primary address for the given user. Unsets all other addresses for that
	 * user as non-primary
	 *
	 * @param mixed $objectOrId primary address id or object instance
	 */
	public function setPrimary($objectOrId) {
		$address = is_numeric($objectOrId)
			? Address::find($objectOrId)
			: $objectOrId;
		
		if($userId = $address->user_id) {
			Address::where('user_id', '=', $userId)->update(array('is_primary'=>false));
			$address->is_primary = true;
			$address->save();
		}
	}

	/**
	 * Set billing address for the given user. Unsets all other addresses for that
	 * user as non-billing
	 *
	 * @param mixed $objectOrId primary address id or object instance
	 */
	public function setBilling($objectOrId) {
		$address = is_numeric($objectOrId)
			? Address::find($objectOrId)
			: $objectOrId;
	
		if($userId = $address->user_id) {
			Address::where('user_id', $userId)->update(array('is_billing'=>false));
			$address->is_billing = true;
			$address->save();
		}
	}

	/**
	 * Set shipping address for the given user. Unsets all other addresses for that
	 * user as non-shipping
	 *
	 * @param mixed $objectOrId primary address id or object instance
	 */
	public function setShipping($objectOrId) {
		$address = is_numeric($objectOrId)
			? Address::find($objectOrId)
			: $objectOrId;
	
		if($userId = $address->user_id) {
			Address::where('user_id', $userId)->update(array('is_shipping'=>false));
			$address->is_shipping = true;
			$address->save();
		} else {
			throw new UserNotFoundException;
		}
	}
	
	/**
	 * Return collection of all countries
	 * 
	 * @return Collection
	 */
	public static function getCountries() {
		return Cache::rememberForever('addresses.countries', function() {
			return Country::orderBy('name', 'ASC')->get();
		});
	}

	/**
	 * Return collection of all states/provinces within a country
	 * TODO: caching to make this fetch speedy speedy
	 *
	 * @param string 2 letter country alpha-code
	 * @return Collection
	 */
	public static function getStates($countryA2 = 'US') {
		if(strlen($countryA2) != 2) {
			throw new InvalidValueException;
		}
		
		return Cache::rememberForever('addresses.'.$countryA2.'.states', function() use ($countryA2) {
			return State::where('country_a2', $countryA2)->orderBy('name', 'ASC')->get();
		});
	}
	
	/**
	 * Accept 2 or 3 digit alpha-code
	 * 
	 * @param string $countryA2
	 * @return $string full country name
	 */
	public static function countryName($countryA2) {
		if(strlen($countryA2) != 2) {
			throw new InvalidValueException;
		}

		return Cache::rememberForever('addresses.'.$countryA2.'.country_name', function() use ($countryA2) {
			return Country::byCode($countryA2)->first()->name;
		});
	}

	/**
	 * Accept 2 digit alpha-code. Pass in the country to be extra sure you get the right name returned.
	 * TODO: caching to make this fetch speedy speedy
	 *
	 * @param string $stateA2
	 * @param string $countryA2 defaults to 'US'
	 * @return $string full state/province name
	 */
	public static function stateName($stateA2, $countryA2 = 'US') {
		if(strlen($stateA2) != 2 || strlen($countryA2) != 2) {
			throw new InvalidValueException;
		}
		
		if(empty($countryA2)) {
			return State::byCode($code)->firstOrFail()->name;
		}

		return Cache::rememberForever('addresses.'.$countryA2.'.'.$stateA2.'.state_name', function() use ($stateA2, $countryA2) {
			return State::byCountry($countryA2)->byCode($stateA2)->firstOrFail()->name;
		});
	}

	/**
	 * Wrapper for \Form::select that populated the country list automatically
	 * Defaults to United States as selected
	 * 
	 * @param string $name
	 * @param string $selected
	 * @param array $options
	 */
	public function selectCountry($name, $selected = 'US', $options = array()) {
		$list = array();
		foreach (self::getCountries() as $country) {
			if($country->a2 == 'US') {
				$usa = $country;
			} else {
				$list[$country->a2] = $country->name;
			}
		}
		
		$list = array_merge(array('US'=>$usa->name), $list);

		return \Form::select($name, $list, $selected, $options);
	}
	
	/**
	 * Wrapper for \Form::select that populated the state/province list automatically
	 * Defaults to United States as selected
	 * 
	 * @param string $name
	 * @param string $selected
	 * @param array $options
	 *   $options['country'] = 'US'
	 */
	public function selectState($name, $selected = null, $options = array('country'=>'US')) {
		$list = array(''=>'');
		
		foreach (self::getStates($options['country']) as $state) {
			$list[$state->a2] = $state->name;
		}
		
		unset($options['country']);

		return \Form::select($name, $list, $selected, $options);
	}
	
}