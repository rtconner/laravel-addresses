<?php namespace Conner\Addresses;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Addresses 
 * linked to the user
 * 
 * @author rtconner
 */
trait Addressable {

	/**
	 * Return collection of addresses related to the tagged model
	 * 
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function addresses() {
		return $this->hasMany('\Conner\Addresses\Address');
	}
	
	/**
	 * Fetch primary address
	 * 
	 * @return Address or null
	 */
	public function primaryAddress() {
		return $this->addresses()->where('is_primary', true)->first();	
	}
	
	/**
	 * Fetch billing address
	 * 
	 * @return Address or null
	 */
	public function billingAddress() {
		return $this->addresses()->where('is_billing', true)->first();	
	}
	
	/**
	 * Fetch billing address
	 * 
	 * @return Address or null
	 */
	public function shippingAddress() {
		return $this->addresses()->where('is_shipping', true)->first();	
	}
	

}