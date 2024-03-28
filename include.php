<?php if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)exit();

use Bitrix\Main\IO\File;
use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__, 'ru');

$autoloadPath = __DIR__ . '/vendor/autoload.php';

if ((new File($autoloadPath))->isExists()) {
    require_once $autoloadPath;
}
