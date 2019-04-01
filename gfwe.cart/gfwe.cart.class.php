<?php

	class gfwe_cart{

		var $cart = array();	// Все данные карзины
		var $summa = 0;			// Общая сумма товаров
		var $length = 0;		// Кол-во товаров
		var $items = array();	// Товары в корзине
		var $item = array();	// Данные нового товара
		var $search = '-1';		// Поиск по корзине. '-1' => нет такого товара в корзине.

		function __construct(){
			if(!isset($_SESSION['gfwe.cart']))
				$this->cart = array(
					'summa' => 0,
					'length' => 0,
					'items' => array()
				);
			else
				$this->cart = $_SESSION['gfwe.cart'];

			$this->items = $this->cart['items'];
			$this->summa = $this->cart['summa'];
		}

		function clear(){	### Очистка корзины товаров
			$this->summa = 0;
			$this->items = array();
		}

		private function save_session(){	### Запись данных корзины в сессию
			$_SESSION['gfwe.cart'] = $this->cart; 
		}

		private function search_item(){	### Поиск товара в корзние
			$this->search = '-1'; // Спросите почему -1? Потому что я немного ленивый и глупенький. Если поставить false, то будут проблемы с обновлением данных в $_SESSION['gfwe.cart']['item'][0]..  Потому что false = 0

			foreach($this->items as $k => $i){
				if($i['img'] == $this->item['img'] && $i['name'] == $this->item['name'] && $i['price'] == $this->item['price']){	// Если картинка, цена, имя товара такиеже как запись в корзине
					$this->search = $k;	// то возвращаем id в корзине
				}
			}
		}

		private function set_item($data){	### Запись данных нового товара в класс.
			$this->item = $data;
		}

		private function set_summa($item_id, $cart_sum = false){	### Подсчет суммы всей корзины или конкретного тавара
			if($cart_sum){	// Если подсчет суммы всей корзины, то:
				$this->summa = 0;	// Сбрасываем сумму корзины.
				$this->length = 0;	// Сбрасываем кол-во товаров в корзине.
				foreach($this->items as $k => $i){	// Перебираем все товары.
					$this->summa += $i['summa'];	// Плюсуем сумму.
					$this->length++;
				}
			}else{	// Если конкретного товара, то:
				$this->items[$item_id]['summa'] = $this->items[$item_id]['price'] * $this->items[$item_id]['count'];	// Просто считаем сумму товара.
			}
		}

		function delete($item_id){	### Удаление товара из корзины
			unset($this->items[$item_id]);	// Просто удаляем товар из корзины.
			$this->set_summa($item_id, 1);	// Пересчитываем сумму всей корзины.
		}

/*
### Старая версия изменения кол-ва товара. ###
		function change_count($item_id, $plus = true){	### Изменение счетчика кол-ва товара
			if($plus){	// Если $plus == true, то:
				$this->items[$item_id]['count']++;	// Увеличиваем счетчик на 1.
				$this->set_summa($item_id);	// Пересчитываем сумму товара.
			}else{	// Если $plus == false
				if($this->items[$item_id]['count'] == 1)	// Проверяем кол-во товара, если 1, то:
					// $this->delete($item_id);
					unset($this->items[$item_id]);	// Удаляем этот товар из корзины, т.к. если мы уменьшим четчик на 1, то будет 0 товара.
				else{	// если больше 1, то:
					$this->items[$item_id]['count']--;	// Уменьшаем счетчик на 1.
					$this->set_summa($item_id);	// Пересчитываем сумму товара.
				}
			}
			$this->set_summa($item_id, 1);	// Пересчитываем сумму всей корзины.
		}
*/
		function change_count($item_id, $count){	### Установить конкретное кол-во товара.
			$this->items[$item_id]['count'] = $count;	// Устанавливаем кол-во
			$this->set_summa($item_id);	// Пересчитываем сумму товара.
			$this->set_summa($item_id, 1);	// Пересчитываем сумму корзины.
		}

		private function plus_one_count($item_id){	### Добавить к кол-ву единицу
			$this->items[$item_id]['count']++;	// Увеличиваем счетчик на 1.
			$this->set_summa($item_id);	// Пересчитываем сумму товара.
			$this->set_summa($item_id, 1);	// Пересчитываем сумму всей корзины.
		}

		function add($data){	### Добавление товара в корзину.
			$this->set_item($data);	// Записывае данные товара в класс.
			$this->search_item();	// Производим поиск товара в корзине.

			if($this->search == '-1'){	// Если товара нет в корзине.
				$this->item['count'] = 1;	// Выставляем дефолтное кол-во.
				$this->item['summa'] = $this->item['count'] * $this->item['price'];	// Считаем сумму товара.
				$this->items[] = $this->item; // Добавляем товар в корзину.
				
				$this->set_summa(0, 1);
			}else{
				$this->plus_one_count($this->search);
			}
		}

		function new_items(){	### Упорядочить массив стоварами.
			$new_items = array();	// Создаем временную переменну с упорядоченным массивом товаров.
			foreach($this->items as $k => $i){ 
				$new_items[] = $i; // Заполняем массив.
			}
			$this->items = $new_items;	// Записываем новыйупорядоченный список.
		}

		function return_json(){	### AJAX данные.
			return json_encode($this->cart); // Превращаем данные корзины в json для передачи через AJAX
		}

		function finish(){	### Финишная функция

			$this->new_items();

			$this->cart = array(	// Собираем все данные в корзину.
				'summa' => $this->summa,
				'length' => $this->length,
				'items' => $this->items
			);

			$this->save_session();	// Сохраняем данныекорзины в сессию.
			return $this->return_json(); // Вернём данные корзины для AJAX
		}
		
		function __destruct(){

		}
	}

?>