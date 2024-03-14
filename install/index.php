<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

class test_views extends CModule
{
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
    }

    public function DoInstall(): bool
    {
        if (!IsModuleInstalled($this->MODULE_ID)) {
            ModuleManager::registerModule($this->MODULE_ID);

            return $this->InstallDB();
        }

        return true;
    }

    public function DoUninstall(): bool
    {
        $this->UnInstallDB();

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
}
