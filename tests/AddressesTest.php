<?php

use \Conner\Addresses\Country;
use \Conner\Addresses\State;
use \Conner\Addresses\Address;

class AddressesTest extends TestCase {

	public function setUp() {
		Illuminate\Foundation\Testing\TestCase::setUp();

		Artisan::call('migrate', array('--package'=>'rtconner\laravel-addresses'));
		Artisan::call('db:seed', array('--class'=>'Conner\Addresses\DatabaseSeeder'));
		
		$user = new \Cartalyst\Sentry\Users\Eloquent\User;
		$user->id = 1;
		
		\Sentry::setUser($user);
	}
	
	public function testCountries() {
		$this->assertGreaterThan(220, count(\Addresses::getCountries())); // should be like 240 or so
		
		$this->assertEquals(Country::byCode('US')->first(), Country::byCode('USA')->first());
		
		$this->assertEquals('Canada', \Addresses::countryName('CA'));
	}
	
	public function testStates() {
		$this->assertGreaterThan(
				count(\Addresses::getStates('CA')),
				count(\Addresses::getStates('US'))
		);
		
		$state = State::byCountry('US')->byCode('NY')->first();
		$this->assertEquals('New York', $state->name);
		
		$this->assertEquals('Florida', \Addresses::stateName('FL'));
		$this->assertEquals('Nevada', \Addresses::stateName('NV', 'US'));
		
		$this->assertNull(State::byCountry('CA')->byCode('UT')->first());
	}
	
	/**
	 * @expectedException Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function testStateNotFoundException() {
		$this->assertEquals('Nevada', \Addresses::stateName('NV', 'CO'));
	}
	
	public function testSelects() {

		$matcher = array(
			'tag' => 'option',
			'attributes'=>array('value'=>'BB'),
			'content' => 'Barbados',
			'parent'     => array(
				'tag'        => 'select',
				'attributes' => array('name'=>'test_country')));
		$this->assertTag($matcher, \Addresses::selectCountry('test_country'));
		
		$this->assertNotEquals(
				\Addresses::selectState('state', null, array('country'=>'CA')),
				\Addresses::selectState('state')
		);
		
		$matcher = array(
				'tag' => 'option',
				'attributes'=>array('value'=>'NV', 'selected'=>'selected'),
				'content' => 'Nevada',
				'parent'     => array(
					'tag'        => 'select',
					'attributes' => array('name'=>'test_states')));
		$this->assertTag($matcher, \Addresses::selectState('test_states', 'NV'));
		
	}
	
	public function testAddresses() {
		$userId = 33;
		
		$address1 = new Address;
		$address1->user_id = $userId;
		$address1->organization = 'Microsoft Corporation';
		$address1->street = 'One Microsoft Way';
		$address1->city = 'Redmond';
		$address1->state = 'WA';
		$address1->zip = '98052-7329';
		$address1->phone = '(425) 882-8080';
		$this->assertTrue($address1->save());
		
		$this->assertEmpty(\Addresses::getPrimary($userId));
		$this->assertEmpty(\Addresses::getBilling($userId));
		$this->assertEmpty(\Addresses::getShipping($userId));

		$address2 = new Address;
		$address2->user_id = $userId;
		$address2->street = '1600 Pennsylvania Ave NW';
		$address2->city = 'Washington';
		$address2->state = 'DC';
		$address2->zip = '20500';
		$this->assertTrue($address2->save());
		
		$address3 = new Address;
		$address3->user_id = $userId;
		$address3->street = '501 Marlins Way';
		$address3->city = 'Miami';
		$address3->state = 'FLoridA';
		$address3->zip = '33125';
		$address3->country = 'United States';
		$this->assertTrue($address3->save());
		$this->assertEquals('US', $address3->country);
		$this->assertEquals('United States', $address3->country_name);
		$this->assertEquals('FL', $address3->state);
		$this->assertEquals('Florida', $address3->state_name);

		$address4 = new Address;
		$address4->user_id = 999999;
		$address4->street = '451 Test Street.';
		$address4->city = 'Salt Lake City';
		$address4->state = 'UT';
		$address4->zip = '84111';
		$this->assertTrue($address4->save());
		
		$all = Addresses::getAll($userId);
		$this->assertContainsOnlyInstancesOf('Conner\Addresses\Address', $all);
		$this->assertCount(3, $all);
		
		Addresses::setPrimary($address3);
		$this->assertEquals($address3->id, \Addresses::getPrimary($userId)->id);

		Addresses::setPrimary($address1);
		$this->assertNotEquals($address3->id, \Addresses::getPrimary($userId)->id);
		
		
		Addresses::setBilling($address2);
		$this->assertEquals($address2->id, \Addresses::getBilling($userId)->id);
		
		Addresses::setBilling($address1);
		$this->assertNotEquals($address2->id, \Addresses::getBilling($userId)->id);
		
		
		Addresses::setShipping($address3);
		$this->assertEquals($address3->id, \Addresses::getShipping($userId)->id);
		
		Addresses::setShipping($address1->id);
		$this->assertNotEquals($address2->id, \Addresses::getShipping($userId)->id);
		
		$html = '<span><em>'.\Addresses::getShipping($userId)->toHtml('</em><em>').'</em></span>';
		$matcher = array(
			'tag'  => 'span',
			'children' => array(
				'greater_than' => 3,
				'only' => array('tag' => 'em')));
		$this->assertTag($matcher, $html);
		
	}
	
	public function testValidator() {
		$data = array(
			'street'=>'123 Test Street',
			'city'=>'Las Vegas',
			'state'=>'Nevada',
			'country'=>'US',
			'zip'=>'12345',
			'phone'=>'(234) 234-2345',
		);
		
		$valid = \Addresses::getValidator($data);
		$this->assertTrue($valid->passes());
		
		$data = array(
				'street'=>'123 Test Street',
				'city'=>'Las Vegas',
				'state'=>'Nevada',
				'country'=>'US',
				'zip'=>'12345-2345',
		);
		$valid = \Addresses::getValidator($data);
		$this->assertTrue($valid->passes());

		$data = array(
				'street'=>'123 Test Street',
				'city'=>'Las Vegas',
				'state'=>'Nevada',
				'country'=>'US',
				'zip'=>'12345-23457',
		);
		$valid = \Addresses::getValidator($data);
		$this->assertTrue($valid->fails());
	}
	
	public function testCreate() {
		$address = Addresses::createAddress(array(
				'id'=>23,
				'user_id'=>1,
				'street'=>'123 Test Street',
				'city'=>'Las Vegas',
				'state'=>'Nevada',
				'country'=>'US',
				'zip'=>'23455',
				'phone'=>'(234) 234-2345',
		));
	
		$this->assertInternalType('integer', $address->id);
		$this->assertNotEquals($address->id, 23);
	}
	
}