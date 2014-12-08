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
					$mediaFile = new MediaFile();
					$mediaFile->forvoId     = (int) $item->id ;
					$mediaFile->word        = (string) $item->word;
					$mediaFile->addtime     = (string) $item->addtime;
					$mediaFile->username    = (string) $item->username;
					$mediaFile->sex         = (string) $item->sex;
					$mediaFile->country     = (string) $item->country;
					$mediaFile->code        = (string) $item->code;
					$mediaFile->langname    = (string) $item->langname;
					$mediaFile->pathmp3     = (string) $item->pathmp3;
					$mediaFile->pathogg     = (string) $item->pathogg;
					$mediaFile->rate        = (string) $item->rate;

					if (self::$download)
						$mediaFile->downloadFile( self::$downloadFormat );

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
					$mediaFile = new MediaFile();
					$mediaFile->forvoId     = $item['id'];
					$mediaFile->word        = $item['word'];
					$mediaFile->addtime     = $item['addtime'];
					$mediaFile->username    = $item['username'];
					$mediaFile->sex         = $item['sex'];
					$mediaFile->country     = $item['country'];
					$mediaFile->code        = $item['code'];
					$mediaFile->langname    = $item['langname'];
					$mediaFile->pathmp3     = $item['pathmp3'];
					$mediaFile->pathogg     = $item['pathogg'];
					$mediaFile->rate        = $item['rate'];

					if (self::$download)
						$mediaFile->downloadFile( self::$downloadFormat );

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
}