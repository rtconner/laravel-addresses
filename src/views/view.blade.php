<?php

if(empty($prefix)) {
	$prefix = '';
}

if(empty($address)) {
	return '';
}

if(!isset($separator)) {
	$separator = '<br>';
}

$html = array();
foreach(array('addressee', 'organization', 'street', 'street_extra') as $line) {
	if(strlen($address->{$prefix.$line})) {
		$html []= e($address->{$prefix.$line});
	}
}
 
if(strlen($address->{$prefix.'city'})) {
	$html []= sprintf('%s, %s %s', e($address->{$prefix.'city'}), e($address->{$prefix.'state'}), e($address->{$prefix.'zip'}));
}

if(\Config::get('addresses::show_country') && strlen($address->{$prefix.'country_name'})) {
	$html []= e($address->country_name);
}

if(strlen($address->phone)) {
	$html []= e($address->{$prefix.'phone'});
}
 
$return = implode($separator, $html);
 
if(!empty($this->is_primary) || !empty($this->is_default)) {
	$return = '<strong>'.$return.'</strong>';
}

echo $return;