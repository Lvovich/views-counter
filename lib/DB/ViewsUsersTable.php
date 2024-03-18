<?php
namespace Test\Views\DB;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;

/**
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> COOKIE_ID string(32) mandatory
 * </ul>
 **/
abstract class ViewsUsersTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'test_views_users';
    }

    public static function getObjectClass(): string
    {
        return ViewsUser::class;
    }

    /**
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary'      => true,
                'autocomplete' => true,
                'title'        => ''
            ]),

            new StringField('COOKIE_ID', [
                'required'   => true,
                'size'       => 32,
                'validation' => [__CLASS__, 'validateCookieId'],
                'title'      => ''
            ]),
        ];
    }

    /**
     * @throws ArgumentTypeException
     */
    public static function validateCookieId(): array
    {
        return [new LengthValidator(32, 32)];
    }
}
