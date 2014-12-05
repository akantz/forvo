<?php


class MediaFilesFabric
{
	public static function getFiles( $data, $format )
	{
		switch ($format)
		{
			case \ForvoApi::FORMAT_XML:

				break;

			case \ForvoApi::FORMAT_JSON:
				return self::_getFiles_FormatJson($data);
				break;

			case \ForvoApi::FORMAT_JS_TAG:

				break;
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
					$mediaFile->forvoId = $item['id'];
					$mediaFile->word = $item['word'];
					$mediaFile->addtime = $item['addtime'];
					$mediaFile->username = $item['username'];
					$mediaFile->sex = $item['sex'];
					$mediaFile->country = $item['country'];
					$mediaFile->code = $item['code'];
					$mediaFile->langname = $item['langname'];
					$mediaFile->pathmp3 = $item['pathmp3'];
					$mediaFile->pathogg = $item['pathogg'];
					$mediaFile->rate = $item['rate'];

					$mediaFile->downloadFile( MediaFile::MEDIA_FILE_FORMAT_MP3 );

					$files[] = $mediaFile;
				}
			}

			return $files;
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			return [];
		}
	}
}