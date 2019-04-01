$(function(){

	function p($v, $t = false){
		if($t)
			console.log($t+": "+$v);
		else
			console.log($v);
	}

	function view_cart_list(){
		$cart_view = $('gfwe-cart').attr('data-view');

		if($cart_view == 'show'){
			$('gfwe-cart').attr({'data-view':'hide'});
		}else{
			$('gfwe-cart').attr({'data-view':'show'});
		}
	}

	$('[data-gfwe-view-cart], gfwe-cart > #counter').click(function(){
		view_cart_list();
		return false;
	});
	
	function gennerate_item_HTML($item, $num = '#', $html = ''){
		$html += "\n<gfwe-cart-item>";
		$html += "\n<gfwe-cart-img style='background-image:url("+$item.img+")'><i class='fas fa-times'></i></gfwe-cart-img>";
		$html += "\n<gfwe-cart-num>"+$num+"</gfwe-cart-num>";
		$html += "\n<gfwe-cart-name>"+$item.name+"</gfwe-cart-name>";
		$html += "\n<gfwe-cart-price>"+$item.price+"</gfwe-cart-price>";
		$html += "\n<gfwe-cart-count>"+$item.count+"</gfwe-cart-count>";
		$html += "\n<gfwe-cart-sum>"+$item.summa+"</gfwe-cart-sum>";
		$html += "\n</gfwe-cart-item>";
		return $html;
	}

	function update_cart_HTML($data, $html = ''){
		$items = $data.items;
		$('gfwe-cart > #counter').attr({'data-count':$items.length});
		$('gfwe-cart > #list').attr({'data-summa':$data.summa});
		
		$items.forEach(function($item, $i, $arr){
			console.log($item.name+" : "+$i);
			$html += gennerate_item_HTML($item, $i+1);
		});

		$('gfwe-cart > #list > .items').html($html);
	}

	function send_ajax($data){
		$.ajax({
			type:"POST",
			url:'gfwe.cart.php',
			data:$data,
			cache:false,
			dataType:'json',
			beforeSend: function(){
				cart_Load();
			},
			error: function(req, text, error){
				console.error('Упс! Ошибочка: ' + text + ' | ' + error);
				console.log('error');
			},
			complete: function(){
				setTimeout(function(){
					cart_Load();
				}, 500);
			},
			success: function($data){
				update_cart_HTML($data);
			}
		});
	}

	function add_item_to_cart($item){
		$name = $item.attr('data-cart_item_name');
		$price = $item.attr('data-cart_item_price');
		$img = $item.attr('data-cart_item_img');

		send_ajax({'act':'add', 'name':$name, 'price':$price, 'img':$img});
	}

	function clear_cart(){
		send_ajax({'act':'clear'});
	}

	function cart_Load(){
		$('#cart_Load').toggleClass('send');
		console.log('cart_Load');
	}

	function delete_item($item){
		$item_id = parseInt($item.parent('gfwe-cart-item').children('gfwe-cart-num').text()) - 1;
		console.log($item_id);
		send_ajax({'act':'delete','item_id':$item_id});
		return false;
	}

	function check_active_edit_count(){
		if($('gfwe-cart > #list gfwe-cart-item > input').attr('data-sum') !== undefined){
			$old = $('gfwe-cart > #list gfwe-cart-item > input');
			$old.after("<gfwe-cart-count>"+$old.attr('data-count')+"</gfwe-cart-count><gfwe-cart-sum>"+$old.attr('data-sum')+"</gfwe-cart-sum>");
			$('gfwe-cart > #list gfwe-cart-item > input, gfwe-cart > #list gfwe-cart-item > button').remove();
		}
	}

	function edit_count_item($item){
		check_active_edit_count();

		$id = $item.siblings('gfwe-cart-num').text(); $count = $item.text(); $sum = $item.siblings('gfwe-cart-sum').text();

		$item.siblings('gfwe-cart-sum').after('<button id="'+$id+'"><i class="fas fa-check"></i></button>').remove();
		$item.after("<input type='number' data-sum='"+$sum+"' data-count='"+$count+"' value='"+$count+"' required  min='1'>").remove();
		$('gfwe-cart-item > input').focus();
		return false;
	}

	function finish_edit_count_item($item){
		$item_id = parseInt($item.attr('id')) - 1;
		$count = parseInt($item.siblings('input').val());
		$old_count = parseInt($item.siblings('input').attr('data-count'));

		if($count > 0){
			if($count !== $old_count){
				send_ajax({'act':'count','item_id':$item_id,'count':$count});
			}
		}
		check_active_edit_count();
		return false;
	}

	send_ajax();

	$('[data-cart_item_name]').click(function(){
		add_item_to_cart($(this));
		return false;
	});

	$('[data-cart_clear]').click(function(){
		clear_cart();
		return false;
	});
	$('[data-cart_refresh]').click(function(){
		send_ajax();
		return false;
	});

	$(document).on('click', 'gfwe-cart > #list gfwe-cart-item > gfwe-cart-img', function(){
		delete_item($(this));
		return false;
	});

	$(document).on('click', 'gfwe-cart > #list gfwe-cart-item > gfwe-cart-count', function(){
		edit_count_item($(this));
		return false;
	});

	$(document).on('click', 'gfwe-cart > #list gfwe-cart-item > button', function(){
		finish_edit_count_item($(this));
		return false;
	});
});