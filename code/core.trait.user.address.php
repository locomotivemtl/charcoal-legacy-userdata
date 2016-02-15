<?php

/**
 * File: User Address Class
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
 * Trait: User Address
 *
 * This Trait of properties is practical if the components of a
 * user's address are important to distinguish, filter, and sort.
 *
 * If the address is a minor detail, it is recommended you just
 * implement {@see Property_Address} into your object.
 *
 * The pre-defined properties are taken from {@see Property_Address}.
 *
 * @package Core\Objects
 */
trait Core_Trait_User_Address
{
	/**
	 * Value of intermediary "Civic Address" property
	 *
	 * @var string
	 * @see self::$_address_street
	 */
	public $address_street;

	/**
	 * Civic Address (Level 1)
	 *
	 * @var string
	 * @see Property_String
	 */
	public $address_level_1;

	/**
	 * Civic Address (Level 2)
	 *
	 * @var string
	 * @see Property_String
	 */
	public $address_level_2;

	/**
	 * Municipality
	 *
	 * @var string
	 * @see Property_String
	 */
	public $locality;

	/**
	 * Administrative Area (Level 1)
	 *
	 * @var string
	 * @see Property_String
	 */
	public $administrative_area_level_1;

	/**
	 * Administrative Area (Level 2)
	 *
	 * @var string
	 * @see Property_String
	 */
	public $administrative_area_level_2;

	/**
	 * Postal Code
	 *
	 * @var string
	 * @see Property_String
	 */
	public $postal_code;

	/**
	 * Country
	 *
	 * @var string
	 * @see Property_String
	 */
	public $country;

	/**
	 * Storage of intermediary "Civic Address" properties:
	 *
	 * 1. `address_level_1`
	 * 2. `address_level_2`
	 *
	 * @var Property_Text
	 * @see self::address_street()
	 */
	protected $_address_street;

	/**
	 * Retrieve an intermediary Charcoal_Property that merges:
	 *
	 * 1. `address_level_1`
	 * 2. `address_level_2`
	 *
	 * @var string
	 * @see Property_Text
	 */
	public function get_address_street()
	{
		$ident = 'address_street';

		if ( $this->{ "_{$ident}" } ) {
			return $this->{ "_{$ident}" };
		}

		$prop = Charcoal::property('text');
		$prop->ident = 'address_street';
		$prop->set_obj( $this );

		$prop->level_1 = &$this->p('address_level_1');
		$prop->level_2 = &$this->p('address_level_2');

		if ( $prop->level_1->val() ) {
			$content[] = $prop->level_1->val();
		}

		if ( $prop->level_2->val() ) {
			$content[] = $prop->level_2->val();
		}

		$prop->set_val( implode( "\n", $content ) );

		$this->{ $ident }      = $prop->val();
		$this->{ "_{$ident}" } = $prop;

		return $prop;
	}

	/**
	 * Get the replacement array for an email template
	 *
	 * @return array The replacements in `key => value` pairs
	 */
	protected function _user_address_email_replacements()
	{
		$replacements = [
			'address_street'              => $this->get_address_street()->text(),
			'address_level_1'             => $this->p('address_level_1')->text(),
			'address_level_2'             => $this->p('address_level_2')->text(),
			'locality'                    => $this->p('locality')->text(),
			'administrative_area_level_1' => $this->p('administrative_area_level_1')->text(),
			'administrative_area_level_2' => $this->p('administrative_area_level_2')->text(),
			'postal_code'                 => $this->p('postal_code')->text(),
			'country'                     => $this->p('country')->text(),
		];

		return $replacements;
	}
}
