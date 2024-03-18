<?php
namespace Test\Views\DB;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;

/**
 * Fields:
 * <ul>
 * <li> USER_ID int mandatory
 * <li> ELEMENT_ID int mandatory
 * <li> TIMESTAMP datetime optional default current datetime
 * </ul>
 **/
class ArticleViewsTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'test_articles_views';
    }

    /**
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            new IntegerField('USER_ID', [
                'primary' => true,
                'title' => ''
            ]),

            new IntegerField('ELEMENT_ID', [
                'primary' => true,
                'title' => ''
            ]),

            new DatetimeField('TIMESTAMP', [
                'default' => function(){return new DateTime();},
                'title' => ''
            ]),
        ];
    }
}
