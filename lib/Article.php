<?php
namespace Test\Views;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Error;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\Result;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\SystemException;
use Exception;
use Test\Views\DB\ArticleView;
use Test\Views\DB\ArticleViewsTable;
use Test\Views\DB\ViewsUser;

class Article
{
    private int $articleId;

    private int $viewsCount;

    private ?ViewsUser $viewsUser = null;

    /**
     * @throws ArgumentException
     */
    public function __construct(int $elemId)
    {
        if (($this->articleId = $elemId) <= 0) {
            throw new ArgumentException(GetMessage('TEST_ARTICLE_NOT_FOUND'));
        }

        if (!($this->viewsUser = ViewsUser::getInstance())) {
            throw new ArgumentException(GetMessage('TEST_VIEWS_USER_NOT_FOUND'));
        }

        $this->viewsCount = $this->countViews();
    }

    public function addView(): AddResult
    {
        $res = new AddResult();

        try {
            $res = (new ArticleView())->setUserId($this->viewsUser->getId())->setElementId($this->articleId)->save();
            $this->viewsCount++;
        }
        catch (Exception $e) {
            $res->addError(new Error($e->getMessage()));
        }

        return $res;
    }

    public function hideView(): Result
    {
        $res = new Result();

        try {
            $filter = ['USER_ID'=>$this->viewsUser->getId(), 'ELEMENT_ID'=>$this->articleId];

            if ($view = ArticleViewsTable::getList(['filter'=>$filter])->fetchObject()) {
                $res = $view->delete();
                $this->viewsCount--;
            }
        }
        catch (Exception $e) {
            $res->addError(new Error($e->getMessage()));
        }

        return $res;
    }

    public function clearViews(): Result
    {
        $res = new Result();

        try {
            $views = ArticleViewsTable::getList(['filter'=>['ELEMENT_ID'=>$this->articleId]])->fetchCollection();

            foreach ($views as $view) {
                $view->delete();
            }

            $res = $views->save();

            $this->viewsCount = 0;
        }
        catch (Exception $e) {
            $res->addError(new Error($e->getMessage()));
        }

        return $res;
    }

    public function getViewsCount(): int
    {
        return $this->viewsCount;
    }

    public static function getViewsCountList(array $articlesIds): array
    {
        try {
            $dbRes = ArticleViewsTable::getList([
                'filter' => ['ELEMENT_ID'=>($articlesIds ?: [0])],
                'select' => ['ELEMENT_ID', new ExpressionField('CNT', 'COUNT(%s)', ['USER_ID'])],
            ])->fetchAll();

            return array_combine(array_column($dbRes, 'ELEMENT_ID'), array_column($dbRes, 'CNT'));
        }
        catch (SystemException) {
            return [];
        }
    }

    private function countViews(): int
    {
        try {
            $res = ArticleViewsTable::getList([
                'filter' => ['ELEMENT_ID'=>$this->articleId],
                'select' => [new ExpressionField('CNT', 'COUNT(%s)', ['USER_ID'])],
            ])->fetch();
        }
        catch (SystemException) {
            $res = [];
        }

        return intval($res['CNT']);
    }
}
