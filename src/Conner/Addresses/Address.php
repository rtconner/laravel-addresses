<?php namespace Conner\Addresses;

class Address extends \Eloquent {

	protected $table = 'addresses';
	public $timestamps = false;
	protected $fillable = array('addressee', 'organization', 'street', 'street_extra', 'city', 'state', 'zip', 'country', 'phone', 'is_primary', 'is_shipping', 'is_billing');
	protected $guarded = array('id', 'state_a2', 'country_a2', 'state_name', 'country_name', 'user_id');
	protected $appends = array('state', 'country');

	public static function boot() {
		parent::boot();

		static::saving(function($address) {
			if(\Config::get('addresses::geocode')) {
				$address->geocode();
			}
		});
	}
	
	public static function rules() {
		$rules = array(
			'adressee'=>'Max:100',
			'street'=>'required|Max:100',
			'city'=>'required',
			'state_a2'=>'required|Alpha|size:2',
			'zip'=>'required|AlphaDash|Min:5|Max:10', // https://www.barnesandnoble.com/help/cds2.asp?PID=8134
		);
		
		if(\Config::get('addresses::show_country')) {
			$rules['country_a2'] = 'required|Alpha|size:2';
		}
		
		return $rules;
	}

	public function getCountryAttribute() {
		if(array_key_exists('country_a2', $this->attributes)) {
			return $this->attributes['country_a2'];
		}

		return \Config::get('addresses::default_country');
	}
    
    public function setCountryNameAttribute() {
    	throw new InvalidOperationException;
    }
    
    public function getStateAttribute() {
    	return @$this->attributes['state_a2'];
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

    	if(!empty($this->attributes['state_a2'])) {
    		$this->attributes['state_name'] = Addresses::stateName($this->attributes['state_a2']);
    	}
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
    	
    	$return = implode($separator, $html);
    	
    	if($this->is_primary) {
    		$return = '<strong>'.$return.'</strong>';
    	}
    	
    	return $return;
    }
    
    /**
     * Using the address in memory, fetch get latitude and longitude
     * from google maps api and set them as attributes
     */
    public function geocode() {
    	if(!empty($this->zip)) {
	    	$string[] = $this->street;
	    	$string[] = sprintf('%s, %s %s', $this->city, $this->state, $this->zip);
	    	$string[] = $this->country_name;
    	}
    	
	    $query = str_replace(' ', '+', implode(', ', $string));
	    
	    $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$query.'&sensor=false');
	    $output= json_decode($geocode);
	    
	    $this->latitude = $output->results[0]->geometry->location->lat;
	    $this->longitude = $output->results[0]->geometry->location->lng;

	    return $this;
    }
    
}
