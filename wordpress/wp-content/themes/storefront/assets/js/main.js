var $ = jQuery;

function isWebp() {
    function testWebP(callback) {
        var webP = new Image();
        webP.onload = webP.onerror = function () {
            callback(webP.height == 2);
        };
        webP.src =
            "data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA";
    }

    testWebP(function (support) {
        if (support == true) {
            document.querySelector("html").classList.add("webp");
        } else {
            document.querySelector("html").classList.add("no-webp");
        }
    });
}

isWebp();

// Swiper.use([Mousewheel, Pagination]);
const swiper = new Swiper(".slider", {
    pagination: true,
    slidesPerView: 1,
});
const footetLogo = document.querySelector(".footer__logo");
const footerSocial = document.querySelector(".footer__social");
const headerLogo = document.querySelector(".header__logo");
if (window.innerWidth <= 500) {
    footetLogo.remove();
    document.querySelector(".footer__top").appendChild(footetLogo);
    headerLogo.remove();
    document.querySelector(".header__top").prepend(headerLogo);
    // footerSocial.remove();
    // document.querySelector(".footer__top").prepend(footerSocial);
}

document
    .querySelectorAll(".product__item .product__cart .btn")
    .forEach((btn) => {
        btn.addEventListener("click", ($event) => {
            var productId = $($event.target).data('target');
            document.querySelector(".cart__wrapper.id-" + productId).classList.add("active");
            let item = JSON.parse($($event.target).data('seo'));
            jQuery(document.body).trigger('wc_fragment_refresh');

            window.dataLayer = window.dataLayer || [];
            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "select_item",
                ecommerce: {
                    items: [item]
                }
            });

            window.dataLayer = window.dataLayer || [];
            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "view_item",
                ecommerce: {
                    items: [item]
                }
            })
        })
    });

$(document.body).on('added_to_cart', function (e, fragments, cart_hash, button) {
    let item = JSON.parse($(button).data('seo'));
    item.quantity = $('.cart__wrapper.active .product-popup-quantity input[type=number]').val();

    window.dataLayer = window.dataLayer || [];
    dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
    dataLayer.push({
        event: "add_to_cart",
        ecommerce: {
            items: [item]
        }
    });
});

$(document.body).on('removed_from_cart', function (e, fragments, cart_hash, button) {
    let item = JSON.parse($(button).data('seo'));

    window.dataLayer = window.dataLayer || []
    dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
    dataLayer.push({
        event: "remove_from_cart",
        ecommerce: {
            items: [item]
        }
    });
});

jQuery(".product__item").on("click", function () {
    const targetId = jQuery(this).data("target");
    jQuery(".id-" + targetId).addClass("active");
})

document.querySelectorAll(".cart__item .close").forEach((btn) => {
    btn.addEventListener("click", ($event) => {
        var productId = $event.target.offsetParent.classList[1];
        document.querySelector(".cart__wrapper.id-" + productId).classList.remove("active");
    });
});
document.querySelector(".cart").addEventListener("click", () => {
    document.querySelector(".cart__preview").classList.toggle("active");
});

function show_error($field, $mesg) {
    if ($field.prev('.error_msg').length) {
        $field.prev('.error_msg').html('<p>' + $mesg + '</p>');
    } else {
        jQuery('<div class="error_msg" style="color:#f00"><p>' + $mesg + '</p></div>').insertBefore($field);
    }

}


function remove_error($field) {
    if ($field.prev('.error_msg').length) {
        $field.prev('.error_msg').remove();
    }
}

function showMore(item) {
    var childItems = [...item.parentNode.parentNode.children[1].children];
    childItems.filter((child) => child.classList[1] === 'hidden').forEach(child => {
        child.classList.remove('hidden')
    })
    item.parentNode.classList.add('hidden');
}

jQuery(".quantity input[name=quantity]").on('change', function (e) {

    if (jQuery(this).val() > jQuery(this).attr("max")) {
        show_error(jQuery(this).parent(".quantity"), "Your selected order quantity is greater than our existing stock. Please expect a delay of up to 2 weeks for our stock to be replenished")
    } else {
        remove_error(jQuery(this).parent(".quantity"));
    }
})
jQuery(document).ready(function () {
    jQuery(document).on('click', '.cart__preview-item .quantity .minus', (el) => {
        changeQuantity(el, 'decrease', () => updateMiniCartQuantity())
        return false;
    });
    jQuery(document).on('click', '.cart__preview-item .quantity .plus', (el) => {
        changeQuantity(el, 'increase', () => updateMiniCartQuantity())
        return false;
    });
});

function updateMiniCartQuantity() {
    document.querySelector('.spinner').classList.toggle('active');
    var cartForm = jQuery('.shop_table.cart form');
    jQuery('<input />').attr('type', 'hidden')
        .attr('name', 'update_cart')
        .attr('value', 'Update Cart')
        .appendTo(cartForm);

    var formData = cartForm.serialize();
    jQuery.ajax({
        type: cartForm.attr('method'),
        url: cartForm.attr('action'),
        data: formData,
        dataType: 'html',
        success: function (response) {

            let wc_cart_fragment_url = (wc_cart_fragments_params.wc_ajax_url).replace("%%endpoint%%", "get_refreshed_fragments");
            jQuery.ajax({
                type: 'post',
                url: wc_cart_fragment_url,
                success: function (response) {
                    // console.log(response);
                    // var mini_cart_wrapper = jQuery('.widget_shopping_cart_content');
                    // var parent = mini_cart_wrapper.parent();
                    // mini_cart_wrapper.remove();
                    // parent.append(response.fragments['div.widget_shopping_cart_content']);
                    jQuery(document.body).trigger('wc_fragment_refresh');
                },
                complete: function () {
                    cartForm = jQuery('.shop_table.cart form');
                    document.querySelector('.spinner').classList.toggle('active');
                }
            });
        }
    });
}

function changeQuantity($event, type, callback = null) {
    var item;
    if ($event.srcElement) {
        item = $event.srcElement;
    } else {
        item = $event.target;
    }
    const childItems = [...item.parentNode.children];
    childItems.forEach(item => {
        if (item.localName === 'input') {
            item.value = ((type === 'increase') ? Number(item.value) + 1 : Number(item.value) - 1);
            const id = item.id.split('-')[1];
            if (id) {
                updateQuantity(item.value, id);
            }
            item.dataset.quantity = item.value;
            if (item.value < 1) {
                item.value = 1
            }
            if (callback) {
                callback()
            }
        }
    })
}

function updateQuantity(value, id) {
    document.querySelector('#add-to-cart-' + id).dataset.quantity = value;
}

document.querySelectorAll('.quantity-increase').forEach((item) => item.addEventListener("click", ($event) => changeQuantity($event, 'increase')))

document.querySelectorAll('.quantity-decrease').forEach((item) => item.addEventListener("click", ($event) => changeQuantity($event, 'decrease')))


document.querySelectorAll('.add_to_cart_button')
    .forEach((item) => item.addEventListener("click", () => showAddToCartNotification()))

function showAddToCartNotification() {
    const item = document.querySelector('.add-to-cart-notification');
    item.classList.toggle('active');
    setTimeout(() => {
        item.classList.toggle('active');
    }, 4000)
}


// Initialize and add the map
function initMap() {
    // The location of Uluru
    //50.554190190342496, 30.212766115341417
    const uluru = {lat: 50.554190190342496, lng: 30.212766115341417};
    // The map, centered at Uluru
    window.map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: uluru,
    });
    // The marker, positioned at Uluru
    window.marker = new google.maps.Marker({
        position: uluru,
        map: map,
        icon: 'https://yappo.softgrowth.dev/wp-content/themes/storefront/assets/images/main/marker.svg'
    });
}

window.initMap = initMap;


function newLocation(newLat, newLng) {
    map.setCenter({
        lat: newLat,
        lng: newLng
    });
    window.marker.setOptions({
        position: {
            lat: newLat,
            lng: newLng
        }
    });
}

// Add additional product to cart

jQuery('#number-of-people').change(function (e) {
    var qty = $(this).val();
    var product_id = $(this).data('product_id');
    var cart_item_key = $(this).data('cart_item_key');
    var security = $('#update_cart_nonce').val();

    var data = {
        action: 'woocommerce_update_cart',
        qty: qty,
        product_id: 470,
        cart_item_key: cart_item_key,
        security: security
    };

    $.post(wc_cart_fragments_params.ajax_url, data, function (response) {
        if (response && response.fragments) {
            $.each(response.fragments, function (key, value) {
                $(key).replaceWith(value);
            });
        }
    });
});

function addToCart(p_id) {
    jQuery.get('/?wc-ajax=add_to_cart', function () {
        // call back
    });
}

jQuery("#billing_person_count").attr({
    "max": 15,        // substitute your own
    "min": 1          // values (or variables) here
});
jQuery("#billing_phone").attr({
    "max": 15,        // substitute your own
    "min": 15,
});
// const billingPhone = document.getElementById('billing_phone');
// if (billingPhone) {
//   const classList = billingPhone.classList.forEach((item) => {
//     console.log('item')
//   })
// }
// jQuery("#billing_phone").prop('autocomplete', 'off');
// jQuery("#billing_phone").on("input", (atr)=> {
//   console.log('Change')
//   let unvalid = false;
//   const element = atr.target;
//   console.log(element.value.length);
//   if (element.value.length <= 14) {
//     jQuery(element).addClass("input-unvalid");
//     unvalid = true;
//   }
//   // element.classList.forEach((className) => {
//   //   if (className === 'input-unvalid'){
//   //     unvalid = true;
//   //   }
//   // });
//
//   // jQuery("#place_order").disable = unvalid;
//   jQuery("#place_order").prop('disabled', unvalid);
// })

jQuery(document).ready(function () {

    //scroll header

    jQuery(window).on("scroll", function () {
        var height = jQuery(window).scrollTop();

        if (height > 0) {
            jQuery('.header__top').addClass('header-top-active');

        } else {
            jQuery('.header__top').removeClass('header-top-active');

        }
    });


    jQuery('.product__cart .btn-primary').on('click', function () {
        jQuery('body').addClass('overflow-body');

    })

    jQuery('.cart__item .close').on('click', function () {
        jQuery('body').removeClass('overflow-body');
    })


    $('.cart__wrapper').on('click', function (e) {
        if ($(e.target).is('.cart__wrapper'))
            $(this).removeClass('active');
    })


    // jQuery('.cart__wrapper').on('click', function () {
    //     if (jQuery(this).hasClass('active')) {
    //         jQuery('body').removeClass('overflow-body');
    //         jQuery(this).removeClass('active')
    //     }
    // })


    jQuery("body").css("margin-top", jQuery(".header").outerHeight() + "px");

});

