<?php namespace Conner\Addresses;

class Country extends \Eloquent {

    protected $table = 'countries';
    public $timestamps = false;
    public static $unguarded = true;
    
    public function states() {
    	return $this->hasMany('\Conner\Addresses\State', 'country_a2', 'a2');
    }

    public static function scopeByCode($query, $code) {
    	if(strlen($code) == 2) {
    		return $query->where('a2', '=', $code);
    	} elseif(strlen($code) == 3) {
    		return $query->where('a3', '=', $code);
    	}
    }
    
}
