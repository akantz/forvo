<?php

/**
 * When you make request to forvo.com API, you receive JSON or XML data containing many items.
 * This class allows you use Object-oriented representation of data. Always in the same format.
 * You just should set "format" in ForvoApi properties, which format you want, and get array of
 * MediaFile objects.
 *
 * Please, remember, if you get data dynamically, the file link (i.e. pathmp3 and pathogg) is valid
 * only for 2 hours. Cache is not allowed by forvo's terms of use. It's implemented, but only for debugging!
 *
 * @property string $fileType
 * @property string $filePathMp3
 * @property string $filePathOgg
 * @property boolean $cached
 * @property string $cacheDir
 * @property string $fileUID
 * @property string $assetsPath
 *
 *
 * @property string $forvoId
 * @property string $word
 * @property string $addtime
 * @property string $username
 * @property string $sex
 * @property string $country
 * @property string $code
 * @property string $langname
 * @property string $pathmp3
 * @property string $pathogg
 * @property string $rate
 *
 * Class MediaFile
 */
class MediaFile extends BaseApiComponent
{
	const MEDIA_FILE_FORMAT_MP3 = 'mp3';
	const MEDIA_FILE_FORMAT_OGG = 'ogg';

	protected $_properties = [
		'fileType' => null,
		'filePathMp3' => '',
		'filePathOgg' => '',
		'cached' => false,
		'cacheDir' => null,
		'assetsPath' => '',

		'forvoId'           => '',
		'word'              => '',
		'addtime'           => '',
		'username'          => '',
		'sex'               => '',
		'country'           => '',
		'code'              => '',
		'langname'          => '',
		'pathmp3'           => '',
		'pathogg'           => '',
		'rate'              => '',
	];

	public function downloadFile( $type )
	{
		$this->fileType = $type;
		$word = $this->word;

		if (strlen($word) < 3)
		{
			$word = str_pad($word, 3, '0');
		}

		$fileName = $this->rate . '_' . $this->sex . '_' . $this->word . '_' . $this->forvoId . '_' . $this->code . '_' . $this->langname . '_' . $this->country .  '.' . $this->fileType ;
		$fileName = str_replace(' ', '_', $fileName);

		$this->assetsPath = '/assets/tmp/' . $word[0] . '/' . $word[1] . '/' . $word[2] . '/' . $this->word . '/' . $this->fileType . '/';
		$dir = dirname(__FILE__) . '/..' . $this->assetsPath;

		$savePath = $dir . $fileName;

		if (!file_exists($dir))
			mkdir($dir, 0777, true);

		$this->assetsPath .= $fileName;

		switch ($type)
		{
			case self::MEDIA_FILE_FORMAT_MP3:
				$this->_curlDownloadFile( $this->pathmp3, $savePath );
				$this->filePathMp3 = $savePath;
				break;

			case self::MEDIA_FILE_FORMAT_OGG:
				$this->_curlDownloadFile( $this->pathogg, $savePath );
				$this->filePathOgg = $savePath;
				break;
		}
	}

	protected function _curlDownloadFile( $url, $to )
	{
		$content = $this->curlGetContent($url, 10);

		if ($content)
		{
			file_put_contents($to, $content);
			$this->cached = true;
		}
	}
}