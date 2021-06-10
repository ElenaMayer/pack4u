$(document).ready(function() {

	/*-------------------------------------------
	 Sticky Header
	 --------------------------------------------- */
    var win = $(window);
    var sticky_id = $("#sticky-header-with-topbar");
    win.on('scroll',function() {
        var scroll = win.scrollTop();
        if (scroll < 245) {
            sticky_id.removeClass("scroll-header");
        }else{
            sticky_id.addClass("scroll-header");
        }
    });

    $(document.body).on('click', '.add-to-cart', function (event) {
        if($('#diversity').val() == 1 && $('#diversity_id').val() == 0){
            $('.diversities').addClass('has_error');
            $('.diversity-error').show();
        } else {
            button = $(this);
            quantity = $('.product-qty').val();
            if (!quantity) {
                quantity = 1;
            }
            $.ajax({
                method: 'get',
                url: '/cart/add',
                dataType: 'json',
                data: {
                    id: button.data('id'),
                    diversity_id: button.data('diversity_id'),
                    quantity: quantity,
                },
            }).done(function (data) {
                add_to_cart_animation(button, data.count);
            });
        }
    });

    $(document.body).on('submit', '.notification-form', function (e) {
        e.preventDefault();
        form = $(this);
        $.ajax({
            method: 'post',
            url: '/cart/add_notification',
            dataType: 'json',
            data: {
                id: form.data('id'),
                diversity_id: form.data('diversity_id'),
                phone: form.find('input.phone').val(),
            },
        }).done(function (data) {
            $('#product_notification_modal').modal('hide');
            $('.add-notification').html('Сообщим о поступлении');
            $('.add-notification').addClass('added');
        });
    });

    $(document.body).on('click', '.product_wishlist', function () {
        var w = $('.wishlist-login');
        var e = $(this);
        if(w.length > 0){
            w.removeClass('hide');
            return false;
        } else {
            updateWishlist(e);
        }
    });

    $(document.body).on('click', '.catalog_wishlist', function () {
    	var e = $(this);
        updateWishlist(e);
    });

    $(document.body).on('submit', '#order-form', function (e) {
        if (!document.activeElement.className.match(/(?:^|\s)checkout-button(?:\s|$)/)
            || document.activeElement.className.match(/(?:^|\s)disabled(?:\s|$)/)) {
            return false;
        }
        if ("ga" in window) {
            tracker = ga.getAll()[0];
            if (tracker)
                tracker.send("event", "button", "send_order");
        }
        yaCounter48289898.reachGoal('ORDER');
    });

    $(document.body).on('click', '.add-to-cart', function (e) {
        if ("ga" in window) {
            tracker = ga.getAll()[0];
            if (tracker)
                tracker.send("event", "button", "add_to_cart");
        }
        yaCounter48289898.reachGoal('ADD_TO_CART');
        gtag('event', 'conversion', {
            'send_to': 'AW-788505492/mHdGCPHB2YcCEJTH_vcC',
            'transaction_id': '',
            'event_callback': callback
        });
    });

    $(document.body).on('mouseover', '.order-discount .fa-question-circle', function (e) {
        $(".prices-popup").removeClass("hide");
    });

    $(document.body).on('mouseout', '.order-discount .fa-question-circle', function (e) {
        $(".prices-popup").addClass("hide");
    });

    aweProductRender(true);

    $('#order-payment_method').find("input[value='account']").parents('div.radio').remove();
    // Shipping counting
    $(document.body).on('change', '#order-shipping_method' ,function()
    {
        $('.shipping_methods').children().each(function(){
            $(this).hide();
        });
        $('#order-address').val('');
        $('#order-city').val('');

        shipping_method = $(this).children("option:selected").val();

        if(shipping_method == 'self') {
            $('#order-payment_method').append($('<option>', {
                value: 'cash',
                text: 'Наличными при получении'
            }));
            $('.shipping_methods .self').show();
        } else {
            // $('#order-payment_method').children("option[value='cash']").remove();
            subtotal = parseInt($('#amount_subtotal').html());
            if (shipping_method == 'tk') {
                $('.shipping_methods .tk').show();
                $('#order-shipping_cost').val('');
                $('.amount_sum').hide();
                $('.amount_hint').show();
                $('.checkout-button').addClass('disabled');
            } else {
                shipping = parseInt($('#shipping-default').val());
                $('tr.shipping span.amount').html(shipping);
                $('#amount_total').html(subtotal+shipping);
                $('.shipping_methods .order-address').show();
                $('.amount_sum').show();
                $('.amount_hint').hide();
                $('.checkout-button').removeClass('disabled');
            }
        }
    });

    $(document.body).on('change', '#order-payment_method' ,function(){
        method = $(this).find("input:checked").val();
        change_order_send_button(method);
    });

    $(document.body).on('click', '#order-is_ul' ,function(){
        if($(this).prop('checked')) {
            $('.ul_requisites').show();
            $('#order-payment_method').find("input").each(function(){
                $(this).parents('div.radio').remove();
            });
            $('#order-payment_method').append($('<div class="radio"><label><input type="radio" name="Order[payment_method]" value="account" checked> Оплата по счету</label></div>'));
        } else {
            $('.ul_requisites').hide();
            $('#order-payment_method').find("input[value='account']").parents('div.radio').remove();

            $('#order-payment_method').append($('<div class="radio"><label><input type="radio" name="Order[payment_method]" value="online" checked> Банковской картой онлайн (комиссия 0%)</label></div>'));
            $('#order-payment_method').append($('<div class="radio"><label><input type="radio" name="Order[payment_method]" value="card"> Переводом на карту</label></div>'));
        }
        change_order_send_button($('#order-payment_method').find("input:checked").val());
    });

    $(document.body).on('change', '#order-tk' ,function(){
        if($(this).children("option:selected").val() == 'cdek'){
            $('tr.shipping > td > p').html('Уточнить стоимость можно у нашего менеджера');
        } else if($(this).children("option:selected").val() == 'pec') {
            $('tr.shipping > td > p').html('Оплата при получении. Уточнить стоимость можно <a target="_blank" class="text-primary" href="https://pecom.ru/services-are/the-calculation-of-the-cost/">ТУТ</a>');
        } else if($(this).children("option:selected").val() == 'dellin') {
            $('tr.shipping > td > p').html('Оплата при получении. Уточнить стоимость можно <a target="_blank" class="text-primary" href="https://novosibirsk.dellin.ru/">ТУТ</a>');
        } else if($(this).children("option:selected").val() == 'nrg') {
            $('tr.shipping > td > p').html('Оплата при получении. Уточнить стоимость можно <a target="_blank" class="text-primary" href="https://nrg-tk.ru/client/calculator/">ТУТ</a>');
        }
    });


    //Change cart qty
    $(document.body).on('change', 'input.cart-qty' ,function(){
        updateCartQty($(this).parents('form'));
    });
    $(document.body).on('keyup', 'input.cart-qty' ,function(){
        updateCartQty($(this).parents('form'));
    });

    $(document.body).on('click', '#remove_cart_item' ,function(){
        e = $(this).parents('.cart_item');
        $.ajax({
            method: 'get',
            url: '/cart/remove',
            dataType: 'json',
            data: {
                id: $(this).data('id'),
            },
        }).done(function( data ) {
            e.hide('slow');
            $('.has-cart').each(function(){
                $(this).children('em').show().text(data.count);
            });
            orderAvailableCheck(data);
            $("#data_total").html(data.data);
        });
    });

    $(document.body).on('click', '#remove_order_item' ,function(){
        e = $(this).parents('li');
        $.ajax({
            method: 'get',
            url: '/cart/remove',
            dataType: 'json',
            data: {
                id: $(this).data('id'),
            },
        }).done(function( data ) {
            e.hide('slow');
            $('.has-cart').each(function(){
                $(this).children('em').show().text(data.count);
            });
            $("#data_total").html(data.data);
            shipping = parseInt($('#amount_shipping').text());
            if(shipping) {
                $('#amount_total').text(data.total + shipping);
            } else {
                $('#amount_total').text(data.total);
            }
            orderAvailableCheck(data);
        });
    });

    $(document.body).on('click', '#remove_wl_item' ,function(){
        e = $(this).parents('tr');
        $.ajax({
            method: 'get',
            url: '/wishlist/remove',
            dataType: 'json',
            data: {
                id: $(this).data('id'),
            },
        }).done(function( data ) {
            e.hide('slow');
        });
    });

    $(document.body).on('change', 'input.product-qty' ,function(){
        checkProductCount($(this).parents('form'));
    });

    $(document.body).on('keyup', 'input.product-qty' ,function(){
        checkProductCount($(this).parents('form'));
    });

    $(".mobile-filter").on("click", function() {
        i = $(this).find('i');
    	if(i.hasClass('fa-angle-down')){
            $('.mobile-filter-field').each(function(){
				$(this).show();
				i.removeClass('fa-angle-down').addClass('fa-angle-up');
            });
		} else {
            $('.mobile-filter-field').each(function(){
                $(this).hide();
                i.removeClass('fa-angle-up').addClass('fa-angle-down');
            });
		}
    });

    $(".noo-list").on("click", function() {
    	if(!$(this).hasClass('active')){
            $(this).addClass('active');
            $('.noo-grid').removeClass('active');
    		$('.products.row').addClass('product-list').removeClass('product-grid');
		}
    });
    $(".noo-grid").on("click", function() {
        if(!$(this).hasClass('active')){
            $(this).addClass('active');
            $('.noo-list').removeClass('active');
            $('.products.row').addClass('product-grid').removeClass('product-list');
        }
    });

    $(".noo-search").on("click", function() {
        $(".search-header5").fadeIn(1).addClass("search-header-eff");
        $(".search-header5").find('input[type="search"]').val("").attr("placeholder", "").select();
        return false;
    });
    $(".remove-form").on("click", function() {
        $(".search-header5").fadeOut(1).removeClass("search-header-eff");
    });
    $(".button-menu-extend").on("click", function() {
        $(".noo-menu-extend-overlay").fadeIn(1, function() {
            $(".noo-menu-extend").addClass("show");
        }).addClass("show");
        return false;
    });
    $(".menu-closed, .noo-menu-extend-overlay").on("click", function() {
        $(".noo-menu-extend-overlay").removeClass("show").hide();
        $(".noo-menu-extend").removeClass("show");
    });
    if ($("body").hasClass("fixed_top")) {
        $(window).scroll(function() {
            var $resTopbar = 0;
            if ($(".noo-topbar").length > 0) {
                var $heightTopbar = $(".noo-topbar").height();
                $resTopbar = "-" + $heightTopbar + "px";
            }
            var $heightBar = $("header").height();
            if ($(".header-5").length > 0) {
                if ($(window).width() < 992) {
                    $resTopbar = "144px";
                } else {
                    $heightBar = 200;
                }
            }
            var $top = $(window).scrollTop();
            if ($top <= $heightBar) {
                if ($("header").hasClass("eff")) {
                    if ($(".header-6").length > 0) {
                        $("header").css("marginTop", "25px").removeClass("eff");
                    } else {
                        $("header").css("marginTop", 0).removeClass("eff");
                    }
                }
            } else {
                if (!$("header").hasClass("eff")) {
                    $("header").css("marginTop", "-150px").animate({
                        marginTop: $resTopbar
                    }, 700);
                    $("header").addClass("eff");
                }
            }
        });
    }
    resize_window();
    $(window).resize(function() {
        resize_window();
    });
    function resize_window() {
        if ($(".header-1").length > 0) {
            if ($(window).width() < 1500) {
                if ($("header").find(".noo-menu-option").find("li").length > 0) $("header").find(".noo-menu-option").addClass("collapse");
            } else {
                $("header").find(".noo-menu-option").removeClass("collapse");
            }
        }
        if ($(".header-3").length > 0) {
            if ($(window).width() < 1300) {
                if ($("header").find(".noo-menu-option").find("li").length > 0) $("header").find(".noo-menu-option").addClass("collapse");
            } else {
                $("header").find(".noo-menu-option").removeClass("collapse");
            }
        }
    }
    $("#off-canvas-nav li.menu-item-has-children").append('<i class="fa fa-angle-down"></i>');
    $("#off-canvas-nav li.menu-item-has-children i").on("click", function(e) {
        var link_i = $(this);
        link_i.prev().slideToggle(300);
        link_i.parent().toggleClass("active");
    });
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $(".navbar-nav").find(".menu-item-has-children").find("a").on("touchstart", function(e) {
            "use strict";
            var link = $(this);
            if (link.hasClass("hover")) {
                return true;
            } else {
                link.addClass("hover");
                $(".navbar-nav").find(".menu-item-has-children").find("a").not(this).removeClass("hover");
                e.preventDefault();
                return false;
            }
        });
    }
	//Countdown Timer
	if($('.defaultCountdown').length > 0) {
		var austDay = new Date(2016, 03 - 1,  31);
		$('.defaultCountdown').countdown({until: austDay});
		$('#year').text(austDay.getFullYear());
	}
	if($('.noo_custom_countdown').length > 0) {
		var austDay = new Date(2016, 03 - 1,  21);
		$('.noo_custom_countdown').countdown({until: austDay});
		$('#year').text(austDay.getFullYear());
	}

	//Owl Carousel
	$('.noo-product-sliders').owlCarousel({
		items : 4,
		itemsDesktop : [1199,4],
		itemsDesktopSmall : [991,2],
		itemsTablet: [768, 2],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoHeight: false,
		addClassActive: true,
		autoPlay: false,
		loop:true,
		pagination: false,
        navigation:true,
        navigationText: [
            "<i class='fa fa-chevron-left'></i>",
            "<i class='fa fa-chevron-right'></i>"
        ],
        dots: true,
	});

	//Owl Carousel
	$('.blog-slider').owlCarousel({
		items : 1,
		singleItem: true,
	});

	$('.noo-slider-image').owlCarousel({
		items : 3,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [991,2],
		itemsTablet: [768, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoHeight: true,
		addClassActive: true,
		autoPlay: true,
		loop:true,
		pagination: false
	});

    $('.noo-simple-product-slider').owlCarousel({
		items : 5,
		itemsDesktop : [1199,5],
		itemsDesktopSmall : [979,3],
		itemsTablet: [768, 2],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoHeight: true,
		addClassActive: true,
		autoPlay: false,
		loop:true,
		pagination: false,
        navigation:true,
        navigationText: [
            "<i class='fa fa-chevron-left'></i>",
            "<i class='fa fa-chevron-right'></i>"
        ],
        dots: true,

    });

	//Testimonial Carousel
	var sync1 = $(".noo-testimonial-sync2");
	var sync2 = $(".noo-testimonial-sync1");

	sync1.owlCarousel({
		singleItem : true,
		slideSpeed : 1000,
		navigation: false,
		pagination:false,
		afterAction : syncPosition,
		responsiveRefreshRate : 200
	});

	function syncPosition(el){
		var current = this.currentItem;
		$(".noo-testimonial-sync1")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
		if($(".noo-testimonial-sync1").data("owlCarousel") !== undefined){
			center(current)
		}
	}

	$(".noo-testimonial-sync1").on("click", ".owl-item", function(e){
		e.preventDefault();
		var number = $(this).data("owlItem");
		sync1.trigger("owl.goTo",number);
	});

	sync2.owlCarousel({
		items : 3,
		itemsDesktop      : [1199,3],
		itemsDesktopSmall     : [979,3],
		itemsTablet       : [768,3],
		itemsMobile       : [479,2],
		pagination:false,
		responsiveRefreshRate : 100,
		afterInit : function(el){
			el.find(".owl-item").eq(1).click();
		}
	});

	function center(number){
		var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
		var num = number;
		var found = false;
		for(var i in sync2visible){
			if(num === sync2visible[i]){
				var found = true;
			}
		}

		if(found===false){
			if(num>sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", num - sync2visible.length+2)
			}else{
				if(num - 1 === -1){
					num = 0;
				}
				sync2.trigger("owl.goTo", num);
			}
		} else if(num === sync2visible[sync2visible.length-1]){
			sync2.trigger("owl.goTo", sync2visible[1])
		} else if(num === sync2visible[0]){
			sync2.trigger("owl.goTo", num-1)
		}
	}

	$('.noo_testimonial').each(function(){
		$(this).owlCarousel({
			items : 1,
			itemsDesktop : [1199,1],
			itemsDesktopSmall : [979,1],
			itemsTablet: [768, 1],
			slideSpeed:500,
			paginationSpeed:800,
			rewindSpeed:1000,
			autoHeight: false,
			addClassActive: true,
			autoPlay: true,
			loop:true,
			pagination: true,
			afterInit : function(el){
				el.find(".owl-item").eq(1).addClass("synced");
			}
		});
	});

	//Ajax popup
	if($(".noo-qucik-view").length > 0) {
		$('.noo-qucik-view').magnificPopup({
			type: 'ajax'
		});
	}

	//Boxes hover
	$('.box-inner').each(function(){
		var first_color = $(this).find('.product-box-header li:first-child span').data('color');
		$(this).find('.product-box-header li:first-child span').css('background',first_color).addClass('acitve');
		$(this).find('.box-content h3').css('color',first_color);
		$(this).find('.box-description-tab').css('background-color',first_color);
	});

	$('.product-box-header li span').mousemove(function(){
		var $parent = $(this).closest('.box-inner');
		$parent.find('.product-box-header li span').removeAttr('style').removeClass('acitve');
		var color   = $(this).data('color');
		var id      = $(this).data('id');
		$(this).css('background',color).addClass('acitve');
		$parent.find('.box-content-tab').hide();
		$parent.find(id).show();
	});

	//Boxes Detail Slider
	$(".sync1").owlCarousel({
		singleItem : true,
		slideSpeed : 1000,
		navigation: false,
		pagination:false,
		autoHeight : true,
		responsiveRefreshRate : 200
	});

	$(".sync2").owlCarousel({
		items : 4,
		itemsDesktop      : [1199,4],
		itemsDesktopSmall : [979,4],
		itemsTablet       : [768,3],
		itemsMobile       : [479,2],
		pagination:false,
		responsiveRefreshRate : 100
	});

	$(".sync2").on("click", ".owl-item", function(e){
		e.preventDefault();
		var number = $(this).data("owlItem");
		$(".sync1").trigger("owl.goTo",number);
	});

	//Recent Post Background
	$('.widget_recent_entries .post_list_widget li').each(function(){
		var post_thumb = $(this).find(".post-thumb");
		post_thumb.css('background-image','url("' + post_thumb.attr("data-bg") + '")');
	});

	//Noo Simple Product Slider
	$('.noo-simple-product-slider li').each(function(){
		var slider_item = $(this).find(".noo-simple-slider-item");
		slider_item.css('background-image','url("' + slider_item.attr("data-bg") + '")');
	});

    // $(document.body).on('keyup', '#order-zip' ,function(){
    //     if ($(this).val().length == 6) {
    //         total = parseInt($('#amount_subtotal').text());
    //         postcalc_url = 'http://api.postcalc.ru/mystat.php/';
    //         postcode_from = '630001';
    //         postcode_to = $(this).val();
    //         weight = $('#order_weight').val() * 1000 * 1.1;
    //         $.ajax({
    //             url: postcalc_url,
    //             type: "GET",
    //             data: {
    //                 f: postcode_from,
    //                 t: postcode_to,
    //                 w: weight,
    //                 o: 'json',
    //             },
    //             dataType: 'jsonp',
    //             success: function (data) {
    //                 if (data['Status'] == "OK") {
    //                     tariff = parseInt(data['Отправления']['ЦеннаяПосылка']['Тариф']);
    //                     shipping_cost = tariff;
    //                     new_total = total + shipping_cost;
    //                     $('.shipping > td > p').html("<span id=\"amount_shipping\">" + shipping_cost.toFixed(0) + "</span><i class=\"fa fa-ruble\"></i>");
    //                     $('#amount_total').html(new_total.toFixed(0));
    //                     $("#order-shipping_cost").val(shipping_cost.toFixed(0));
    //                 } else if(data['Status'] == "BAD_TO_INDEX"){
    //                     $('.shipping > td > p').html("Стоимость не определена. Проверьте индекс и попробуйте снова");
    //                     $('#amount_total').html(total);
    //                     $("#order-shipping_cost").val(null);
    //                 }
    //             }
    //         })
    //     }
    // });

    // $(document.body).on('click', '.courier_cost_button' ,function(){
    //     address = $('#order-address').val();
    //     if(address.length > 0) {
    //         weight = $('#order_weight').val() * 1.05;
    //         total = parseInt($('#amount_subtotal').text());
    //         $('html').addClass("wait");
    //         $.ajax({
    //             url: '/cart/get_courier_cost',
    //             type: "GET",
    //             data: {
    //                 weight: weight,
    //                 address: address,
    //             },
    //             complete: function () {
    //                 $('html').removeClass("wait");
    //             },
    //             success: function (data) {
    //                 if(data){
    //                     new_total = total + parseInt(data);
    //                     $('.shipping > td > p').html("<span id=\"amount_shipping\">" + parseInt(data).toFixed(0) + "</span><i class=\"fa fa-ruble\"></i>");
    //                     $('#amount_total').html(new_total.toFixed(0));
    //                     $("#order-shipping_cost").val(parseInt(data));
    //                 } else {
    //                     $('.courier .help-block-error').text('Некорректный адрес');
    //                     $('.courier .form-group').addClass('has-error');
    //                 }
    //             }
    //         });
    //     } else {
    //         $('.courier .help-block-error').text('Введите адрес для расчета');
    //         $('.courier .form-group').addClass('has-error');
    //     }
    // });

    $(document.body).on('click', '#diversity-link' ,function() {
        $.ajax({
            url: $(this).data('href'),
            type: "GET",
            success: function (data) {
                $('#product-data').html(data);
                aweProductRender(true);
            }
        });
    });

    var token = "1c57b65d747773a848f9de3433c16e10f9eb7d08";

    $("#geo_city").on("keyup", function() {
        if($(this).val().length > 0) {
            $('.geo_cities_list').hide();
        } else {
            $('.geo_cities_list').show();
        }
    });

    $(document.body).on('click', '.geo_city_const' ,function() {
        city = $(this).text();
        change_location(city);
    });

    $(document.body).on('click', '#geo_city_button' ,function() {
        city = $('#geo_city').val();
        change_location(city);
    });

    // Ограничиваем область поиска от города до населенного пункта
    $("#geo_city").suggestions({
        token: token,
        type: "ADDRESS",
        hint: false,
        geoLocation: false,
        bounds: "city-settlement",
        onSuggestionsFetch: removeNonCity,
        onSelect: function(suggestion) {
            if(suggestion.data.city) {
                city = suggestion.data.city;
            } else {
                city = suggestion.value;
            }
            change_location(city);
        }
    });

    //Init RevSlider
    if($('#rev_slider_1').length > 0) {
        revSlider_1();
    }
    if($('#rev_slider_2').length > 0) {
        revSlider_2();
    }
    if($('#rev_slider_3').length > 0) {
        revSlider_3();
    }

    var userFeed = new Instafeed({
        get: 'user',
        userId: '6706865954',
        clientId: '5240196a48394517bde1696155cb28c1',
        accessToken: '6706865954.5240196.56f267dc18a04dcdab58b9f7d53697b4',
        template: '<a href="{{link}}" class="instafeed_image" target="_blank" id="{{id}}"><img src="{{image}}" /></a>',
        sortBy: 'most-recent',
        limit: 9,
        links: false
    });

    userFeed.run();

});

function change_location(city) {
    $.ajax({
        url: '/site/change_location',
        type: "GET",
        data: {
            city: city,
        },
        success: function (data) {
            if($('#order-form')) {
                fio = $('#order-fio').val();
                email = $('#order-email').val();
                phone = $('#order-phone').val();
                notes = $('#order-notes').text();
                url = document.location.href.replace(/^|\#+$/g, '');

                url = url + "?fio=" + fio + "&email=" + email + "&phone=" + phone + "&notes=" + notes + "&sm=rp";
                document.location = url;
            }
        }
    });
}

// удаляет районы города и всё с 65 уровня
function removeNonCity(suggestions) {
    return suggestions.filter(function(suggestion) {
        return suggestion.data.fias_level !== "5" && suggestion.data.fias_level !== "65";
    });
}

function change_order_send_button(method) {
    if(method == 'online'){
        $('.cart-offer > span').text('Оплатить заказ');
        $('.checkout-button').html('Оплатить заказ');
    } else {
        $('.cart-offer > span').text('Отправить заказ');
        $('.checkout-button').html('Отправить заказ');
    }
}

function add_to_cart_animation(button, count){
    button.addClass('is-added').find('path').eq(0).animate({
        //draw the check icon
        'stroke-dashoffset':0
    }, 300, function(){
        setTimeout(function(){
            $('.has-cart').each(function(){
                $(this).children('em').show().text(count);
            });
            button.removeClass('is-added').find('em').on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
                //wait for the end of the transition to reset the check icon
                button.find('path').eq(0).css('stroke-dashoffset', '19.79');
                animating =  false;
            });

            if( $('.no-csstransitions').length > 0 ) {
                // check if browser doesn't support css transitions
                addToCartBtn.find('path').eq(0).css('stroke-dashoffset', '19.79');
                animating =  false;
            }
        }, 600);
    });
}

function revSlider_1(){
	$("#rev_slider_1").show().revolution({
	  sliderType:"standard",
	  sliderLayout:"fullscreen",
	  dottedOverlay:"none",
	  delay:9000,
	  navigation: {
		  keyboardNavigation:"off",
		  keyboard_direction: "horizontal",
		  mouseScrollNavigation:"off",
		  onHoverStop:"on",
		  touch:{
			  touchenabled:"on",
			  swipe_threshold: 75,
			  swipe_min_touches: 50,
			  swipe_direction: "horizontal",
			  drag_block_vertical: false
		  }
		  ,
		  bullets: {
			  enable:true,
			  hide_onmobile:true,
			  hide_under:600,
			  style:"ares",
			  hide_onleave:true,
			  hide_delay:200,
			  hide_delay_mobile:1200,
			  direction:"vertical",
			  h_align:"right",
			  v_align:"center",
			  h_offset:30,
			  v_offset:0,
			  space:5,
			  tmp:'<span class="tp-bullet-title">{{title}}</span>'
		  }
	  },
	  responsiveLevels:[1240,1024,778,480],
	  visibilityLevels:[1240,1024,778,480],
	  gridwidth:[1240,1024,778,480],
	  gridheight:[600,768,960,720],
	  lazyType:"none",
	  parallax: {
		  type:"mouse",
		  origo:"slidercenter",
		  speed:2000,
		  levels:[2,3,4,5,6,7,12,16,10,50,47,48,49,50,51,55],
		  type:"mouse",
	  },
	  shadow:0,
	  spinner:"off",
	  stopLoop:"on",
	  stopAfterLoops:2,
	  stopAtSlide:1,
	  shuffle:"off",
	  autoHeight:"off",
	  fullScreenAutoWidth:"off",
	  fullScreenAlignForce:"off",
	  fullScreenOffsetContainer: "",
	  fullScreenOffset: "",
	  hideThumbsOnMobile:"on",
	  hideSliderAtLimit:0,
	  hideCaptionAtLimit:0,
	  hideAllCaptionAtLilmit:0,
	  debugMode:false,
	  fallbacks: {
		  simplifyAll:"off",
		  nextSlideOnWindowFocus:"off",
		  disableFocusListener:false,
	  }
  });
}

function revSlider_2(){
	$("#rev_slider_2").show().revolution({
		sliderType:"standard",
		sliderLayout:"fullscreen",
		dottedOverlay:"none",
		delay:9000,
		navigation: {
			keyboardNavigation:"off",
			keyboard_direction: "horizontal",
			mouseScrollNavigation:"off",
			onHoverStop:"off",
			arrows: {
				style:"hades",
				enable:true,
				hide_onmobile:false,
				hide_onleave:false,
				tmp:'<div class="tp-arr-allwrapper"><div class="tp-arr-imgholder"></div></div>',
				left: {
					h_align:"left",
					v_align:"center",
					h_offset:20,
					v_offset:0
				},
				right: {
					h_align:"right",
					v_align:"center",
					h_offset:20,
					v_offset:0
				}
			}
		},
		visibilityLevels:[1240,1024,778,480],
		gridwidth:1240,
		gridheight:868,
		lazyType:"none",
		shadow:0,
		spinner:"spinner0",
		stopLoop:"on",
		stopAfterLoops:1,
		stopAtSlide:0,
		shuffle:"off",
		autoHeight:"off",
		fullScreenAutoWidth:"off",
		fullScreenAlignForce:"off",
		fullScreenOffsetContainer: "",
		fullScreenOffset: "",
		hideThumbsOnMobile:"off",
		hideSliderAtLimit:0,
		hideCaptionAtLimit:0,
		hideAllCaptionAtLilmit:0,
		debugMode:false,
		fallbacks: {
			simplifyAll:"off",
			nextSlideOnWindowFocus:"off",
			disableFocusListener:false,
		}
  });
}

function revSlider_3(){
	$("#rev_slider_3").show().revolution({
		sliderType:"standard",
		sliderLayout:"fullscreen",
		dottedOverlay:"none",
		delay:9000,
		navigation: {
			keyboardNavigation:"off",
			keyboard_direction: "horizontal",
			mouseScrollNavigation:"off",
			onHoverStop:"on",
			arrows: {
				style:"zeus",
				enable:true,
				hide_onmobile:false,
				hide_onleave:false,
				tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',
				left: {
					h_align:"left",
					v_align:"center",
					h_offset:20,
					v_offset:0
				},
				right: {
					h_align:"right",
					v_align:"center",
					h_offset:20,
					v_offset:0
				}
			}
		},
		visibilityLevels:[1240,1024,778,480],
		gridwidth:1240,
		gridheight:868,
		lazyType:"none",
		shadow:0,
		spinner:"spinner0",
		stopLoop:"off",
		stopAfterLoops:-1,
		stopAtSlide:-1,
		shuffle:"off",
		autoHeight:"off",
		fullScreenAutoWidth:"off",
		fullScreenAlignForce:"off",
		fullScreenOffsetContainer: "",
		fullScreenOffset: "",
		disableProgressBar:"on",
		hideThumbsOnMobile:"off",
		hideSliderAtLimit:0,
		hideCaptionAtLimit:0,
		hideAllCaptionAtLilmit:0,
		debugMode:false,
		fallbacks: {
			simplifyAll:"off",
			nextSlideOnWindowFocus:"off",
			disableFocusListener:false,
		}
  });
}

$(window).on('load', function(){
    $(".noo-page-heading").addClass("eff");
    $(".page-title").addClass("eff");
    $(".noo-page-breadcrumb").addClass("eff");
    $(".noo-spinner").remove();
});

function aweProductRender(thumbHorizontal) {

    var sMain = new Swiper('.product-slider-main', {
        loop: false,

        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev'
    });

    var sThumb = new Swiper('.product-slider-thumbs', {
        loop: false,
        centeredSlides: true,
        spaceBetween: thumbHorizontal ? 15 : 0,
        slidesPerView: thumbHorizontal ? 4 : 3,
        direction: thumbHorizontal ? 'horizontal' : 'vertical',
        slideToClickedSlide: true
    });

    sMain.params.control = sThumb;
    sThumb.params.control = sMain;
}

function updateCartQty(form) {
	var e = form.find('input.cart-qty');
	var count = form.find("input[name='count']").val();
	if(!e.hasClass('disable') && e.val() && e.val() > 0){
		if(parseInt(e.val()) > count){
            form.find("input[name='quantity']").val(count);
            e.parents('.product-quantity').find('.count-error').show();
		} else {
            e.parents('.product-quantity').find('.count-error').hide();
		}
        //e.addClass('disable').prop('readonly', true);
        $.ajax({
           method: 'get',
           url: '/cart/update_cart_qty',
           dataType: 'json',
           data: $(form).serialize(),
        }).done(function( data ) {
			$('#amount_val_'+data.id).text(data.productTotal);
            $('#amount_price_'+data.id).text(data.productPrice);
            e.val(data.count);
            orderAvailableCheck(data);
            $("#data_total").html(data.data);
            // e.removeClass('disable').prop('readonly', false);
        }).fail(function( data ) {
            e.removeClass('disable').prop('readonly', false);
        });
	}

}

function checkProductCount(form) {
    if($('#diversity').val() != 1 || $('#diversity_id').val() != 0){
        var count = form.find("input[name='count']").val();
        if(parseInt($('input.product-qty').val()) > count){
            form.find("input[name='quantity']").val(count);
            $('.count-error').show();
        } else {
            $('.count-error').hide();
        }
    }
}

function updateWishlist(e) {
    $.ajax({
        method: 'get',
        url: '/wishlist/update',
        dataType: 'json',
        data: {
            id: e.attr('id'),
        },
    }).done(function( data ) {
        p = e.parents('.yith-wcwl-add-to-wishlist');
        if(p.hasClass('active'))
            p.removeClass('active')
        else
            p.addClass('active');
    });
}

function orderAvailableCheck(data) {
    if(data.orderAvailable){
        $('.min_order_sum').hide();
        $('.checkout-button').removeClass('disabled');
    } else {
        $('.min_order_sum').show();
        $('.checkout-button').addClass('disabled');
    }
}