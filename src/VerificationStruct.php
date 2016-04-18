<?php

namespace Lenddo;

use Lenddo\exceptions\ValueException;

class VerificationStruct {
	protected $_first_name;
	protected $_middle_name;
	protected $_last_name;
	protected $_date_of_birth;
	protected $_employer;
	protected $_mobile_phone;
	protected $_university;

	/**
	 * @param mixed $first_name
	 * @return VerificationStruct
	 */
	public function setFirstName($first_name)
	{
		$this->_first_name = $first_name;
		return $this;
	}

	/**
	 * @param mixed $middle_name
	 * @return VerificationStruct
	 */
	public function setMiddleName($middle_name)
	{
		$this->_middle_name = $middle_name;
		return $this;
	}

	/**
	 * @param mixed $last_name
	 * @return VerificationStruct
	 */
	public function setLastName($last_name)
	{
		$this->_last_name = $last_name;
		return $this;
	}

	/**
	 * @param mixed $date_of_birth
	 * @return VerificationStruct
	 * @throws ValueException
	 */
	public function setDateOfBirth($date_of_birth)
	{
		if (preg_match('/\d{4}-\d{2}-\d{2}/', $date_of_birth) < 1) {
			throw new ValueException('Invalid Date of Birth Value. Please use the YYYY-MM-DD pattern.');
		}
		$this->_date_of_birth = $date_of_birth;
		return $this;
	}

	/**
	 * @param mixed $employer
	 * @return VerificationStruct
	 */
	public function setEmployer($employer)
	{
		$this->_employer = $employer;
		return $this;
	}

	/**
	 * @param mixed $mobile_phone
	 * @return VerificationStruct
	 */
	public function setMobilePhone($mobile_phone)
	{
		$this->_mobile_phone = $mobile_phone;
		return $this;
	}

	/**
	 * @param mixed $university
	 * @return VerificationStruct
	 */
	public function setUniversity($university)
	{
		$this->_university = $university;
		return $this;
	}

	/**
	 * Export the verification object as it should be structured. Filter out undefined/null values.
	 *
	 * @return array
	 */
	public function export() {
		return array_filter(array(
			'name' => array_filter(array(
				'first' => $this->_first_name,
				'middle' => $this->_middle_name,
				'last' => $this->_last_name
			)),
			'date_of_birth' => $this->_date_of_birth,
			'employer' => $this->_employer,
			'phone' => array_filter(array(
				'mobile' => $this->_mobile_phone
			)),
			'university' => $this->_university
		));
	}
}
