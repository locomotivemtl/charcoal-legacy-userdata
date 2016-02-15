<?php

/**
 * File: User Organization Class
 *
 * @copyright  2015 Locomotive
 * @license    PROPRIETARY
 * @link       http://charcoal.locomotive.ca
 * @author     Mathieu Ducharme <mat@locomotive.ca>
 * @author     Chauncey McAskill <chauncey@locomotive.ca>
 * @since      Version 2015-0-02
 */

use \Charcoal as Charcoal;

/**
 * Trait: User Organization
 *
 * @package Core\Objects
 */
trait Core_Trait_User_Organization
{
	/**
	 * Organization Name
	 *
	 * Disabled by default.
	 *
	 * @var string
	 * @see Property_String
	 */
	public $organization;

	/**
	 * Title within the organization
	 *
	 * Disabled by default.
	 *
	 * @var string
	 * @see Property_String
	 */
	public $title;
}
