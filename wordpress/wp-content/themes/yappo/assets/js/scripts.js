var $ = jQuery;	



$(function() {
// custom scripts

		//scroll up

		$(window).scroll(function() {
			var scrollTop = $(this).scrollTop();
			var lastScrollTop = $(this).data('lastScrollTop');




			if (scrollTop > lastScrollTop && scrollTop - lastScrollTop > 10) {
			  if ($("#menu").hasClass('overlay')) {

			  } else {
				$('.header-center').addClass('header-center-scroll');
				$(".btns-wrap-header-menu").removeClass('btns-wrap-header-menu-active');
				$('.burger-desck').removeClass('burger-active');


				$('.resault-block').css('top','8rem');


				if($(window).width() <= 600){
					$('.form-wrap').css('top','0.8rem');
				}else if ($(window).width() <= 822){
					$('.form-wrap').css('top','0.8rem');
				}
				else{
					$('.form-wrap').css('top','0.8rem');
				}
			  }

			}

			else if (scrollTop < lastScrollTop && lastScrollTop - scrollTop > 10) {
			  $(".header-center").removeClass("header-center-scroll");
			  $('.resault-block').css('top','14rem');


				if($(window).width() <= 600){
					$('.form-wrap').css('top','5.5rem');
				}else if ($(window).width() <= 822){
					$('.form-wrap').css('top','8.5rem');
				}
				else{
					$('.form-wrap').css('top','7rem');
				}
			}

			$(this).data('lastScrollTop', scrollTop);
		});


		var previousScroll = 0;

		$(window).scroll(function() {
		  var currentScroll = $(this).scrollTop();

		  if (currentScroll >= 500) {
			  $('.fix-cart').css('display', 'flex');
		  } else {
			  $('.fix-cart').css('display', 'none');
		  }

		  previousScroll = currentScroll;
		});

	//modal search

	$('.form-search-btn').on('click', function () {
		var windowHeight = $(window).height();
		var headerHeight = $('.header').outerHeight();
		var footerHeight = $('.footer-modal').outerHeight();

		var pageHeight = windowHeight - headerHeight;

		$('.modal-search').css('min-height', pageHeight + 4 + 'px');
		// $('.modal-search').css('padding-bottom', footerHeight + 230 + 'px');

		$('.modal-search').toggleClass('modal-search-active');
		$('main').toggleClass('for-main-overflow');



		if ($('.modal-search').hasClass('modal-search-active')) {
			$(".btn-open-search").css('display','none'),
			$(".close-open-search").css('display','block')
			$("html, body").animate({ scrollTop: 0 }, "slow");
			$('.fix-cart').css('right','-200px');

		} else {
			$(".btn-open-search").css('display','block')
			$(".close-open-search").css('display','none')
			$('main').removeClass('for-main-overflow');
			$('.fix-cart').css('right','20px');
		}
	})



	//burger

	$('.burger').on('click', function () {
		$('#menu').toggleClass('overlay');
		$('main').toggleClass('for-main-overflow');
		$("main").toggleClass('put-up')
		$(this).toggleClass('burger-active');

		if ($(this).hasClass('burger-active')) {
			$("footer").css('display','none')
		} else {
			$("footer").css('display','block')
		}
	})

	$('main').on('click', function () {
		$(this).removeClass('put-up');
		$('main').removeClass('for-main-overflow');
		$('#menu').removeClass('overlay');
		$('.burger').removeClass('burger-active')
		$('.cart-modal').removeClass('cart-modal-active')
		$('body').removeClass('overflow');
		$('.fix-cart').css('opacity', '1');
		$('body').css('padding-top', '201.5px');

		if( window.innerWidth >= 600){
			$('body').css('padding-top', '201.5px');
		}
		else{
			$('body').css('padding-top', '175.5px');
		}
	})


	$('body').on('wc_fragments_refreshed added_to_cart', function(){
		setTimeout(function(){
			$('.fix-cart').css('display', 'flex');
		}, 100)
	})






	//filter

	$('.filter-btn-open').on('click', function () {
		$('.filter-options').addClass('filter-options-active');
		$('.filter-options').css('margin-bottom', '0')
		$('.filter-wrap').addClass('filter-wrap-active');
		$('.widget-btns').css("left", "-100%");
		$('.cheked-wrap').css("margin-top", "3rem");
	})


	$('.close-filter').on('click', function () {
		$('.filter-options').removeClass('filter-options-active');
		$('.filter-wrap').removeClass('filter-wrap-active');
		$('.widget-btns').css("left", "0%");
		$('.filter-options').css('margin-bottom', '30px')

		if( window.innerWidth >= 600){
			$('.cheked-wrap').css("margin-top", "6rem");
		}
		else{
			$('.cheked-wrap').css("margin-top", "3rem");
		}
	})



	$('.filter__checkgroup-title input').on('change', function () {
		$(this).closest(".filter__checkgroup-title").toggleClass('label-active');

	});

	$('.category-wrap-filter  .filter__checkgroup-title').on('change',function() {
		var radioButton = $(this);

		if (radioButton.hasClass('label-active')) {
		  var imageSrc = radioButton.find('img').attr('src');
		  var newImageSrc = imageSrc.replace('.svg', '-white.svg');
		  radioButton.find('img').attr('src', newImageSrc);
		} else {
		  var imageSrc = radioButton.find('img').attr('src');
		  var newImageSrc = imageSrc.replace('-white.svg', '.svg');
		  radioButton.find('img').attr('src', newImageSrc);
		}
	});



	$(".inp-regulation").click(function() {
        var input = $(this);
        var value = input.val();
        input.val("").val(value);
    });




	//local in header

	// $('.local-wrap').on('click', function (e) {

	// 	e.preventDefault();
	// 	$( ".city-list").toggleClass('city-list-active');
	// 	$(".btns-wrap-header-menu").removeClass('btns-wrap-header-menu-active');
	// 	$(".burger-desck").removeClass('burger-active');

	// 	if ($(".city-list").hasClass('city-list-active')) {
	// 		$('.local-wrap').css("background", "rgba(0, 0, 0, 0.2)");
	// 		toggleCart()
	// 	  } else {
	// 		$('.local-wrap').css("background", "rgba(0, 0, 0, 0.0)");
	// 	}
	// })
	$(document).on('click', '.local-wrap', function(e) {
		e.preventDefault();
		$(".city-list").toggleClass('city-list-active');
		$(".btns-wrap-header-menu").removeClass('btns-wrap-header-menu-active');
		$(".burger-desck").removeClass('burger-active');

		if ($(".city-list").hasClass('city-list-active')) {
			$('.local-wrap').css("background", "rgba(0, 0, 0, 0.2)");
			// toggleCart();
		} else {
			$('.local-wrap').css("background", "rgba(0, 0, 0, 0.0)");
		}
	});

	$(document).on('click', function(e) {
		if (!$(e.target).closest('.local-wrap').length) {
			$(".city-list").removeClass('city-list-active');
			$('.local-wrap').css("background", "rgba(0, 0, 0, 0.0)");
		}

		if (!$(e.target).closest('.btns-wrap-header-menu, .burger-desck').length) {
			$(".btns-wrap-header-menu").removeClass('btns-wrap-header-menu-active');
			$(".burger-desck").removeClass('burger-active');
		}

	});



	$('.burger-desck').on('click', function () {
		$(this).toggleClass('burger-active');
		$(".btns-wrap-header-menu").toggleClass('btns-wrap-header-menu-active');

	})




	//hover for svg

	$('.hover-effect-svg').hover(function() {
		$(this).find('[fill="#2A1A5E"]').attr('fill', '#3F2E77');
		$(this).find('[stroke="#2A1A5E"]').attr('stroke', '#3F2E77');
	}, function() {
		$(this).find('[fill="#3F2E77"]').attr('fill', '#2A1A5E');
		$(this).find('[stroke="#3F2E77"]').attr('stroke', '#2A1A5E');
	});



	//menu-page //hover for svg

	$('.menu-item').hover(function() {
		$(this, ".hover-effect-menu-page").find('[fill="#2A1A5E"]').attr('fill', '#FF671D');
		$(this, ".hover-effect-menu-page").find('[stroke="#2A1A5E"]').attr('stroke', '#FF671D');
	}, function() {
		$(this, ".hover-effect-menu-page").find('[fill="#FF671D"]').attr('fill', '#2A1A5E');
		$(this, ".hover-effect-menu-page").find('[stroke="#FF671D"]').attr('stroke', '#2A1A5E');
	});


	//contacts //hover for svg

	$('.contacts a').hover(function() {
		$(this, ".hover-effect-svg-local").find('[fill="#2A1A5E"]').attr('fill', '#736998');
		$(this, ".hover-effect-svg-local").find('[stroke="#2A1A5E"]').attr('stroke', '#736998');
	}, function() {
		$(this, ".hover-effect-svg-local").find('[fill="#736998"]').attr('fill', '#2A1A5E');
		$(this, ".hover-effect-svg-local").find('[stroke="#736998"]').attr('stroke', '#2A1A5E');
	});





	// top-slider


	if ($(".swiper").length) { const banner_slider = new Swiper(".banner-slider",{slidesPerView:1.2,spaceBetween:40,centeredSlides:!0,loop:!0,simulateTouch:!0,navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},speed:1e3,autoplay:{delay:5e3,stopOnLastSlide:!0,disableOnInteraction:!1},breakpoints:{425:{},550:{spaceBetween:100,slidesPerView:1.4},768:{spaceBetween:150,slidesPerView:1.6},1024:{spaceBetween:250,slidesPerView:1.8},1700:{spaceBetween:400,slidesPerView:2}},on:{init:function(){$('.banner-slider').removeClass('banner-slider_preview');},},})}






	//products sale-badges

	$(".discount-sale").parent(".sale-bage-wrap").parent(".product__item").css({
		"border": "1px solid rgba(244, 89, 5, 0.4)",
		"box-shadow": "0px 4px 15px 0px rgba(0, 0, 0, 0)"
	});

	$(".new-sale").parent(".sale-bage-wrap").parent(".product__item").css({
		"border": "1px solid rgba(42, 26, 94, 0.4)",
		"box-shadow": "0px 4px 15px 0px rgba(0, 0, 0, 0)"
	});

	$(".no-product ").parent(".no-product-wrap").parent(" .product__item").css({
		"pointer-events": "none",
		"z-index": "0",
	});

	$(".no-product ").parent(".no-product-wrap").parent(" .product__item").find('.btn-primary').css({
		"display": "none",
	});

	// if ($('.no-product-wrap').length) {
	// 	$('.product__image > img').css({
	// 	  "object-fit": "cover"
	// 	});
	// }



	//discount-card

	$(".product__item").each(function() {
		if ($(this).find(".discount-sale").length > 0) {
		  $(this).find(".product__cart__info").addClass("product__cart__info-discount");
		}
	});




	//remove blocks


	if ($('.page-404,.contacts,.about-us,.privacy-policy,.return-policy,.public-offer').length > 0) {
		$('.fix-cart').remove();
		$('.widget-btns').remove();
	}

	if ($('.cart-page').length > 0) {
		$('.fix-cart').remove();

		if( window.innerWidth >= 1025){
			$('main').css('overflow','initial');
		}
		else{
			$('main').css('overflow-x','hidden');
		}
	}





	// click  on header menu


	  $('.link-category').each(function() {
		var link = $(this);
		var href = link.attr('href');
		var currentPageURL = window.location.href;

		if (currentPageURL === href) {
		  link.addClass('link-category-active');
		}
	  });




	//quantity-input


	$(document).on('click', '.plus' , function() {
		const inp = $(this).parent().find('input');
		let inpValue = Number(inp.val());
		inp.val(inpValue + 1);
		inp.trigger('change');
	  });

	  $(document).on('click', '.minus', function() {
		const inp = $(this).parent().find('input');
		let inpValue = Number(inp.val());

		if (inpValue < 1) {
		  return false;
		}

		inp.val(inpValue - 1);
		inp.trigger('change');
	  });






	//select-custom


	$('.select-dropdown__button').on('click', function() {
		var dropdownList = $(this).siblings('.select-dropdown__list');

		$('.select-dropdown__list').not(dropdownList).removeClass('active');
		dropdownList.toggleClass('active');
	});

	$('.select-dropdown__list-item').on('click', function() {
		var itemValue = $(this).data('value');
		// console.log(itemValue);

		var parentDropdown = $(this).closest('.select-dropdown');
		var dropdownButton = parentDropdown.find('.select-dropdown__button span');

		dropdownButton.text($(this).text()).parent().attr('data-value', itemValue);

		$('.select-dropdown__list').removeClass('active');
	});






	//cart page delivery
	$(document).on('click', '.delivery-label input, .self-pickup-label input', handleRadioChange);

	function handleRadioChange() {
	  var deliveryLabel = $(this).closest('.delivery-label');
	  var selfPickupLabel = $(this).closest('.self-pickup-label');
	  var streetInput = deliveryLabel.find('.street');
	  var entranceInput = deliveryLabel.find('.entrance');
	  var apartmentInput = deliveryLabel.find('.apartment');

	  if (selfPickupLabel.is(':checked')) {
		streetInput.hide();
		entranceInput.hide();
		apartmentInput.hide();
	  } else {
		streetInput.show();
		entranceInput.show();
		apartmentInput.show();
	  }
	}




	//for tab and tel disription product

	var $customTooltip;
	var $lastTooltipElement;
	
	$(document).on('click', '.cart__detail[title]', function(e) {
	  e.stopPropagation();
	
	  if ($(window).width() < 768) {
		var tooltipText = $(this).attr('title');
		var $productItem = $(this).closest('.product__item');
	
		if ($customTooltip && $customTooltip.is(':visible') && $(this).is($lastTooltipElement)) {
		
		  $customTooltip.remove();
		  $customTooltip = null;
		  $lastTooltipElement = null;
		} else {
	
		  if ($customTooltip && $customTooltip.is(':visible')) {
			$customTooltip.remove();
		  }
	
	
		  $customTooltip = $('<div>')
			.attr('id', 'custom-tooltip')
			.addClass('custom-tooltip')
			.append($('<div>').addClass('tooltip-content').text(tooltipText));
	
		  $productItem.append($customTooltip);
		  $lastTooltipElement = $(this);
		  $customTooltip.show();
		}
	  }
	});
	
	$(document).click(function() {
	  if ($customTooltip && $customTooltip.is(':visible')) {
		$customTooltip.remove();
		$customTooltip = null;
		$lastTooltipElement = null;
	  }
	});
	
	$customTooltip && $customTooltip.click(function(e) {
	  e.stopPropagation();
	});

})




///product discription

$(document).ready(function() {

		///custom-select-language descctop

		$('.lang-desctop').on('click', function (e) {
			e.preventDefault();
			$(this).toggleClass('lang-desctop-active');
		});

		$('.lang-list a').on('click', function (e) {
			e.stopPropagation();
			const lang = $(this).data("lang");
			$(".lang-desctop-wrap a").removeClass("wpml-ls-current-language");
			$(`.lang-desctop-wrap a[data-lang="${lang}"]`).addClass("wpml-ls-current-language");

			$(".lang-list a").removeClass("wpml-ls-current-language");
			$(`.lang-list a[data-lang="${lang}"]`).addClass("wpml-ls-current-language");
		});


	var descriptionElement = $('.product-description');
	var descriptionText = descriptionElement.text();


	var lineHeight = parseFloat(descriptionElement.css('line-height'));
	var maxHeight = lineHeight * 1;

	if (descriptionElement.height() > maxHeight) {
	  while (descriptionElement.height() > maxHeight) {
		descriptionText = descriptionText.slice(0, -1);
		descriptionElement.text(descriptionText + '...');
	  }
	}




	$(document).on('click', '.fix-cart', toggleCart);
	$(document).on('click', '.cart-header', toggleCart);
	$(document).on('click', '.close-cart', toggleCart);
	$(document).on('click', '.added-success > .orange-btn', function() {
		toggleCart;
	  
		$('.added-success').removeClass('added-success-active');
	  });
	

	$(document).on('click', '.local', function() {
	if ($('.cart-modal').hasClass('cart-modal-active')) {
	  toggleCart();
	}
	});





	//close- cart calback
	var observer = new MutationObserver(function(mutationsList) {
		for (var mutation of mutationsList) {

		if (!$(mutation.target).hasClass('cart-modal-active')) {
			$('.header-center').removeClass('header-center-scroll');
		}
		}
	});

	observer.observe($('.cart-modal')[0], { attributes: true, attributeFilter: ['class'] });





	//we-got-success

	$('.resault-block .orange-btn').click(function(e) {
		e.preventDefault()
		$('.we-got-success').addClass('we-got-success-active');
		$('body').css('overflow', 'hidden');

	});

	$('.we-got-success').click(function() {
		$(this).removeClass('we-got-success-active');
		$('body').css('overflow', 'auto');
	});




	// togle for ask section


	$(".slide-header").click(function(e) {
		var icon = $(this).find("span");

		$(".slide-header").not(this).next().slideUp();
		$(".slide-header").not(this).removeClass("accordion-item-active").find("span").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");

		if (icon.hasClass("glyphicon-chevron-down")) {
		  $(this).addClass("accordion-item-active");
		  icon.removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
		} else {
		  $(this).addClass("accordion-item-active");
		  icon.removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
		}

		$(this).next().slideToggle(function() {
		  if (!$(this).is(":visible")) {
			$(this).prev().removeClass("accordion-item-active");
		  }
		});
	  });




	//modal city

	$(document).on('click', '.modal-city ul li a', function(event) {
		event.preventDefault();
		$('.modal-city ul li a').removeClass("active");
		$(this).addClass('active');

		var activeText = $(this).text();
		$('.select-wrap .city').text(activeText);
	});

	$('.modal-city .orange-btn').click(function(event) {
		event.preventDefault();
		$('.modal-city-wrap').addClass("modal-city-wrap-none");
		$('.modal-city-wrap').removeClass("modal-city-wrap-cart-active");

		// $('form').submit();
	});

	//modal city cart

	$('body').on('click', '.select-wrap', function(){
		$('.modal-city-wrap').addClass("modal-city-wrap-cart-active")
	});


	//modal citi in home page function

	// var modalShownTime = localStorage.getItem('modalShownTime');
    // var modalWrap = $('.modal-city-wrap');

    // if (!modalShownTime || new Date().getTime() - modalShownTime > 3600000) {
    //     modalWrap.removeClass('modal-city-wrap-none');
    //     localStorage.setItem('modalShownTime', new Date().getTime());
    // }

    // modalWrap.find('.modal-city .orange-btn').click(function(e) {
    //     e.preventDefault();

    //     modalWrap.addClass('modal-city-wrap-none');
    // });



	$('.sale-bage-wrap').click(function(e) {
		// e.stopPropagation()
		// e.stopImmediatePropagation()
		e.preventDefault()
		console.log('click')
	});
});



$(window).on('load resize', function() {



  // height $('.page-404')
  function setPageHeight() {
    var windowHeight = $(window).height();
    var headerHeight = $('.header').outerHeight();
    var footerHeight = $('.footer').outerHeight();

    var pageHeight = windowHeight - headerHeight - footerHeight;
    $('.page-404').css('min-height', pageHeight + 'px');
  }

  setPageHeight();

  $(window).resize(setPageHeight);



  if ($(window).width() <= 1024) {
	var $activeElement = $(".link-category-active");
	if ($activeElement.length === 0) {
	  return;
	}

	var containerWidth = $(".header__category ul").width();
	var activeElementWidth = $activeElement.outerWidth();
	var activeElementLeft = $activeElement.position().left;
	var scrollLeft = activeElementLeft - (containerWidth / 2) + (activeElementWidth / 2);

	$(".header__category ul").scrollLeft(scrollLeft);
  } else {
	$(".header__category ul").scrollLeft(0);
  }


});


  	//cart modal

	function toggleCart() {
		$('.cart-modal').toggleClass('cart-modal-active');
		$('body').toggleClass('overflow');
		$('.btns-wrap-header-menu').removeClass('btns-wrap-header-menu-active');


		if ($('.cart-modal').hasClass('cart-modal-active')) {
			$('.widget-btns').css("left", "-100%");
			$('#menu').removeClass("overlay");
			$('main').addClass('put-up');
			$('main').removeClass('for-main-overflow ');
			$('.header-center').addClass('header-center-scroll');
			$('.fix-cart').css('opacity', '0');
			$('.modal-search').removeClass('modal-search-active');
			$(".btn-open-search").css('display','block')
			$(".close-open-search").css('display','none')
		setTimeout(()=>{
			cartAdaptive()
		},200)

		} else {
			$('.widget-btns').css("left", "0");
			$('main').removeClass('put-up');
			$('.header-center').removeClass('header-center-scroll');
		}

	}


	//cart adaptive

	function cartAdaptive(){
		const headerHeight = Math.floor($("header").outerHeight());
		var resaulBottomHeight = $('.resaul-sum-wrap').outerHeight(true)
		const modatTitleHeight = $('.cart-title').outerHeight(true)
		var cartModalHeight = $(window).height() - headerHeight
		const productListHeight = cartModalHeight - (resaulBottomHeight + modatTitleHeight)

		$('body').css('padding-top', headerHeight + 'px');
		$('.cart-modal').outerHeight(cartModalHeight + 4 + 'px');
		$('.cart-modal').css('top', headerHeight + -4 +'px');
		// $('.cart-list').outerHeight(productListHeight - 125);
		// console.log($('.cart-list').outerHeight())
	}



	//click in page-404
	
	$('.cursor-pointer').click(function() {
		window.location.href = window.location.origin;
	});


	//fix cart total prise
	setInterval(function() {
		var count = parseInt($('.mini-cart-count').text());
		if (count > 0) {
		  $('.speech').addClass('speech-active');
		} else {
		  $('.speech').removeClass('speech-active');
		}
	  }, 1000);

	
	//for chat
	function checkElement() {
		if ($('.helpcrunch-iframe-wrapper iframe').length > 0) {
	
		  $('.header').css('z-index', 20);
		} else {
	
		  $('.header').css('z-index', 100);
		}
	}
	
	  
	$(window).on('load resize', function() {
		if ($(window).width() <= 768) {
			setInterval(checkElement, 500);
		}
	});



