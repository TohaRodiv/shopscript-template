# Шпоргалка для натяжки шаблона

## Общие сведения

**Основной шаблон:** astrade.dv

**Тема шаблона:** astrade

------

*Планы на будущее: добавить папку components в шаблон с готовыми компонетами. Например, один из компонентов - компонент yandex-карты, где ручками, пока что, указываем только координаты местонахождения компании, а остальная информация (адрес, название, и т.д.) подтягивается из админки. P.S. Уже реализованно на http://yunaeli.ru.*

------

*Дата последнего обновления: 31.03.2021*



## Контактная информация

**Название магазина:**

```html
{$nameShop = $theme_settings.nameShop}
```

```
{$nameShop}
```

**Полное название (с описанием):**

```html
{$nameShopFull = $theme_settings.nameShopFull}
```

```html
{$nameShopFull}
```

**Описание:**

```html
{$descriptionShop = $theme_settings.descriptionShop}
```

```
{$descriptionShop}
```



**Массив адресов магазина:**

```html
{$tenAddr = "|"|explode:$theme_settings.address}
```

```
{$tenAddr[0]}
```



**Массив режимов работы:**

```html
{$tenTime = "|"|explode:$theme_settings.times}
```

```
{$tenTime[0]}
```



**Массив чистых номеров (для атрибута ссылки href):**

```html
{$clearPhone = ","|explode:$theme_settings.phones|regex_replace:"/[^0-9,]/":""}
```

```html
{$clearPhone[0]}
```

**Массив номеров по умолчанию (для красивого отображения):**

```html
{$tenPhone = ","|explode:$theme_settings.phones}
```

```
{$tenPhone[0]}
```

```html
<a href="tel:{$clearPhone[0]}">{$tenPhone[0]}</a>
```



**Массив E-mail адресов:**

```html
{$tenMail = ","|explode:$theme_settings.email}
```

```html
{$tenMail[0]}
```

```html
<a href="#" data-lredmod-id="order">{$tenMail[0]}</a>
```



## Для шапки сайт

**Кол-во товаров в сравнении:**

```html
{$compare_count = explode(",", $smarty.cookies.shop_compare)|count - 1}
<!--
Почему внутри .compareCount обязательно тег span!?
Поправить эту хуйню в product.js! Это, блять, неудобно!
Когда будет готово, удалить этот комент!
-->
<span class="compareCount">
    <span>{$compare_count}</span>
</span>
```



**Сумма и кол-во товаров в корзине:**

```html
<span class="cart-count">{$cart_count}</span>
<span class="hidden-md hidden-sm hidden-xs cart-total">
    {wa_currency_html($cart_total, $currency)}
</span>
```



**Форма поиска:**

```html
<form action="/search/" method="GET">
    <input type="text" name="query" value="" placeholder="Поиск">
    <button type="submit">Поиск</button>
</form>
```



**Главное меню:**

```html
{* Главное меню *}
{$main_menu = $wa->menu->get(1)}

<ul>
    {foreach $main_menu as $item}
        <li>
            <a href="{$item.url}">{$item.name}</a>
        </li>
    {/foreach}
</ul>
```



**Меню Каталога:**

```html
{* Категория Каталог *}
{$categories = $wa->shop->categories(32, null, true)}

    <ul class="category-list nav navbar-nav">
        {foreach $categories as $cat}
            <li class="menu-item{if $cat.childs} dropdown{/if}">
                <a href="{$cat.url}"{if $cat.childs} class="dropdown-toggle"{/if}>{$cat.name}</a>
                {if $cat.childs}
                    <div class="dropdown-menu single-dropdown">
                        <div class="dropdown-inner">
                            <ul class="list-unstyled megamenu-dropdown column3">
                                {foreach $cat.childs as $child1}
                                    <li class="menu-item{if $childs1.childs} dropdown{/if}">
                                        <a href="{$child1.url}"{if $child1.childs} class="dropdown-toggle"{/if}>{$child1.name}</a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/if}
            </li>
        {/foreach}
    </ul>
```



**Каталог одноуровневый:**

```html
{* Категория Каталог *}
{$categories = $wa->shop->categories(32, null, true)}

<ul>
    {foreach $categories as $cat}
        <li>
            <a href="{$cat.url}">{$cat.name}</a>
        </li>
    {/foreach}
</ul>
```



## Карточка товара

**Обвертка для любой карточки товара:**

```html
<div
    class="{if $className} {$className}{/if}"
    data-card="{$p.id}"
    data-card-name="{$p.name}"
    data-card-picture="{shopImage::getUrl(['product_id' => $p.id, 'id' => $p.image_id, 'ext' => $p.ext], '270x270')}"
    data-card-price="{wa_currency_html($p.price, $p.currency)}">
    
</div>
```



**Форма добавления в корзину:**

```html
<form class="addtocart" method="post" action="{$wa->getUrl('/frontendCart/add')}">
	<input type="hidden" name="quantity" value="1">
	<input type="hidden" name="product_id" value="{$product.id}" />
	<button type="submit">
        В корзину
    </button>
</form>
```



**Кнопка добавление в сравнение:**

```html
<button
	class="compareBtn {if $wa->shop->inComparison($p.id)} compareRemove{else} compareAdd{/if}"
	title="{if $wa->shop->inComparison($p.id)}Убрать из сравнения{else}В сравнение{/if}"
	data-product="{$product.id}">
	{if $wa->shop->inComparison($p.id)}Убрать из сравнения{else}В сравнение{/if}
</button>
```



**Кнопка быстрого просмотра:**

```html
<button class="linkQuickView" data-href="{$p.frontend_url|cat: '?popup=1'}">
    Быстрый просмотр
</button>
```
