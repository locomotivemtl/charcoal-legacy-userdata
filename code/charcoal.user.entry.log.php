<?php

/**
 * File: User Entry Log Class
 *
 * @copyright  2015 Locomotive
 * @license    PROPRIETARY
 * @link       http://charcoal.locomotive.ca
 * @author     Chauncey McAskill <chauncey@locomotive.ca>
 * @since      Version 2015-09-21
 */

use \Charcoal as Charcoal;

/**
 * Class: User Entry Log
 *
 * This class acts as an alternative to {@see Charcoal_Log}
 *
 * @package Core\Objects
 */
class Charcoal_User_Entry_Log extends Charcoal_Log
{
	use Core_Trait_User_Metadata;

	/**
	 * {@inheritdoc}
	 */
	protected function pre_save( $properties = null )
	{
		$this->_pre_save_user_metadata();

		if ( ! $this->obj_type ) {
			$this->obj_type = '';
		}

		if ( ! $this->obj_id ) {
			$this->obj_id = 0;
		}

		return parent::pre_save($properties);
	}
}
