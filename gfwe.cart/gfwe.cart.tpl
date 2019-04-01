<!-- <a href="http://gfwe.ru/" data-cart_item_name='Шаблон 1 для сайта' data-cart_item_price='1350' data-cart_item_img="/item.jpg">Добавить товар 1 в корзину</a> -->
<gfwe-cart data-view='hide'>
	<script type="text/javascript" src='{THEME}/gfwe.cart/gfwe-cart.js'></script>
	<link rel="stylesheet" type="text/css" href="{THEME}/gfwe.cart/style.css">
	<div id='counter' class='flex' title='В корзине:' data-count='0'>
		<i class="fas fa-shopping-basket"></i>
	</div>
	<div id='list' class="flex" data-summa='0'>
		<div class='top'>
			<gfwe-cart-item>
				<gfwe-cart-img></gfwe-cart-img>
				<gfwe-cart-num>#</gfwe-cart-num>
				<gfwe-cart-name>Наименование</gfwe-cart-name>
				<gfwe-cart-price>Цена</gfwe-cart-price>
				<gfwe-cart-count>Кол-во</gfwe-cart-count>
				<gfwe-cart-sum>Сумма</gfwe-cart-sum>
			</gfwe-cart-item>
		</div>
		<div class='items'></div>
		<div class='buts flex'>
			<a href="#" data-gfwe-view-cart><i class="fas fa-times"></i><span>Закрыть</span></a>
			<a href="#" data-cart_clear><i class="far fa-trash-alt"></i><span>Очистить</span></a>
			<a href="#" data-cart_refresh><i class="fas fa-sync"></i><span>Обновить</span></a>
		</div>
	</div>
	<div id='cart_Load'><b></b><b></b><b></b><b></b></div>
</gfwe-cart>