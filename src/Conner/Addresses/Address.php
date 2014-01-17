<?php namespace Conner\Addresses;

class Address extends \Eloquent {

    protected $table = 'addresses';
    public $timestamps = false;

    protected static $rules = array(
		'user_id' => array('required'),
		'street' => array('required'),
		'city' => array('required'),
		'state_a2' => array('required'),
    );

    public function getCountryAttribute() {
    	return $this->attributes['country_a2'];
    }
    
    public function setCountryNameAttribute() {
    	throw new InvalidOperationException;
    }
    
    public function getStateAttribute() {
    	return $this->attributes['state_a2'];
    }
    
    public function setStateNameAttribute() {
    	throw new InvalidOperationException;
    }
    
    public function setCountryAttribute($value) {
    	if(strlen($value) == 2) {
    		$this->attributes['country_a2'] = strtoupper($value);
    	} else {
    
    		$operator = \Config::get('database.default')=='pgsql' ? 'ilike' : 'like';
    		$country = Country::where('name', $operator, $value)->first();
    		if($country) {
    			$this->attributes['country_a2'] = $country->a2;
    		}

    	}
    	
    	$this->attributes['country_name'] = Addresses::countryName($this->attributes['country_a2']); 
    }
    
    public function setStateAttribute($value) {
    	if(strlen($value) == 2) {
			$this->attributes['state_a2'] = strtoupper($value);
    	} else {
    		$operator = \Config::get('database.default')=='pgsql' ? 'ilike' : 'like';
    		$state = State::where('name', $operator, $value)->first();
	    	if($state) {
	    		$this->attributes['state_a2'] = $state->a2;
	    	}
    	}
		
    	$this->attributes['state_name'] = Addresses::stateName($this->attributes['state_a2']);
    }
    
    function toHtml($separator='<br />') {
    	$html = array();
    	foreach(array('addressee', 'organization', 'street', 'street_extra') as $line) {
			if(strlen($this->{$line})) {
				$html []= e($this->{$line});
			}
    	}
    	
    	if(strlen($this->city)) {
    		$html []= sprintf('%s, %s %s', e($this->city), e($this->state), e($this->zip));
    	}
    	
    	foreach(array('country_name', 'phone') as $line) {
    		if(strlen($this->{$line})) {
    			$html []= e($this->{$line});
    		}
    	}
    	
    	return implode($separator, $html);
    }
    
}
