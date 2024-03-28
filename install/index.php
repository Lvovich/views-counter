<?php
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

class test_views extends CModule
{
    private const JUST_INSTALLED = 'dc3149f35c18ba7b4bfbf148a53882c1';

    public $MODULE_ID = 'test.views';

    public function __construct()
    {
        @include __DIR__ . '/version.php';

        Loc::loadLanguageFile(__DIR__ . '/../include.php', 'ru');

        /** @var array $arModuleVersion */

        $this->MODULE_VERSION      = $arModuleVersion['VERSION'] ?? '';
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'] ?? '';

        $this->MODULE_NAME = GetMessage('TEST_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('TEST_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = 'Test';
        $this->PARTNER_URI = 'https://test.test/';

        if ($_REQUEST['mod']===$this->MODULE_ID && $_REQUEST['result']==='OK' && $_SESSION[self::JUST_INSTALLED]) {
            $this->checkComposerInstalled();
            unset($_SESSION[self::JUST_INSTALLED]);
        }
    }

    public function DoInstall(): bool
    {
        if (ModuleManager::isModuleInstalled($this->MODULE_ID)) {
            return true;
        }

        ModuleManager::registerModule($this->MODULE_ID);

        return $_SESSION[self::JUST_INSTALLED] = $this->InstallDB();
    }

    public function DoUninstall(): bool
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        return true;
    }

    public function InstallDB(): bool
    {
        $this->UnInstallDB();

        if ($errors = $GLOBALS['DB']->RunSQLBatch(__DIR__ . '/db/install.sql')) {
            $GLOBALS['APPLICATION']->ThrowException(GetMessage('TEST_FAILED_TO_FILL_DB') . var_export($errors));
        }

        return !$errors;
    }

    public function UnInstallDB()
    {
        $GLOBALS['DB']->RunSQLBatch(__DIR__ . '/db/uninstall.sql');
    }

    public function UnInstallFiles()
    {
        Directory::deleteDirectory(realpath(__DIR__ . '/..') . '/vendor');
    }

    private function checkComposerInstalled()
    {
        if (!(new File(__DIR__ . '/../vendor/autoload.php'))->isExists()) {
            $selector = '#adm-workarea .adm-info-message-green .adm-info-message-title';

            echo "<script>let mess=document.createTextNode('".GetMessage('TEST_NO_COMPOSER_INSTALLED')."');".
                    "setTimeout(function() {".
                        "let messTitle = document.querySelector('$selector');".
                        "messTitle.parentNode.insertBefore(mess, messTitle.nextSibling);".
                    "}, 100);".
                '</script>';
        }
    }
}
