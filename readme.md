**Функционал модуля**
- Регистриует просмотры статей/товаров по уникальному куки пользователя. Сохраняет статистику в виде статья|пользователь|метка времени.
- Отличие от штатного функционала просмотров в том, что штатный работает на сессии. После закрытия вкладки/браузера просмотр пользователя
будет учтен заново (увеличит счетчик). Так же, штатный не хранит статистику по каждому просмотру.
- Этот модуль каждому юзеру ставит уникальную куку, и проверяет просмотр по этой куке. Т.е. даже после закрытия/открытия браузера повторный
просмотр этим пользователем не увеличит счетчик.

**Минимальные требования**
- Битрикс>=Старт, версия>=14.0.0, php>=8.1

**Порядок установки**
- Добавить файлы из репозитория в local/modules/test.views . Дальше обычная установка/удаление средствами битрикса.
- После установки выполнить ```composer install``` находясь в папке модуля.

**Использование**
- на детальной странице элемента, в файле component_epilog.php (для предотвращения кеширования):
```php
use Bitrix\Main\Loader;
use Test\Views;

/** @var array $arResult */

$viewsCount = 100; // A dummy default value, in case the module is not installed or an error occurs

try {
    if (Loader::includeModule('test.views')) {
        $viewsArticle = new Views\Article($arResult['ID']);

        if (!($res = $viewsArticle->addView())->isSuccess()) {
            // $res->getErrors()
        }

        $viewsCount = $viewsArticle->getViewsCount();
    }
}
catch (Exception) {
}
?>
<script>
    BX.ready(function(){document.querySelector('.views_counter_value').innerHTML='<?= $viewsCount ?>'});
</script>
```
- на странице списка элементов, в файле component_epilog.php (для предотвращения кеширования):
```php
use Bitrix\Main\Loader;
use Test\Views;

/** @var array $arResult */

$viewsList = [];

try {
    if (Loader::includeModule('test.views')) {
        $viewsList = Views\Article::getViewsCountList($arResult['ITEMS_IDS_LIST'] ?? []);
    }
}
catch (Exception) {
}
?>
<script>
    BX.ready(function() {
        let views = <?= CUtil::PhpToJSObject($viewsList) ?>,
            items = document.querySelectorAll('.views_counter_value') || [],
            dummyCount = 100;

        [].forEach.call(items, function(item){item.innerHTML = views[item.dataset.id] || dummyCount});
    });
</script>

```
