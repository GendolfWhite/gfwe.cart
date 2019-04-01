<?php
	
	ini_set('display_errors','On');
	error_reporting(E_ALL);

	require_once 'gfwe.cart.class.php';

	function p($data, $ta = false){echo ($ta ? "<textarea title='".$ta."'>" : "<pre>"); print_r($data); echo ($ta ? "</textarea>" : "</pre>");}

	session_start();

	if(@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
		
		$cart = new gfwe_cart();

		switch(@$_POST['act']){
			case 'add':
				$cart->add(array(
					'img' => $_POST['img'],
					'name' => $_POST['name'],
					'price' => $_POST['price']
				));
				break;

			case 'clear':
				$cart->clear();
				break;

			case 'delete':
				$cart->delete($_POST['item_id']);
				break;

			case 'count':
				$cart->change_count($_POST['item_id'], $_POST['count']);
				break;
			
			default:
				# code...
				break;
		}
		
		echo $cart->finish();
	}else{
		die('Отличная попытка');
	}
?>