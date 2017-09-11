<?php

/**
 * File: User Rating Polarity Class
 *
 * @copyright  2015 Locomotive
 * @license    PROPRIETARY
 * @link       http://charcoal.locomotive.ca
 * @author     Chauncey McAskill <chauncey@locomotive.ca>
 * @since      Version 2015-09-21
 */

use \Charcoal as Charcoal;

/**
 * Trait: User Rating Polarity
 *
 * A rating toggle between three states:
 *
 * - Positive
 * - Neutral
 * - Negative
 *
 * @package  Core\Objects
 * @used-by  Charcoal_User_Rating_Log
 * @requires const COOKIE_TOKEN
 * @todo     McAskill [2015-09-21] Abstract to "User Rating Toggle"
 */
trait Core_Trait_User_Rating_Polarity
{
	/**
	 * Positive User Rating Count
	 *
	 * @var int
	 * @see Property_Integer
	 */
	public $user_rating_positive;

	/**
	 * Neutral User Rating Count
	 *
	 * @var int
	 * @see Property_Integer
	 */
	public $user_rating_neutral;

	/**
	 * Negative User Rating Count
	 *
	 * @var int
	 * @see Property_Integer
	 */
	public $user_rating_negative;

	/**
	 * Cache the object's user ratings
	 *
	 * @var Charcoal_User_Rating[]|Charcoal_List
	 */
	protected $_ratings;

	/**
	 * Cache the object's rating counts by choice
	 *
	 * @var int[]
	 */
	protected $_ratings_count;

	/**
	 * {@inheritdoc}
	 */
	protected function pre_update()
	{
		$this->_pre_update_user_ratings();

		return parent::pre_update();
	}

	/**
	 * Alias of self::pre_save()
	 */
	protected function _pre_update_user_ratings()
	{
		$this->user_rating_positive = $this->get_rating_count('positive');
		$this->user_rating_negative = $this->get_rating_count('negative');
	}

	/**
	 * Retrieve the Q&A's user ratings of the desired polarity.
	 *
	 * @param int|string $polarity
	 *
	 * @var Pg_FAQ_Item_Rating[]|Charcoal_User_Rating[]|Charcoal_List
	 */
	public function get_ratings( $polarity )
	{
		$polarity = $this->resolve_rating( $polarity );

		if ( ! isset( $this->_ratings[ $polarity ] ) ) {
			$this->_ratings[ $polarity ] = load_ratings( $polarity );
		}

		return $this->_ratings[ $polarity ];
	}

	/**
	 * Import the Q&A's user ratings of the desired polarity.
	 *
	 * @param int|string $polarity
	 *
	 * @var Charcoal_List
	 */
	public function load_ratings( $polarity )
	{
		return Charcoal::obj_list( $this->rating_obj )
				->add_filter('active',      true)
				->add_filter('obj_type',    $this->obj_type())
				->add_filter('obj_id',      $this->id())
				->add_filter('user_rating', $polarity)
				->load();
	}

	/**
	 * Retrive the count of the desired polarity.
	 *
	 * @param int|string $polarity Optional.
	 *
	 * @return int|int[]
	 */
	public function get_rating_count( $polarity = null )
	{
		if ( ! isset( $this->_ratings_count ) ) {
			$proto = Charcoal::obj( $this->rating_obj );

			if ( $proto->table_exists() ) {
				$sql_query = '
					SELECT
						SUM(user_rating =  1) AS positive,
					#	SUM(user_rating =  0) AS neutral,
						SUM(user_rating = -1) AS negative
					FROM
						`' . $proto->table() . '`
					WHERE
						active = 1
					AND
						obj_type = :obj_type
					AND
						obj_id = :obj_id';

				try {
					$obj_type = $this->obj_type();
					$obj_id   = $this->id();

					$sth = db()->prepare( $sql_query );

					$sth->bindParam( ':obj_type', $obj_type );
					$sth->bindParam( ':obj_id', $obj_id );

					$sth->execute();

					$sth->setFetchMode( PDO::FETCH_ASSOC );

					$this->_ratings_count = $sth->fetch();

					unset($sth);
				}
				catch ( PDOException $e ) {
					Charcoal::debug([
						'msg' => sprintf(
							'Cannot load object list. PDO Error [%s]: "%s"',
							$e->getCode(),
							$$e->getMessage()
						)
					]);

					$count = [];
				}
			}
			else {
				$count = [];
			}
		}

		if ( is_null( $polarity ) ) {
			return $this->_ratings_count;
		}

		$polarity = $this->resolve_rating( $polarity, true );

		if ( isset( $this->_ratings_count[ $polarity ] ) ) {
			return $this->_ratings_count[ $polarity ];
		}
		else {
			return 0;
		}
	}

	/**
	 * Retrieved the current rating from the user session.
	 *
	 * @return int|null
	 */
	public function get_rating_from_session()
	{
		$cookie_name = sprintf( static::COOKIE_TOKEN, $this->id() );

		if ( isset( $_COOKIE[ $cookie_name ] ) ) {
			return $_COOKIE[ $cookie_name ];
		}

		return null;
	}

	/**
	 * Determine if the user has already rated this object.
	 *
	 * @return bool
	 */
	public function is_rated()
	{
		return ( ! is_null( $this->get_rating_from_session() ) );
	}

	/**
	 * Determine if the user has rated favorably
	 *
	 * @return string
	 */
	public function is_rated_positive()
	{
		$rating = $this->get_rating_from_session();

		return ( 1 === (int) $rating );
	}

	/**
	 * Determine if the user has rated neutral.
	 *
	 * @return string
	 */
	public function is_rated_neutral()
	{
		$rating = $this->get_rating_from_session();

		return ( 0 === (int) $rating );
	}

	/**
	 * Determine if the user has rated unfavorably.
	 *
	 * @return string
	 */
	public function is_rated_negative()
	{
		$rating = $this->get_rating_from_session();

		return ( -1 === (int) $rating );
	}

	/**
	 * Resolve the provided rating to its real value.
	 *
	 * @param mixed $value
	 * @param bool  $as_string
	 *
	 * @return string|null
	 */
	public function resolve_rating( $value, $as_string = false )
	{
		if ( $as_string ) {
			return $this->__parse_int_to_str_rating( $value );
		}
		else {
			return $this->__parse_str_to_int_rating( $value );
		}

		return $value;
	}

	/**
	 * Parse the provided rating into a integer identifier
	 *
	 * @used-by self::resolve_rating()
	 *
	 * @param mixed $value
	 *
	 * @return int|null
	 */
	protected function __parse_str_to_int_rating( $value )
	{
		if ( in_array( $value, [ 1/*, 0*/, -1 ] ) ) {
			return $value;
		}
		elseif ( is_int( $value ) ) {
			if ( $value > 1 ) {
				return 1;
			}
			elseif ( $value < -1 ) {
				return -1;
			}
			elseif ( $value === 0 ) {
				return 0;
			}
			else {
				return null;
			}
		}
		elseif ( is_bool( $value ) ) {
			return ( $value ? 1 : -1 );
		}
		elseif ( is_string( $value ) ) {
			switch ( strtolower( $value ) ) {
				case '+1':
				case 'positive':
					return 1;

				case '-1':
				case 'negative':
					return -1;

				default:
					return null;
			}
		}

		return $value;
	}

	/**
	 * Parse the provided rating into a string identifier
	 *
	 * @used-by self::resolve_rating()
	 *
	 * @param mixed $value
	 *
	 * @return string|null
	 */
	protected function __parse_int_to_str_rating( $value )
	{
		if ( in_array( $value, [ 'positive'/*, 'neutral'*/, 'negative' ] ) ) {
			return $value;
		}
		elseif ( is_int( $value ) ) {
			if ( $value >= 1 ) {
				return 'positive';
			}
			elseif ( $value <= -1 ) {
				return 'negative';
			}
			/*elseif ( $value === 0 ) {
				return 'neutral';
			}*/
			else {
				return null;
			}
		}
		elseif ( is_bool( $value ) ) {
			return ( $value ? 'positive' : 'negative' );
		}
		elseif ( is_string( $value ) ) {
			switch ( $value ) {
				case '+1':
					return 'positive';

				case '-1':
					return 'negative';

				default:
					return null;
			}
		}

		return $value;
	}
}
