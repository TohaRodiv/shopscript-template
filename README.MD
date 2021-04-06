# Шпоргалка для натяжки шаблона

## Общие сведения

**Основной шаблон:** astrade.dv

**Тема шаблона:** astrade

------

*Планы на будущее: добавить папку components в шаблон с готовыми компонетами. Например, один из компонентов - компонент yandex-карты, где ручками, пока что, указываем только координаты местонахождения компании, а остальная информация (адрес, название, и т.д.) подтягивается из админки. P.S. Уже реализованно на https://yunaeli.ru/contacts/*

------

*Дата последнего обновления: 31.03.2021*



## Контактная информация

**Название магазина:**

```html
{* Название магазина *}
{$nameShop = $theme_settings.sitename}
```

```
{$nameShop}
```

**Полное название (с описанием):**

```html
{* Полное описание *}
{$nameShopFull = $theme_settings.fullDescription}
```

```html
{$nameShopFull}
```

**Описание:**

```html
{$fullDescription = $theme_settings.description}
```

```
{$fullDescription}
```

**Теглайн:**

```
{$tagline = $theme_settings.tagline}
```

```
{$tagline}
```

**Полный путь логотипа:**

```
{$logo = $wa_theme_url|cat:$theme_settings.logo}
```

```
{$logo}
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

<ul id="top-menu" class="top-menu" data-depth="0">
						{foreach $categories as $cat}
						<li class="top_level_category dropdown">
						   <a class="dropdown-item" href="{$cat.url}">{$cat.name}
						   {if $cat.childs}
						   <i class="fa fa-angle-right add hidden-xs hidden-sm"></i>
						   {/if}
						   </a>
						   {if $cat.childs}
						   <span class="pull-xs-right hidden-md hidden-lg">
						   <span data-target="#top_sub_menu_{$cat.id}" data-toggle="collapse" class="navbar-toggler collapse-icons">
						   <i class="fa fa-angle-down add"></i>
						   <i class="fa fa-angle-up remove"></i>
						   </span>
						   </span>
						   {/if}
						   {if $cat.childs}
						   <div id="top_sub_menu_{$cat.id}" class="dropdown-menu popover sub-menu collapse">
							  {foreach $cat.childs as $child}
							  <ul class="list-unstyled childs_1 category_dropdownmenu  multiple-dropdown-menu " data-depth="1">
								 <li class="category dropdown sub-category">
									<a class="dropdown-item dropdown-submenu" href="{$child.url}">{$child.name}</a>
									{if $child.childs}
									<span class="pull-xs-right hidden-md hidden-lg">
									<span data-target="#top_sub_menu_{$child.id}" data-toggle="collapse" class="navbar-toggler collapse-icons">
									<i class="fa fa-angle-down add"></i>
									<i class="fa fa-angle-up remove"></i>
									</span>
									</span>
									{/if}
									{if $child.childs}
									<div id="top_sub_menu_{$child.id}" class="dropdown-inner collapse">
									   <ul class="list-unstyled childs_2 top-menu" data-depth="2">
										  {foreach $child.childs as $child2}
										  <li class="category">
											 <a class="dropdown-item" href="{$child2.url}">{$child2.name}</a>
										  </li>
										  {/foreach}
									   </ul>
									</div>
									{/if}
								 </li>
							  </ul>
							  {/foreach}
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
