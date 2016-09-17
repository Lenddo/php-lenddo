<?php

namespace Lenddo\tests\cases;

use Lenddo\Verification;

class VerificationTest extends \PHPUnit_Framework_TestCase {
	public function testVerificationInstantiation() {
		$this->assertInstanceOf('Lenddo\Verification', new Verification());
	}

	public function testSetMethods() {
		$expectedSetMethods = array(
			'FirstName' => 'fn',
			'MiddleName' => 'mn',
			'LastName' => 'ln',
			'DateOfBirth' => '1988-05-04',
			'Employer' => 'Lenddo',
			'MobilePhone' => 'mp',
			'University' => 'u',
			'Email' => 'support@lenddo.com'
		);

		$verification = new Verification();

		foreach($expectedSetMethods as $verificationKey => $value) {
			$verification->{'set' . $verificationKey}($value);
		}

		$this->assertEquals($verification->export(), array(
			'name' => array(
				'first' => 'fn',
				'middle' => 'mn',
				'last' => 'ln'
			),
			'date_of_birth' => '1988-05-04',
			'employer' => 'Lenddo',
			'phone' => array(
				'mobile' => 'mp'
			),
			'university' => 'u',
			'email' => 'support@lenddo.com'
		));
	}

	public function testNameOnly() {
		$verification = new Verification();
		$verification->setFirstName('Howard')
			->setLastName('Lince III');

		$this->assertEquals($verification->export(), array(
			'name' => array(
				'first' => 'Howard',
				'last' => 'Lince III'
			)
		));
	}

	public function testDateException() {
		$this->setExpectedException('Lenddo\exceptions\ValueException');

		$verification = new Verification();
		$verification->setDateOfBirth('foo');
	}
}