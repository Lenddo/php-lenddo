# Verification Object
_Pass probes to verify your user against_

The Lenddo SDK exposes a verification object which is designed to make integration easier by providing
 easy to consume methods for setting the verification probes. When you send Lenddo verification probes we 
 use the provided data and compare it against observed data in the users' networks to provide you with a 
 verification response. [You can find the verification response here](verification_response).

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Introduction to the Verification Class](#introduction-to-the-verification-class)
- [Available "set" methods](#available-set-methods)
  - [setFirstName( $first_name )](#setfirstname-first_name-)
  - [setMiddleName( $middle_name )](#setmiddlename-middle_name-)
  - [setLastName( $last_name )](#setlastname-last_name-)
  - [setDateOfBirth( $date_of_birth )](#setdateofbirth-date_of_birth-)
  - [setEmployer( $employer )](#setemployer-employer-)
  - [setMobilePhone( $mobile_phone )](#setmobilephone-mobile_phone-)
  - [setUniversity( $university)](#setuniversity-university)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Introduction to the Verification Class
You can find the verification class at `Lenddo\Verification`. Simply instantiate it with no arguments to begin using it.
```php
$verification = new Lenddo\Verification();
```

The class will expose a series of __set*__ methods which all return the verification object, making them easily chainable.
For example, if you wish to set a users' name:
```php
$verification->setFirstName('First')
    ->setMiddleName('Middle')
    ->setLastName('Last');
```

After you perform the necessary __set*__ operations you can pass the `$verification` object instance to the accepting method.
 Currently the only method which can accept this verification object is [Lenddo\WhiteLabelClient::commitPartnerJob](whitelabel_client.md#commitpartnerjob)

## Available "set" methods
### setFirstName( $first_name )
Define the users' first name

### setMiddleName( $middle_name )
Define the users' middle name

### setLastName( $last_name )
Define the users' last name

### setDateOfBirth( $date_of_birth )
Define when this user was born.
**important** This must be defined as YYYY-MM-DD where digits are in place of the respective Y,M, and D positions. 
For example, April 20th 2016 would be written as 2016-04-20. Failure to do this will result in an exception being thrown.

### setEmployer( $employer )
Define the current employer of the user.

### setMobilePhone( $mobile_phone )
Define the mobile phone number of the user.

### setUniversity( $university)
Define the university this user attended.