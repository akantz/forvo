<?php

require_once( dirname(__FILE__) . '/../ForvoApiLoader.php' );

//  First, you should to register component's autoloader. You need to do that, if you use component with other
//  architectures, for example with some framework, which use his own autoload system.
ForvoApiLoader::registerAutoload();

// Just get pronounce for "cat" word, with default params.

// get Api instance
$forvoApi = ForvoApiLoader::getApi();
// Fill api key (get one on forvo.com
$forvoApi->apiKey = '<your forvo.com api key>';
// get media files array (audio in selected format, mp3 is default)
$files = $forvoApi->getPronounce('cat', 1);

// Or even more simple...

$files = ForvoApiLoader::getApi()->setApiKey('<your api key>')->getPronounce('cat');

// if you need set some params, use param($name, $value) method:

$files = ForvoApiLoader::getApi()->setApiKey('<your api key>')
	->set('sex', \ForvoApi::SEX_FEMALE)
	->set('language', 'en')
	->set('limit', 5)
	->set('format', \ForvoApi::FORMAT_XML)
	->getPronounce('radioactivity');

var_dump($files);

// remove component's autoloader from stack
ForvoApiLoader::unregisterAutoload();