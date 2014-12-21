<?php

namespace forvoapi\classes;

class ResourceFabric
{
	protected static $download;
	protected static $downloadFormat;

	public static function getLanguagesArray($data, $format)
	{
		switch ($format)
		{
			case ForvoApi::FORMAT_JSON:
				try
				{
				    $languages = [];
					$json = json_decode($data, true);

					if (isset($json['items']) && count($json['items']) > 0)
					{
						foreach ($json['items'] as $item)
						{
							$languages[ $item['code'] ] = $item['en'];
						}
					}

					unset($json);
					return $languages;
				}
				catch (\Exception $e)
				{
					echo $e->getMessage();
				}
				break;

			case ForvoApi::FORMAT_XML:
				try
				{
					$languages = [];
					$simpleXmlObject = new \SimpleXMLElement($data);

					if (count($simpleXmlObject->item) > 0)
					{
						foreach ( $simpleXmlObject->item as $item )
						{
							$languages[ (string) $item->code ] = (string) $item->en;
						}
					}

					unset($simpleXmlObject);
					return $languages;
				}
				catch (\Exception $e)
				{
				    echo $e->getMessage();
				}
				break;
		}

		return [];
	}

	public static function getWordSearch($data, $format)
	{
		switch ($format)
		{
			case ForvoApi::FORMAT_XML:
				$return = [];
				$simpleXmlObject = new \SimpleXMLElement($data);

				if (count($simpleXmlObject->item) > 0)
				{
					foreach ( $simpleXmlObject->item as $item )
					{
						if (isset($item->standard_pronunciation))
						{
							$item->standard_pronunciation->word = $item->word;
							$return[ (int) $item->id ] = self::_makeMediaFile( $item->standard_pronunciation, 'xml' );
						}
					}
				}
				return $return;

				break;

			case ForvoApi::FORMAT_JSON:
				$return = [];
				$json = json_decode( $data, true );

				if (count($json['items']) > 0)
				{
					foreach ( $json['items'] as $item )
					{
						if (isset($item['standard_pronunciation']))
						{
							$item['standard_pronunciation']['word'] = $item['word'];
							$return[ (int) $item['id'] ] = self::_makeMediaFile( $item['standard_pronunciation'], 'json' );
						}
					}
				}

				return $return;
				break;

			case ForvoApi::FORMAT_JS_TAG:
				return $data;
				break;
		}
	}

	public static function getFiles( $data, $format, $download, $downloadFormat )
	{
		self::$download = $download;
		self::$downloadFormat = $downloadFormat;

		switch ($format)
		{
			case ForvoApi::FORMAT_XML:
				return self::_getFiles_FormatXml($data);
				break;

			case ForvoApi::FORMAT_JSON:
				return self::_getFiles_FormatJson($data);
				break;

			case ForvoApi::FORMAT_JS_TAG:
				return $data;
				break;
		}
	}

	public static function clearCache()
	{

	}

	protected static function _getFiles_FormatXml( $data )
	{
		try
		{
		    $files = [];
			$simpleXmlObject = new \SimpleXMLElement($data);

			if (count($simpleXmlObject->item) > 0)
			{
				foreach ( $simpleXmlObject->item as $item )
				{
					$mediaFile = self::_makeMediaFile( $item, 'xml' );
					$files[] = $mediaFile;
				}
			}

			unset($simpleXmlObject);
			return $files;
		}
		catch (\Exception $e)
		{
			echo $e->getMessage();
			return [];
		}
	}

	protected static function _getFiles_FormatJson( $data )
	{
		try
		{
			$files = [];
			$json = json_decode( $data, true );

			if (isset($json['items']) && $json['items'] > 0)
			{
				foreach ($json['items'] as $item)
				{
					$mediaFile = self::_makeMediaFile( $item, 'json' );
					$files[] = $mediaFile;
				}
			}

			unset($json);
			return $files;
		}
		catch (\Exception $e)
		{
			echo $e->getMessage();
			return [];
		}
	}

	/**
	 * Get MediaFile object from xml or json item source
	 *
	 * @param $item
	 * @param string $format
	 * @return MediaFile
	 */
	protected static function _makeMediaFile( $item, $format = 'json' )
	{
		$format = strtolower($format);
		$mediaFile = new MediaFile();
		$mediaFile->forvoId     = ($format == 'json') ? $item['id']         : (int)$item->id;
		$mediaFile->word        = ($format == 'json') ? $item['word']       : (string)$item->word;
		$mediaFile->addtime     = ($format == 'json') ? $item['addtime']    : (string)$item->addtime;
		$mediaFile->username    = ($format == 'json') ? $item['username']   : (string)$item->username;
		$mediaFile->sex         = ($format == 'json') ? $item['sex']        : (string)$item->sex;
		$mediaFile->country     = ($format == 'json') ? $item['country']    : (string)$item->country;
		$mediaFile->code        = ($format == 'json') ? $item['code']       : (string)$item->code;
		$mediaFile->langname    = ($format == 'json') ? $item['langname']   : (string)$item->langname;
		$mediaFile->pathmp3     = ($format == 'json') ? $item['pathmp3']    : (string)$item->pathmp3;
		$mediaFile->pathogg     = ($format == 'json') ? $item['pathogg']    : (string)$item->pathogg;
		$mediaFile->rate        = ($format == 'json') ? $item['rate']       : (int)$item->rate;

		if (self::$download)
			$mediaFile->downloadFile( self::$downloadFormat );

		return $mediaFile;
	}
}