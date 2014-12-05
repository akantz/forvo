<?php

/**
 * Class ForvoApi
 *
 * @property string $apiKey             Your API key at forvo.com ( you can get one on http://api.forvo.com/documentation/word-pronunciations/ )
 * @property string $action             What to do...
 * @property strint $format             Format, which you want to use for API's answers (Use class constants)
 * @property string $language           Language for results. Default en. If you want to change language, you should get list of available languages, and choice one.
 * @property string $country            To get only the pronunciations recorded by users of this country.
 *                                      You should use the Alpha-3 code.
 * @property string $username           If you want to get the pronunciation recorded by a specific user of forvo.com set this property to his login
 * @property string $sex                The gender of pronouncer
 * @property integer $minimalRate
 * @property integer $order             Use class constants :) It's order for results.
 * @property boolean $groupInLanguages  Group pronunciations in languages. Default value is "false"
 * @property integer $limit             any integer number. Max. pronunciations returned
 * @property string $word               word to pronounce
 *
 *
 * @property string $cacheDirectory
 * @property string $apiForvoUrl        Default is http://apifree.forvo.com
 * @property integer $curlTimeout
 * @property integer $mediaFileFormat   Use class constants
 */
class ForvoApi extends BaseApiComponent
{
	const FORMAT_XML = 'xml';
	const FORMAT_JSON = 'json';
	const FORMAT_JS_TAG = 'js-tag';

	const SEX_MALE = 'm';
	const SEX_FEMALE = 'f';

	const ORDER_DATE_DESC = 'date-desc';
	const ORDER_DATE_ASC = 'date-ask';
	const ORDER_RATE_DESC = 'rate-desc';
	const ORDER_RATE_ASC = 'rate-asc';

	//----------------------------------------------------------------------------------------------------------------------

	protected $_properties = [
		'apiKey' => '',
		'action' => 'word-pronunciations',
		'format' => self::FORMAT_JSON,
		'language' => null,
		'country' => null,
		'username' => null,
		'sex' => null,
		'minimalRate' => '',
		'order' => self::ORDER_RATE_DESC,
		'groupInLanguages' => false,
		'limit' => null,
		'word' => '',

		'cacheDirectory' => '',
		'apiForvoUrl' => 'http://apifree.forvo.com',
		'curlTimeout' => 10,
		'mediaFileFormat' => MediaFile::MEDIA_FILE_FORMAT_MP3,
	];

	protected $_curl = null;

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

	/**
	 * @param $apiKey
	 * @return ForvoApi
	 */
	public function setApiKey($apiKey)
	{
		$this->apiKey = $apiKey;
		return self::$instance;
	}

	/**
	 * @param $param
	 * @param $value
	 *
	 * @return ForvoApi
	 */
	public function set($param, $value)
	{
		self::$instance->$param = $value;
		return self::$instance;
	}

	/**
	 * @param mixed $word. If it's string - we check only one word, if array - each word in array
	 * @param int $count
	 *
	 * @return MediaFile[]
	 */
	public function getPronounce( $word )
	{
		$this->set('word', $word);

		return $this->_getMediaFiles( $this->_makeRequest() );
	}

	protected function _makeRequest()
	{
		try
		{
			if (!function_exists('curl_version'))
				throw new Exception('Sorry, but curl extension is not installed');

			$this->_curl = curl_init();

			curl_setopt($this->_curl, CURLOPT_URL, $this->_makeUrl());
			curl_setopt($this->_curl, CURLOPT_TIMEOUT, $this->curlTimeout);
			curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);

			$content = curl_exec( $this->_curl );
			return $content;
		}
		catch (Exception $e)
		{
		    echo $e->getMessage();
		}
		finally
		{
			curl_close( $this->_curl );
		}
	}

	protected function _makeUrl()
	{
		$url = $this->apiForvoUrl
			. '/key/' . $this->apiKey
			. '/format/' . $this->format
			. '/action/' . $this->action
			. '/word/' . $this->word
			. (!empty($this->language) ? '/language/' . $this->language : '')
			. (!empty($this->country) ? '/country/' . $this->country : '')
			. (!empty($this->username) ? '/username/' . $this->username : '')
			. (!empty($this->sex) ? '/sex/' . $this->sex : '')
			. (!empty($this->minimalRate) ? '/rate/' . $this->minimalRate : '')
			. (!empty($this->order) ? '/order/' . $this->order : '')
			. (!empty($this->groupInLanguages) && $this->groupInLanguages === true ? '/group-in-language/' . $this->groupInLanguages : '')
			. (!empty($this->limit) ? '/limit/' . $this->limit : '')
		;

		return $url;
	}

	protected function _getMediaFiles( $content )
	{
		if (empty($content))
			throw new Exception('Sorry, but content is empty!');

		return MediaFilesFabric::getFiles($content, $this->format);
	}
} 