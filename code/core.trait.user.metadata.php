<?php

/**
 * File: User Entry Metadata Class
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
 * Trait: User Entry Metadata
 *
 * @package Core\Objects
 */
trait Core_Trait_User_Metadata
{
	/**
	 * Date and time of user entry creation.
	 *
	 * @var string|DateTime
	 * @see Property_DateTime
	 */
	public $date_created;

	/**
	 * Origin page of user entry submission.
	 *
	 * @var string
	 * @see Property_URL
	 */
	public $source_url;

	/**
	 * User Session ID
	 *
	 * @var string
	 * @see Property_String
	 */
	public $session_id;

	/**
	 * User Agent
	 *
	 * @var string
	 * @see Property_String
	 */
	public $user_agent;

	/**
	 * User Language
	 *
	 * @var string
	 * @see Property_Lang
	 */
	public $user_lang;

	/**
	 * User IP Address
	 *
	 * @var string
	 * @see Property_IP
	 */
	public $user_ip;

	/**
	 * {@inheritdoc}
	 */
	protected function pre_save( $properties = null )
	{
		$this->_pre_save_user_metadata();

		return parent::pre_save($properties);
	}

	/**
	 * Alias of self::pre_save()
	 */
	protected function _pre_save_user_metadata()
	{
		// Timestamp should always be the actual time
		$this->date_created = date('Y-m-d H:i:s');

		// Log the current session ID
		$this->session_id = session_id();

		// Where the entry was submitted from
		if ( ! $this->source_url ) {
			$this->source_url = ( Charcoal::$config['HTTP_MODE'] . getenv('HTTP_HOST') . getenv('REQUEST_URI') );
		}

		$this->user_ip = ( ( $ip = $this->get_ip() ) ? ip2long($ip) : '' );

		// User Agent is determined automatically from the server
		$this->user_agent = ( ( $ua = getenv('HTTP_USER_AGENT') ) ? ( strlen($ua) > 250 ? substr($ua, 0, 250) : $ua ) : '' );

		// If the language was not set explicitely, set the current one in use
		if ( ! $this->user_lang ) {
			$this->user_lang = _l();
		}
	}

	/**
	 * Retrieve the current IP address
	 *
	 * @see Slim\Http\Request::getIp() Inspired by Slim's method.
	 *
	 * @return string
	 */
	public function get_ip()
	{
		// IP is determined automatically from the server
		$headers = [ 'HTTP_X_FORWARDED_FOR', 'X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'CLIENT_IP', 'REMOTE_ADDR' ];
		foreach ( $headers as $header ) {
			$ip = getenv($header);

			if ( $ip ) {
				break;
			}
		}

		if ( $ip ) {
			// HTTP_X_FORWARDED_FOR can return a comma separated list of IPs; extract the first one
			$ip = explode(',', $ip);
			$ip = reset($ip);
		}

		return $ip;
	}

	/**
	 * Get the replacement array for an email template
	 *
	 * @return array The replacements in `key => value` pairs
	 */
	protected function _user_metadata_email_replacements()
	{
		$replacements = [
			'user_ip'      => $this->p('user_ip')->text(),
			'user_agent'   => $this->p('user_agent')->text(),
			'user_lang'    => $this->p('user_lang')->text(),
			'source_url'   => $this->p('source_url')->text(),
			'date_created' => $this->p('date_created')->text()
		];

		return $replacements;
	}
}
