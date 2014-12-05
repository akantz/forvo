<?php

class ForvoApi
{
	const FORMAT_XML = 1;
	const FORMAT_JSON = 2;
	const FORMAT_JS_TAG = 3;

	const SEX_MALE = 'm';
	const SEX_FEMALE = 'f';

	const ORDER_DATE_DESC = 'date-desc';
	const ORDER_DATE_ASC = 'date-ask';
	const ORDER_RATE_DESC = 'rate-desc';
	const ORDER_RATE_ASC = 'rate-asc';

	//----------------------------------------------------------------------------------------------------------------------

	/**
	 * Your API key at forvo.com ( you can get one on http://api.forvo.com/documentation/word-pronunciations/ )
	 *
	 * @var string
	 */
	protected $_apiKey;

	/**
	 * What to do...
	 *
	 * @var string
	 */
	protected $_action = 'word-pronunciations';

	/**
	 * Format, which you want to use for API's answers
	 *
	 * @var int
	 */
	protected $_format = self::FORMAT_JSON;

	/**
	 * Language for results. Default en. If you want to change language, you should get list of available languages, and choice one.
	 *
	 * @var string
	 */
	protected $_language = 'en';

	/**
	 * To get only the pronunciations recorded by users of this country.
	 * You should use the Alpha-3 code.
	 *
	 * @var string
	 */
	protected $_country = 'USA';

	/**
	 * If you want to get the pronunciation recorded by a specific user of forvo.com set this property to his login
	 *
	 * @var null
	 */
	protected $_username = null;

	/**
	 * The gender of pronouncer
	 *
	 * @var string
	 */
	protected $_sex = self::SEX_MALE;

	/**
	 * Integer Value
	 *
	 * @var
	 */
	protected $_minimalRate;

	/**
	 * Use class constants :)
	 *
	 * date-desc (pronunciations order by pronounced time, from recent to older)
	 * date-asc (pronunciations order by pronounced time, older to recent)
	 * rate-desc (pronunciations order by rate, high rated first)
	 * rate-asc (pronunciations order by rate, low rated first)
	 *
	 * @var string
	 */
	protected $_order = self::ORDER_RATE_DESC;

	/**
	 * Group pronunciations in languages. Default value is "false"
	 *
	 * @var bool
	 */
	protected $_groupInLanguages = false;

	/**
	 * any integer number. Max. pronunciations returned
	 *
	 * @var int
	 */
	public $limit = 2;

	public $word = '';


	//----------------------------------------------------------------------------------------------------------------------

	private static $instance = null;

	/**
	 * @return ForvoApi
	 */
	public static function getInstance()
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	private function __construct() {}
	private function __clone() {}
	private function  __wakeup() {}

	//----------------------------------------------------------------------------------------------------------------------

	public function setApiKey( $apiKey )
	{
		if (strlen($apiKey) == 32)
			$this->_apiKey = $apiKey;
		else
			throw new Exception('API key is not valid!');
	}

	public function getApiKey()
	{
		return $this->_apiKey;
	}



} 