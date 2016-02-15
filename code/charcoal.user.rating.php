<?php

/**
 * File: User Rating Class
 *
 * @copyright  2015 Locomotive
 * @license    PROPRIETARY
 * @link       http://charcoal.locomotive.ca
 * @author     Chauncey McAskill <chauncey@locomotive.ca>
 * @since      Version 2015-09-21
 */

use \Charcoal as Charcoal;

/**
 * Class: User Rating
 *
 * To be used for evaluating a user's sentiment towards an object.
 *
 * Can be used for ratings, votes, likes, dislikes, etc.
 *
 * @package Core\Objects
 */
class Charcoal_User_Rating extends Charcoal_User_Entry_Log
{
	/**
	 * The User's Rating
	 *
	 * @var int
	 * @see Property_Integer
	 */
	public $user_rating;

	/**
	 * The User's Comment, if any
	 *
	 * @var string
	 * @see Property_Text
	 */
	public $user_comment;

	/**
	 * {@inheritdoc}
	 */
	public function set_obj( Charcoal_Base $obj )
	{
		$this->event_type = ( isset( $obj->rating_type ) ? $obj->rating_type : '' );
		$this->obj_type   = $obj->obj_type();
		$this->obj_id     = $obj->id();
	}
}
