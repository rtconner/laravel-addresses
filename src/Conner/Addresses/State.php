<?php namespace Conner\Addresses;

class State extends \Eloquent {

    protected $table = 'states';
    public $timestamps = false;
    public static $unguarded = true; 
    
    public function country() {
    	return $this->hasOne('\Conner\Addresses\Country', 'a2', 'country_a2');
    }
    
    /**
     * Fetch one (fist one it finds) state/province matching it's 2 digit alpha-code. Searches US states first, then all countries.
     * Pass in countryA2 code to look for exact state/province
     *  
     * @param $string $code
     * @param string $countryA2
     */
    public static function scopeByCode($query, $code) {
    	return $query->where('a2', '=', $code);
    }

    /**
     * Fetch one (fist one it finds) state/province matching it's 2 digit alpha-code. Searches US states first, then all countries.
     * Pass in countryA2 code to look for exact state/province
     *
     * @param $string $code
     * @param string $countryA2
     */
    public function scopeByCountry($query, $countryCode) {
    	if(strlen($countryCode) == 2) {
    		return $query->where('country_a2', '=', $countryCode);
    	}
    	
    	throw new \Exception('Must Use 2-Digit Alpha-Code to find a State');
    }
    
}
