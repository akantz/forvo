<?php

require_once( dirname(__FILE__) . '/../ForvoApiLoader.php' );

// first, register component's autoloader. You need that, if you use component with other architecture,
// for example with some framework, which use his own autoload system.
ForvoApiLoader::registerAutoload();

// Just get pronounce for "cat" word, with default params.

// get Api instance
$forvoApi = ForvoApiLoader::getApi();
// Fill api key (get one on forvo.com
$forvoApi->apiKey = '<your forvo.com api key>';
// get media files array (audio in selected format, mp3 is default)
$files = $forvoApi->getPronounces('cat', 1);

// Or even much more simple...

$files = ForvoApiLoader::getApi()->setApiKey('<your api key>')->getPronounces('cat');

// if you need set some params, use param($name, $value) method:

$files = ForvoApiLoader::getApi()->setApiKey('key')
	->param('sex', \ForvoApi::SEX_MALE)
	->param('language', 'en')
	->getPronounces('cat');

// remove component's autoloader from stack
ForvoApiLoader::unregisterAutoload();