<?php

/**
 * @property string $fileType
 * @property string $filePathMp3
 * @property string $filePathOgg
 * @property boolean $cached
 * @property string $cacheDir
 * @property string $fileUID
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

		$dir = dirname(__FILE__) . '/../assets/tmp/' . $this->fileType . '/' . $word[0] . '/' . $word[1] . '/' . $word[2] . '/' . $this->word . '/';
		$fileName = $this->rate . '_' . $this->sex . '_' . $this->word . '_' . $this->forvoId . '_' . $this->code . '_' . $this->langname . '_' . $this->country .  '.' . $this->fileType ;
		$fileName = str_replace(' ', '_', $fileName);
		$savePath = $dir . $fileName;

		if (!file_exists($dir))
			mkdir($dir, 0777, true);

		switch ($type)
		{
			case self::MEDIA_FILE_FORMAT_MP3:
				$this->_curlDownloadFile( $this->pathmp3, $savePath );
				break;

			case self::MEDIA_FILE_FORMAT_OGG:
				$this->_curlDownloadFile( $this->pathogg, $savePath );
				break;
		}
	}

	protected function _curlDownloadFile( $url, $to )
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec( $curl );

		if ($content)
		{
			file_put_contents($to, $content);
		}

		curl_close($curl);
	}
}