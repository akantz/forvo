<?php

/**
 * Class ForvoApi
 *
 * @property string $apiKey             Your API key at forvo.com ( you can get one on http://api.forvo.com/documentation/word-pronunciations/ )
 * @property string $action             What to do...
 * @property string $format             Format, which you want to use for API's answers (Use class constants)
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
 * @property integer $minPronunciations Min count for pronunciations (for language-list action).
 * @property integer $pagesize          For methods of word-search.
 * @property integer $page
 *
 *
 * @property string $cacheDirectory
 * @property string $apiForvoUrl        Default is http://apifree.forvo.com
 * @property integer $curlTimeout
 * @property string $mediaFileFormat    Use class constants
 * @property boolean $download          Return only links to files (false by default behavior)
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
		'word' => null,
		'minPronunciations' => null,
		'pagesize' => null,
		'page' => null,
		'search' => null,

		'cacheDirectory' => '',
		'apiForvoUrl' => 'http://apifree.forvo.com',
		'curlTimeout' => 10,
		'mediaFileFormat' => MediaFile::MEDIA_FILE_FORMAT_MP3,
		'download' => false,
	];

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
	 * Gets all the pronunciations from a word. If $standartPronunciation is set to true, you will get standart (top-rated) pronunciation of the word.
	 * default it's false.
	 *
	 * @param mixed $word.
	 *
	 * @return MediaFile[]
	 */
	public function getPronunciation( $word, $standartPronunciation = false )
	{
		$this->set('word', $word);

		$this->set('action',
			$standartPronunciation ? 'standard-pronunciation' : 'word-pronunciations'
		);

		return $this->_getMediaFiles( $this->curlGetContent( $this->_makeUrl(), $this->curlTimeout ) );
	}

	/**
	 * Gets languages availables at Forvo. If $popular is set to true, method returns only popular languages. Default it's false.
	 *
	 * @param bool $popular
	 * @return array
	 * @throws Exception
	 */
	public function getLanguageList($popular = false)
	{
		$this->set('action',
			$popular ? 'language-popular' : 'language-list'
		);
		$content = $this->curlGetContent( $this->_makeUrl() );

		return ResourceFabric::getLanguagesArray($content, $this->format);
	}


	/**
	 * Not for use! Still in development!
	 *
	 * @param $pattern
	 * @return bool|mixed
	 * @throws Exception
	 */
	public function wordSearch($pattern)
	{
		$this->set('action', 'words-search')
			->set('search', $pattern);

		$content = $this->curlGetContent( $this->_makeUrl(), $this->format );
		return $content; // $this->_getMediaFiles( $content );
	}

	public function resetToDefault()
	{
		$this->set('action', 'word-pronunciations')
			 ->set('format', self::FORMAT_JSON)
			 ->set('language', null)
			 ->set('country', null)
			 ->set('username', null)
			 ->set('sex', null)
			 ->set('minimalRate', '')
			 ->set('order', self::ORDER_RATE_DESC)
			 ->set('groupInLanguages', false)
			 ->set('limit', null)
			 ->set('word', null)
			 ->set('mediaFileFormat', MediaFile::MEDIA_FILE_FORMAT_MP3)
			 ->set('download', false)
			 ->set('minPronunciations', null)
			 ->set('pagesize', null)
			 ->set('page', null)
			 ->set('search', null);

		return $this;
	}

	protected function _makeUrl()
	{
		$url = $this->apiForvoUrl
			. '/key/' . $this->apiKey
			. '/format/' . $this->format
			. '/action/' . $this->action
			. (!empty($this->word) ? '/word/' . $this->word : '')
			. (!empty($this->language) ? '/language/' . $this->language : '')
			. (!empty($this->country) ? '/country/' . $this->country : '')
			. (!empty($this->username) ? '/username/' . $this->username : '')
			. (!empty($this->sex) ? '/sex/' . $this->sex : '')
			. (!empty($this->minimalRate) ? '/rate/' . $this->minimalRate : '')
			. (!empty($this->order) ? '/order/' . $this->order : '')
			. (!empty($this->groupInLanguages) && $this->groupInLanguages === true ? '/group-in-language/' . $this->groupInLanguages : '')
			. (!empty($this->limit) ? '/limit/' . $this->limit : '')
			. (!empty($this->minPronunciations) ? '/min-pronunciations/' . $this->minPronunciations : '')
			. (!empty($this->pagesize) ? '/pagesize/' . $this->pagesize : '')
			. (!empty($this->page) ? '/page/' . $this->page : '')
			. (!empty($this->search) ? '/search/' . $this->search : '')
		;

		return $url;
	}

	protected function _getMediaFiles( $content )
	{
		if (empty($content))
			throw new Exception('Sorry, but content is empty!');

		return ResourceFabric::getFiles($content, $this->format, $this->download, $this->mediaFileFormat );
	}
} 