<?php

/**
 * @property string $fileType
 * @property integer $fileSize
 * @property string $filePath
 * @property boolean $cached
 * @property string $cacheDir
 * @property string $fileUID
 *
 * Class MediaFile
 */
class MediaFile extends BaseApiComponent
{
	const MEDIA_FILE_FORMAT_MP3 = 'mp3';
	const MEDIA_FILE_FORMAT_OGG = 'ogg';

	protected $_properties = [
		'fileType' => null,
		'fileSize' => 0,
		'filePath' => '',
		'cached' => false,
		'cacheDir' => null,
		'fileUID'  => null,
	];

	public function getFile( $data, $format )
	{
		switch ($format)
		{
			case \ForvoApi::FORMAT_XML:
				return $this->_getFile_xml($data);
				break;

			case \ForvoApi::FORMAT_JSON:
				return $this->_getFile_json($data);
				break;

			case \ForvoApi::FORMAT_JS_TAG:
				return $this->_getFile_jsTag($data);
				break;
		}
	}

	public function downloadFile( $url )
	{

	}

	protected function _getFile_xml( $content )
	{

	}

	protected function _getFile_json( $content )
	{

	}

	protected function _getFile_jsTag( $content )
	{

	}
}