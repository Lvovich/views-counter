<?php
namespace Test\Views\DB;

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
use Exception;

class ViewsUser extends EO_ViewsUsers
{
    private const COOKIE_NAME = 'VIEWS_USER';

    private const COOKIE_EXP = '+1 year';

    private const SALT = '9t%+mVdkueX(CI&m+y-G8|+#.dnT;d0K';

    private static ?ViewsUser $instance = null;

    public static function getInstance(): ViewsUser|null
    {
        if (!self::$instance) {
            $request = Application::getInstance()->getContext()->getRequest();

            try {
                $filter = ['COOKIE_ID'=>$request->getCookie(self::COOKIE_NAME)];
                self::$instance = ViewsUsersTable::getList(['filter'=>$filter])->fetchObject() ?? self::addNew();
            }
            catch (Exception) {
            }
        }

        return self::$instance;
    }

    private static function addNew(): ViewsUser|null
    {
        $attempts = 3;

        do { // in a loop, since the identifier $newCookieId can repeat an existing one
            $newCookieId = md5(self::SALT . rand());

            $newUser = (new self())->setCookieId($newCookieId);

            try {
                if ($newUser->save()->isSuccess()) {
                    Application::getInstance()->getContext()->getResponse()->addCookie(
                        new Cookie(self::COOKIE_NAME, $newCookieId, strtotime(self::COOKIE_EXP))
                    );

                    return $newUser;
                }
            }
            catch (Exception) {
            }
        } while (--$attempts);

        return null;
    }
}
