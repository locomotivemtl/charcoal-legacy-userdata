<?php

/**
 * File: User Identity Class
 *
 * @copyright  2015 Locomotive
 * @license    PROPRIETARY
 * @link       http://charcoal.locomotive.ca
 * @author     Mathieu Ducharme <mat@locomotive.ca>
 * @author     Chauncey McAskill <chauncey@locomotive.ca>
 * @since      Version 2015-08-31
 */

use \Charcoal as Charcoal;

/**
 * Trait: User Identity
 *
 * @package Core\Objects
 */
trait Core_Trait_User_Identity
{
	/**
	 * Email Address
	 *
	 * Disabled by default.
	 *
	 * @var string
	 * @see Property_Email
	 */
	public $email;

	/**
	 * Telephone Number
	 *
	 * Disabled by default.
	 *
	 * @var string
	 * @see Property_Phone
	 */
	public $telephone;

	/**
	 * Gender / Sex
	 *
	 * Disabled by default.
	 *
	 * @var string
	 * @see Property_Choice
	 */
	public $gender;

	/**
	 * A Personal Name
	 *
	 * Enabled by default, single-field alternative
	 * to "First Name" and "Last Name".
	 *
	 * @var string
	 * @see Property_String
	 */
	public $name;

	/**
	 * First Name
	 *
	 * Disabled by default.
	 *
	 * @var string
	 * @see Property_String
	 */
	public $name_first;

	/**
	 * Last Name
	 *
	 * Disabled by default.
	 *
	 * @var string
	 * @see Property_String
	 */
	public $name_last;
}
