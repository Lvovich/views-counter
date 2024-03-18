<?php
namespace Test\Views\DB;

use Bitrix\Main\ORM\Objectify\EntityObject;

/**
 * @method string getCookieId()
 *
 * @method ViewsUser setCookieId(string $cookieId)
 * @method static getInstance()
 */
class EO_ViewsUsers extends EntityObject {}

/**
 * @method int getUserId()
 * @method int getElementId()
 *
 * @method ArticleView setUserId(int $viewsUserId)
 * @method ArticleView setElementId(int $elementId)
 */
class EO_ArticleViews extends EntityObject {}
