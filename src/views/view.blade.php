<?php

if(empty($address)) {
	return '';
}

if(!isset($separator)) {
	$separator = '<br>';
}

$html = array();
foreach(array('addressee', 'organization', 'street', 'street_extra') as $line) {
	if(strlen($address->{$line})) {
		$html []= e($address->{$line});
	}
}
 
if(strlen($address->city)) {
	$html []= sprintf('%s, %s %s', e($address->city), e($address->state), e($address->zip));
}

if(\Config::get('addresses::show_country') && strlen($address->country_name)) {
	$html []= e($address->country_name);
}

if(strlen($address->phone)) {
	$html []= e($address->phone);
}
 
$return = implode($separator, $html);
 
if(!empty($this->is_primary) || !empty($this->is_default)) {
	$return = '<strong>'.$return.'</strong>';
}

echo $return;