# Шпаргалка для натяжки шаблона

## Общие сведения

------

*Планы на будущее: добавить папку components в шаблон с готовыми компонетами. Например, один из компонентов - компонент yandex-карты, где ручками, пока что, указываем только координаты местонахождения компании, а остальная информация (адрес, название, и т.д.) подтягивается из админки.

------

*Дата последнего обновления: 16.04.2021*



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
						   <a class="dropdown-item" href="{$cat.url}">
							   {$cat.name}
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



## Готовые стили

**Для личного кабинета и страниц авторизации и звездочки отзывов**:

```css
.over{overflow:auto}
.order-status{color:#fff;font-size:13px;padding:4px}
.icon16.star,.icon16.star-full{background-image:url(../img/icons.png);background-position:-16px 0}
.icon16.star-half{background-image:url(../img/icons.png);background-position:-32px 0}
.icon16.star-empty{background-image:url(../img/icons.png);background-position:-48px 0}
.icon16.star-hover{background-image:url(../img/icons.png);background-position:-64px 0}
.icon16{width:16px;height:16px;display:inline-block}
.wa-field input:not([type="submit"]),.wa-field select, .wa-field-wrapper input:not([type="submit"]),.wa-field-wrapper select{display:inline-block;width:100%!important;margin-bottom:10px;border:1px solid #333;position:relative!important;height:auto!important;opacity:1!important;font-size:16px!important;padding:8px!important}
@media(min-width:768px) {
.wa-form,.wa-login-form-fields,.wa-signup-form-fields,.wa-forgotpassword-form-fields{width:50%}
```

****

**Стили для картинок в блоге**

```css
div#page figure{max-width:45%;margin:0 0 10px 15px !important}
@media(max-width:767px) {
div#page figure{max-width:100%;margin:0 0 15px!important;width:100%}
} 
```



## Иконки

Смотреть: https://www.svgrepo.com/vectors/justice/1

**Корзина**

```html
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="shopping-bag" viewBox="0 0 590 590">
		<title>shopping-bag</title>
		<path d="M258.005,218.355c2.3,2.3,5.4,3.5,8.5,3.5s6.1-1.2,8.5-3.5l73.9-73.9c4.7-4.7,4.7-12.3,0-17s-12.3-4.7-17,0l-65.4,65.5l-26.4-26.4c-4.7-4.7-12.3-4.7-17,0s-4.7,12.3,0,17L258.005,218.355z"/>
		<path d="M145.005,318.355h268.9c29.7,0,53.8-24.2,53.8-53.8v-113.3c0-6.6-5.4-12-12-12s-12,5.4-12,12v113.2c0,16.5-13.4,29.8-29.8,29.8h-268.9c-16.5,0-29.8-13.4-29.8-29.8v-66.7v-74.6v-9.7v-70.2c0-0.4,0-0.9-0.1-1.3c0-0.1,0-0.2,0-0.3c0-0.3-0.1-0.6-0.1-0.8s-0.1-0.5-0.2-0.7c0-0.1-0.1-0.3-0.1-0.4c-0.1-0.3-0.2-0.5-0.3-0.8c0-0.1-0.1-0.2-0.1-0.3c-0.1-0.2-0.2-0.5-0.3-0.7c-0.1-0.1-0.1-0.3-0.2-0.4c-0.1-0.2-0.2-0.4-0.3-0.6c-0.1-0.2-0.2-0.3-0.3-0.5c-0.1-0.1-0.2-0.3-0.3-0.4c-0.1-0.2-0.3-0.4-0.4-0.5s-0.2-0.2-0.3-0.3c-0.2-0.2-0.3-0.4-0.5-0.6c-0.1-0.1-0.2-0.2-0.3-0.2c-0.2-0.2-0.4-0.4-0.6-0.5c-0.1-0.1-0.2-0.2-0.3-0.3c-0.2-0.1-0.4-0.3-0.6-0.4c-0.2-0.2-0.5-0.3-0.7-0.5c-0.1,0-0.2-0.1-0.2-0.2c-0.4-0.2-0.8-0.4-1.2-0.6l-73.9-31c-6.1-2.6-13.1,0.3-15.7,6.4c-2.6,6,0.3,13.1,6.4,15.7l66.5,28.1v62.3v9.7v74.6v66.7v84.3c0,28.2,21.7,51.3,49.3,53.6c-5.6,8.4-8.9,18.5-8.9,29.4c0,29.3,23.9,53.2,53.2,53.2s53.2-23.9,53.2-53.2c0-10.8-3.2-20.8-8.7-29.2h127.3c-5.5,8.4-8.7,18.4-8.7,29.2c0,29.3,23.9,53.2,53.2,53.2s53.2-23.9,53.2-53.2s-23.9-53.2-53.2-53.2h-256c-16.5,0-29.8-13.4-29.8-29.8v-39.5C123.705,314.955,134.005,318.355,145.005,318.355z M213.905,431.755c0,16.1-13.1,29.2-29.2,29.2s-29.2-13.1-29.2-29.2s13.1-29.2,29.2-29.2S213.905,415.655,213.905,431.755z M430.105,431.755c0,16.1-13.1,29.2-29.2,29.2s-29.2-13.1-29.2-29.2s13.1-29.2,29.2-29.2S430.105,415.655,430.105,431.755z"/>
	</symbol>
</svg>
```

**Корзина вариант 2**

```html
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="cart-responsive" viewBox="0 0 510 510">
		<title>cart-responsive</title>
		<path d="M306.4,313.2l-24-223.6c-0.4-3.6-3.6-6.4-7.2-6.4h-44.4V69.6c0-38.4-31.2-69.6-69.6-69.6c-38.4,0-69.6,31.2-69.6,69.6
		v13.6H46c-3.6,0-6.8,2.8-7.2,6.4l-24,223.6c-0.4,2,0.4,4,1.6,5.6c1.2,1.6,3.2,2.4,5.2,2.4h278c2,0,4-0.8,5.2-2.4
		C306,317.2,306.8,315.2,306.4,313.2z M223.6,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4
		C217.2,126.4,220,123.6,223.6,123.6z M106,69.6c0-30.4,24.8-55.2,55.2-55.2c30.4,0,55.2,24.8,55.2,55.2v13.6H106V69.6z
		M98.8,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C92.4,126.4,95.2,123.6,98.8,123.6z M30,306.4
		L52.4,97.2h39.2v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2
		V97.2h110.4v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2
		H270l22.4,209.2H30z"></path>
	</symbol>
</svg>
```

**Использование**

```html
<svg class="icon" viewBox="0 0 30 30">
    <use xlink:href="#shopping-bag" x="8%" y="7%"></use>
</svg>

<svg class="icon" viewBox="0 0 40 40">
    <use xlink:href="#cart-responsive" x="13%" y="13%"></use>
</svg>
```

**Сравнение**

```html
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="compare-img" viewBox="0 0 700 700">
        <title>compare-img</title>
		<path d="M466.657,246.846L393.71,120.268c0-15.858-12.901-28.759-28.76-28.759H255.966V55.756
		c7.513-5.661,12.38-14.654,12.38-24.763C268.346,13.903,254.443,0,237.354,0c-17.089,0-30.993,13.903-30.993,30.993
		c0,10.107,4.864,19.097,12.373,24.758v35.758H109.757c-15.858,0-28.759,12.901-28.759,28.759L8.05,246.846
		c-1.161,2.011-1.161,4.489,0,6.5c0,0.001,0.001,0.001,0.001,0.002l24.253,42.009c1.161,2.011,3.307,3.25,5.629,3.25h99.126
		c2.322,0,4.468-1.239,5.629-3.25l24.255-42.01c0.58-1.006,0.871-2.128,0.87-3.25c0-1.122-0.29-2.245-0.87-3.25L98.756,128.74
		h119.978v177.775h-2.897c-3.59,0-6.5,2.91-6.5,6.5v94.735h-87.913c-3.59,0-6.5,2.91-6.5,6.5v20.479h-7.098
		c-3.59,0-6.5,2.91-6.5,6.5v26.979c0,3.59,2.91,6.5,6.5,6.5h259.057c3.59,0,6.5-2.91,6.5-6.5v-26.979c0-3.59-2.91-6.5-6.5-6.5
		h-7.099V414.25c0-3.59-2.91-6.5-6.5-6.5H265.37v-94.735c0-3.59-2.91-6.5-6.5-6.5h-2.904V128.74h119.986l-68.189,118.106
		c-0.581,1.006-0.871,2.128-0.871,3.25s0.29,2.245,0.871,3.25l24.255,42.01c1.161,2.011,3.307,3.25,5.629,3.25h99.126
		c2.322,0,4.468-1.239,5.629-3.25l24.255-42.01c0.581-1.006,0.871-2.128,0.871-3.25S467.237,247.852,466.657,246.846z M237.354,13
		c9.921,0,17.992,8.071,17.992,17.993c0,9.921-8.071,17.992-17.992,17.992c-9.921,0-17.993-8.071-17.993-17.992
		C219.361,21.071,227.432,13,237.354,13z M231.734,61.465c1.824,0.336,3.7,0.52,5.62,0.52c1.917,0,3.791-0.184,5.612-0.519v30.042
		h-11.232V61.465z M109.757,104.509h255.194c7.117,0,13.146,4.742,15.097,11.231H94.66C96.61,109.25,102.64,104.509,109.757,104.509
		z M80.472,256.597l-11.393,29.01h-8.114l11.393-29.01H80.472z M24.938,256.597h33.453l-11.393,29.01h-5.31L24.938,256.597z
		M133.307,285.607H83.045l11.393-29.01h55.618L133.307,285.607z M24.938,243.596l62.56-108.356l62.56,108.356H24.938z
		M127.924,420.75h157.348l-5.489,13.979H127.924V420.75z M293.749,434.729l5.489-13.979h5.695l-5.489,13.979H293.749z
		M114.326,447.729h7.098h36.755l-5.489,13.979h-38.364V447.729z M360.382,461.707H166.656l5.489-13.979h181.139h7.099V461.707z
		M346.784,434.729h-33.373l5.489-13.979h27.883V434.729z M222.337,407.75v-88.235h30.034v88.235H222.337z M242.966,306.515h-11.232
		V128.74h11.232V306.515z M387.21,135.24l62.559,108.356H324.651L387.21,135.24z M324.651,256.597h85.427l-11.394,29.01H341.4
		L324.651,256.597z M433.02,285.607h-20.37l11.394-29.01h25.725L433.02,285.607z"/>
	</symbol>
</svg>
```

**Использование**

```html
<svg class="icon" viewBox="0 0 40 40">
	<use xlink:href="#compare-img" x="14%" y="12%"></use>
</svg>
```

**Email**

```html
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="email-img" viewBox="0 0 700 700">
		<title>email-img</title>
		<path d="M467,61H45C20.218,61,0,81.196,0,106v300c0,24.72,20.128,45,45,45h422c24.72,0,45-20.128,45-45V106
		C512,81.28,491.872,61,467,61z M460.786,91L256.954,294.833L51.359,91H460.786z M30,399.788V112.069l144.479,143.24L30,399.788z
		M51.213,421l144.57-144.57l50.657,50.222c5.864,5.814,15.327,5.795,21.167-0.046L317,277.213L460.787,421H51.213z M482,399.787
		L338.213,256L482,112.212V399.787z"></path>
	</symbol>
</svg>
```

**Использование**

```html
<svg class="icon" viewBox="0 0 40 40">
	<use xlink:href="#email-img" x="14%" y="12%"></use>
</svg>
```

**Телефон**

```html
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="phone-img" viewBox="0 0 700 700">
		<title>phone-img</title>
		<path d="m98.339 320.8c47.6 56.9 104.9 101.7 170.3 133.4 24.9 11.8 58.2 25.8 95.3 28.2 2.3 0.1 4.5 0.2 6.8 0.2 24.9 0 44.9-8.6 61.2-26.3 0.1-0.1 0.3-0.3 0.4-0.5 5.8-7 12.4-13.3 19.3-20 4.7-4.5 9.5-9.2 14.1-14 21.3-22.2 21.3-50.4-0.2-71.9l-60.1-60.1c-10.2-10.6-22.4-16.2-35.2-16.2s-25.1 5.6-35.6 16.1l-35.8 35.8c-3.3-1.9-6.7-3.6-9.9-5.2-4-2-7.7-3.9-11-6-32.6-20.7-62.2-47.7-90.5-82.4-14.3-18.1-23.9-33.3-30.6-48.8 9.4-8.5 18.2-17.4 26.7-26.1 3-3.1 6.1-6.2 9.2-9.3 10.8-10.8 16.6-23.3 16.6-36s-5.7-25.2-16.6-36l-29.8-29.8c-3.5-3.5-6.8-6.9-10.2-10.4-6.6-6.8-13.5-13.8-20.3-20.1-10.3-10.1-22.4-15.4-35.2-15.4-12.7 0-24.9 5.3-35.6 15.5l-37.4 37.4c-13.6 13.6-21.3 30.1-22.9 49.2-1.9 23.9 2.5 49.3 13.9 80 17.5 47.5 43.9 91.6 83.1 138.7zm-72.6-216.6c1.2-13.3 6.3-24.4 15.9-34l37.2-37.2c5.8-5.6 12.2-8.5 18.4-8.5 6.1 0 12.3 2.9 18 8.7 6.7 6.2 13 12.7 19.8 19.6 3.4 3.5 6.9 7 10.4 10.6l29.8 29.8c6.2 6.2 9.4 12.5 9.4 18.7s-3.2 12.5-9.4 18.7c-3.1 3.1-6.2 6.3-9.3 9.4-9.3 9.4-18 18.3-27.6 26.8l-0.5 0.5c-8.3 8.3-7 16.2-5 22.2 0.1 0.3 0.2 0.5 0.3 0.8 7.7 18.5 18.4 36.1 35.1 57.1 30 37 61.6 65.7 96.4 87.8 4.3 2.8 8.9 5 13.2 7.2 4 2 7.7 3.9 11 6 0.4 0.2 0.7 0.4 1.1 0.6 3.3 1.7 6.5 2.5 9.7 2.5 8 0 13.2-5.1 14.9-6.8l37.4-37.4c5.8-5.8 12.1-8.9 18.3-8.9 7.6 0 13.8 4.7 17.7 8.9l60.3 60.2c12 12 11.9 25-0.3 37.7-4.2 4.5-8.6 8.8-13.3 13.3-7 6.8-14.3 13.8-20.9 21.7-11.5 12.4-25.2 18.2-42.9 18.2-1.7 0-3.5-0.1-5.2-0.2-32.8-2.1-63.3-14.9-86.2-25.8-62.2-30.1-116.8-72.8-162.1-127-37.3-44.9-62.4-86.7-79-131.5-10.3-27.5-14.2-49.6-12.6-69.7z"></path>
	</symbol>
</svg>
```

**Использование**

```html
<svg class="icon" viewBox="0 0 40 40">
    <use xlink:href="#phone-img" x="14%" y="12%"></use>
</svg>
```

