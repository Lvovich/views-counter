<?php
namespace Test\Views\DB;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
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
            $context = Application::getInstance()->getContext();
            $cookieId = $context->getRequest()->getCookie(self::COOKIE_NAME);

            try {
                self::$instance = ViewsUsersTable::getList(['filter'=>['COOKIE_ID'=>$cookieId]])->fetchObject();

                if (!self::$instance) { // attempts to add a new user with unique cookieId
                    $attempts = 3;

                    do { // in a loop, since the identifier $newCookieId can repeat an existing one
                        $newCookieId = md5(self::SALT . rand());

                        self::setCookie($context, $newCookieId);
                        self::$instance = (new self())->setCookieId($newCookieId);

                    } while (!self::$instance->save()->isSuccess() && --$attempts>0);
                }
            }
            catch (Exception) {
            }
        }

        return self::$instance;
    }

    private static function setCookie(Context $context, string $newCookieId)
    {
        $cookie = (new Cookie(self::COOKIE_NAME, $newCookieId, strtotime(self::COOKIE_EXP)))
            ->setPath('/')
            ->setHttpOnly(false)
            ->setDomain($context->getRequest()->getHttpHost());

        $context->getResponse()->addCookie($cookie);
    }
}
