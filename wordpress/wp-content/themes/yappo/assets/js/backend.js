function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

function setCookie(name, value) {
    let d = new Date();
    d.setTime(d.getTime() + 86400000 * 10);
    document.cookie = name + "=" + value + "; expires=" + d.toUTCString() + "; path=/";
}

jQuery(function ($) {
    window.dataLayer = window.dataLayer || [];

    $('body').on('added_to_cart', function (e, fragments, hash, button) {
        $('#added_product_name').text(button.data('name'));

        $('.added-success').addClass('added-success-active');
        setTimeout(function () {
            $('.added-success').removeClass('added-success-active');
        }, 4000);
    })

    $('.product-section .product-section-ajax-btn').click(function () {

        let button = $(this);

        let data = {
            'action': 'loadmore',
            'func': button.data('func'),
            'paged': Number(button.data('paged')) + 1,
        };

        if (button.data('category')) {
            data.category = button.data('category');
        }

        $.ajax({ // you can also use $.post here
            url: wc_add_to_cart_params.ajax_url, // AJAX handler
            data: data,
            type: 'POST',
            success: function (data) {
                console.log(data)
                let {posts = false} = data.data;
                const paged = Number(button.data('paged')) + 1;
                console.log(paged)
                button.data('paged', paged);

                if (posts) {
                    button.closest('.product-section').find('.row').append(posts); // insert new posts
                } else {
                    button.remove(); // if no data, remove the button as well
                }

                if (Number(button.data('max')) <= paged) button.remove();
            }
        });
    });

    $('body').on('change input', '.cart__preview-item .qty', (event) => {
        let cart_item_key = $(event.target).attr('name').replace(/cart\[([\w]+)\]\[qty\]/g, "$1");
        let item_quantity = parseFloat($(event.target).val());
        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: {action: 'update_item_from_cart', 'cart_item_key': cart_item_key, 'qty': item_quantity,},
            success: function (data) {
                if (data.fragments) {

                    $.each(data.fragments, function (key, value) {
                        $(key).replaceWith(value);
                    });

                }
                // $(document.body).trigger('wc_fragment_refresh');
            }
        });
    });

    $('.added-success .orange-btn').click(function () {
        if (toggleCart) {
            toggleCart()
        }
        $('.added-success').removeClass('added-success-active');
    });

    // $("body").on('wc_fragments_refreshed', cartAdaptive);

    $("#city-chooser button").on('click', function (e) {
        e.preventDefault();
        const cityid = $(this).closest('#city-chooser').find('a.active').data('id');
        const cityAddress = $(this).closest('#city-chooser').find('a.active').data('address');
        console.log(cityAddress);
        setCookie('choosedcity', cityid);
        setCookie('choosedaddress', cityAddress);
        window.location.reload();
    })

    function checkoutFields(e){
        if ($('.shipping_method:checked').val() == 'local_pickup:2') {
            $('#billing_address_1_field, #billing_address_2_field, #billing_address_3_field').hide();
        } else {
            $('#billing_address_1_field, #billing_address_2_field, #billing_address_3_field').show();
        }
    }

    $('.shipping_method:checked').on('change', checkoutFields);


    $('body').on('updated_checkout', function () {
        checkoutFields();
    })

    $("#city-chooser ul li a").on('click', function () {
        $("#city-chooser .orange-btn").removeAttr('disabled');
    })

    if (!getCookie('choosedcity')) {
        $('.modal-city-wrap').removeClass('modal-city-wrap-none');
    }

    $('body').on('update_checkout', function () {
        // $("#billing_phone").mask("+38 (999) 999-99-99", {placeholder: "+38 (___) ___-__-__"});
		$('#billing_phone').mask("+38 (?99) 999-99-99", {
			translation: {
				'?': {
					pattern: 0,
					fallback: '0'
				},
			},
			placeholder: "+38 (0__) ___-__-__",
		})
    })

    $('#shipping_method .delivery-label').on('click', function () {
        $(this).find('input').prop("checked", true).trigger('change');
    })

    $('.city-list a').on('click', function (e) {
        e.preventDefault();
        let cityid = $(this).data('id');
        let cityAddress = $(this).data('address');
        setCookie('choosedcity', cityid);
        setCookie('choosedaddress', cityAddress);
        if($(this).attr("href")){
            window.location.href = $(this).attr("href");
        } else {
            window.location.reload()
        }
    })

    $(document.body).on('added_to_cart', function (e, fragments, cart_hash, button) {
        let item = JSON.parse($(button).data('seo'));

        if ($(button).data('index')) {
            item.index = $(button).data('index');
        }

        if (button.hasClass('single_add_to_cart_button')) {
            const quantity = $('form.cart input[name="quantity"]').val() || 1;
            item = PrepareSeoItem($(button).data('seo'), {
                quantity: Number(quantity),
            });
        }

        window.dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        window.dataLayer.push({
            event: "add_to_cart",
            ecommerce: {
                items: [item]
            }
        });
    });

    function PrepareSeoItem(data, changedata = {}) {
        let item = JSON.parse(data);
        item.price = Number(item.price);
        item.item_name = item.item_name.trim();
        item.quantity = Number(item.quantity);
        return {...item, ...changedata};
    }

    $(document.body).on('removed_from_cart', function (e, fragments, cart_hash, button) {
        let item = JSON.parse($(button).data('seo'));

        window.dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        window.dataLayer.push({
            event: "remove_from_cart",
            ecommerce: {
                items: [item]
            }
        });
    });

    $('.product-list-cart .remove-button').on('click', function () {
        let item = JSON.parse($(this).data('seo'));

        window.dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        window.dataLayer.push({
            event: "remove_from_cart",
            ecommerce: {
                items: [item]
            }
        });
    })

    $('body').on('click', '.product__item', function (e) {
        let item = JSON.parse($(this).closest('.product__item').find('.add_to_cart_button').data('seo'));
        window.dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        window.dataLayer.push({
            event: "select_item",
            ecommerce: {
                items: [item]
            }
        });

        window.dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        window.dataLayer.push({
            event: "view_item",
            ecommerce: {
                items: [item]
            }
        })
    })

    $('.product-top .qty').on('change', function () {
        $('.single_add_to_cart_button').attr('data-quantity', $(this).val());
    })

    $.fn.serializeArrayAll = function () {
        var rCRLF = /\r?\n/g;
        return this.map(function () {
            return this.elements ? jQuery.makeArray(this.elements) : this;
        }).map(function (i, elem) {
            var val = jQuery(this).val();
            if (val == null) {
                return val == null
                //next 2 lines of code look if it is a checkbox and set the value to blank
                //if it is unchecked
            } else if (this.type == "checkbox" && this.checked === false) {
                return {name: this.name, value: this.checked ? this.value : ''}
                //next lines are kept from default jQuery implementation and
                //default to all checkboxes = on
            } else if (this.type === 'radio') {
                if (this.checked) {
                    return {name: this.name, value: this.checked ? this.value : ''};
                }
            } else {
                return jQuery.isArray(val) ?
                    jQuery.map(val, function (val, i) {
                        return {name: elem.name, value: val.replace(rCRLF, "\r\n")};
                    }) :
                    {name: elem.name, value: val.replace(rCRLF, "\r\n")};
            }
        }).get();
    };

    $('body').on('click', '.single_add_to_cart_button:not(.disabled)', function (e) {

        var $thisbutton = $(this),
            $form = $thisbutton.closest('form.cart'),
            //quantity = $form.find('input[name=quantity]').val() || 1,
            //product_id = $form.find('input[name=variation_id]').val() || $thisbutton.val(),
            data = $form.find('input:not([name="product_id"]), select, button, textarea').serializeArrayAll() || 0;

        $.each(data, function (i, item) {
            if (item.name == 'add-to-cart') {
                item.name = 'product_id';
                item.value = $form.find('input[name=variation_id]').val() || $thisbutton.val();
            }
        });

        console.log(data)

        e.preventDefault();

        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({
            type: 'POST',
            url: woocommerce_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {

                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
            },
        });

        return false;

    });
})
