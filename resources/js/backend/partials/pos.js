$(document).ready(function () {
    if (location.pathname == '/staff/pos') {
        var audio = new Audio('/build/assets/backend/audio/beep.wav');

        // Get Category data
        const getCategory = () => {

            $.ajax({
                type: 'GET',
                url: route('pos.category'),
                dataType: 'HTML',
                success: function (response) {
                    $('#category-carousel').html(response);
                    $("#category-carousel").slick({
                        dots: false,
                        infinite: true,
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        responsive: [
                            {
                                breakpoint: 1550,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4,
                                    infinite: true,
                                },
                            },
                            {
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 5,
                                    slidesToScroll: 5,
                                    infinite: true
                                },
                            },
                            {
                                breakpoint: 993,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4,
                                    infinite: true
                                },
                            },
                            {
                                breakpoint: 769,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                },
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2,
                                },
                            }
                        ],
                    });
                },
                error: (err) => handleError(err)
            });
        }

        getCategory()

        // Get menu is conditionally
        const getMenu = (url) => {

            $('#allFoods').empty()
            console.log(url)
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'HTML',
                beforeSend: () => $('.pos-menu-loader').removeClass('d-none'),
                success: function (response) {
                    $('#allFoods').html(response)
                    productView();
                },
                complete: () => $('.pos-menu-loader').addClass('d-none'),
                error: (err) => handleError(err)
            });
        }
        window.getMenu = getMenu;

        getMenu(route('pos.menu.index'))



        // Product view change
        const productView = () => {
            const product_view = localStorage.getItem("product_view");

            if (product_view != null) {
                let images = document.querySelectorAll(".product-image");
                if (product_view == "list") {
                    images.forEach((image) => image.classList.add("d-none"));
                } else {
                    images.forEach((image) => image.classList.remove("d-none"));
                }

                $.each($('input[name="productView"]'), function (key, value) {
                    if (product_view == $(value).val()) $(value).attr("checked", true);
                });
            }
        };

        productView();



        $(document).on("click", 'input[name="productView"]', function (e) {
            localStorage.setItem("product_view", e.target.value);
            productView();
        });

        // Filter menu by category
        $(document).on('click', '#filterByCategory', function (e) {
            e.preventDefault();

            const id = $(this).data('id');
            const keyword = $('#searchItems').val();

            $('.category-section .btn').removeClass('active');
            $(this).addClass('active');

            const query = {
                category: id,
                keyword: keyword
            }
            getMenu(route('pos.menu.index', { _query: query }));
        });

        // Search menu
        $(document).on('input', 'input#searchItems', function(e) {
            const category = $('#category-carousel #filterByCategory.active').data('id');
            const query = {
                category: category,
                keyword: e.target.value
            }
            getMenu(route('pos.menu.index', { _query: query }));
        });

        // Show menu details by specific resources
        $(document).on('click', '#menu-details', function (e) {
            e.preventDefault();

            const url = $(this).attr('href');

            // audio.play();

            $('#modal').remove();
            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'HTML',
                success: function (response) {
                    $('.page-content').append(response);
                    $("#modal").modal("show");
                }
            });
        });

        // Get variant wise menu details
        $(document).on('change', 'select#variant', function(e) {
            const value = e.target.value;
            const food_id = $('input#food_id').val();

            $.ajax({
                type: 'GET',
                url: route('pos.menu.variant', { food: food_id, variant: value }),
                dataType: 'JSON',
                success: function (response) {
                    console.log(response);

                    let html;
                    $.each(response.data.variants.data, function (key, value) {
                        html += `<option value="${value.id}" ${value.id == response.data.variant.id ? 'selected':''}>${value.name}</option>`;
                    });

                    $('#modal select#variant').html(html);
                    $('#modal #variant_price').text(convertAmount(response.data.variant.price));
                }
            });
        });

        // Get cart items
        const getCartData = () => {
            $.ajax({
                type: 'GET',
                url: route('pos.cart.index'),
                dataType: 'HTML',
                success: function (response) {
                    $('#cartItems').html(response);

                    const subtotal       = $('input#cart_subtotal').val();
                    const service_charge = $('input#cart_service_charge').val();
                    const discount       = $('input#cart_discount').val();
                    const grandtotal     = $('input#cart_grandtotal').val();

                    $('#cart_subtotal_amount').text(convertAmount(subtotal));
                    $('#cart_service_charge_amount').text(convertAmount(service_charge));
                    $('#cart_discount_amount').text(convertAmount(discount));
                    $('#cart_total_amount').text(convertAmount(grandtotal));
                }
            });
        }

        window.getCartData = getCartData;

        getCartData();


        // Remove item from cart
        $(document).on('click', '#removeCartItem', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');

            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    _token: csrf_token
                },
                dataType: 'JSON',
                success: () => getCartData()
            });
        });

        // Update cart quantity
        $(document).on('click', '#update-qty', function () {
            console.log('Update quantity');
            let role  = $(this).data('role');
            let key   = $(this).data('key');
            let input = $(this).siblings('input')[0];
            let currentVal = parseInt($(input).val());

            if(typeof key == 'undefined') {
                if(role == 'plus' && currentVal >= 0) currentVal++;
                else if (role == 'minus' && currentVal >= 1) currentVal--;
                $(input).val(currentVal);
            }
            else {
                if((role == 'plus' && currentVal >= 1) || (role == 'minus' && currentVal > 1)) {
                    $.ajax({
                        type: 'POST',
                        url: route('pos.cart.update', { id: key }),
                        data: {
                            _token: csrf_token,
                            _method: 'PUT',
                            role: role
                        },
                        dataType: 'JSON',
                        success: () => getCartData(),
                        error: (err) => handleError(err)
                    });
                }
            }
        });

        // Get Table information
        $(document).on('click', '.tab-btn', function(e) {
            e.preventDefault();
            $('.tab-btn').removeClass('active');
            $(this).addClass('active');

            const type = $(this).data('type');
            $('input#order_type').val(type);

            if(type == 'Table') {
                $('input#order_type').val('');
            }
        });

        // Process Table without choice tables
        $(document).on('click', '#process_without_table', function() {
            $('input[name="process_without_table"]').val(1);
            $('#modal').modal('hide');
        });

        $(document).on('keyup', 'form input.table__person', function() {

            const max = parseInt($(this).attr('max'));
            const val = parseInt($(this).val());

            $(this).removeClass('is-invalid');
            $(this).siblings('.form-text').text('');

            if(val > max) {
                $(this).addClass('is-invalid');
                $(this).siblings('.form-text').text(`Available sit ${max}, you are enter ${val}.`);
            }
        });

        // Get customer
        const getCustomer = () => {
            const cart_update = JSON.parse($('input#cart_update').val())

            $.ajax({
                type: 'GET',
                url: route('pos.customer.index'),
                dataType: 'JSON',
                success: function (response) {
                    let html = `<option value="">Select Customer</option>`;
                    $.each(response.data, function (key, user) {
                        html += `<option value="${user.id}"${cart_update != null && user.id == cart_update.user_id ? 'selected':''}>${user.name}</>`;
                    });

                    $('select#customer').html(html).select2()
                }
            });
        }

        // getCustomer();

        window.getCustomer = getCustomer;
        // window.checkCartUpdate = checkCartUpdate;

        // Customer show or edit
        const customerShowOrEdit = (routeName, btn) => {

            $('#modal').remove();
            const customer = $('select#customer').val();

            if(customer != '') {
                $.ajax({
                    type: 'GET',
                    url: route(routeName, customer),
                    dataType: 'HTML',
                    beforeSend: () => $(btn).addClass('disabled'),
                    success: (response) => {
                        $('.page-content').append(response);
                        $("#modal").modal("show");
                    },
                    complete: () => $(btn).removeClass('disabled'),
                    error: (err) => handleError(err)
                });
            }
            else {
                toastr.clear();
                toastr.error("Please select customer.", "Wrong!");
            }
        }

        // Edit customer
        $(document).on('click', '#customerShow', function(e) {
            e.preventDefault();
            customerShowOrEdit('pos.customer.show', $(this))
        });

        // Edit customer
        $(document).on('click', '#customerEdit', function(e) {
            e.preventDefault();
            customerShowOrEdit('pos.customer.edit', $(this))
        });

        // Order processing
        $(document).on('click', '#orderData', function(e) {
            e.preventDefault();
            const url    = $(this).attr('href');
            const update = $(this).data('update');
            const btn = $(this);
            // const order_type = $('input#order_type').val();
            const customer   = $('select#customer').val();
            const process_without_table = parseInt($('input[name="process_without_table"]').val());
            // const table_id = $('#table_id').val();

            const orderTypes = ['Delivery'];

            let data = {
                // order_type: order_type,
                customer: customer,
                // table_id: table_id,
                process_without_table: process_without_table
            }
            if(update == 1) data._method = 'PUT'

            // audio.play();

            // if(order_type == '') {
            //     toastr.error('Please select a valid order type', 'Something wrong');
            // }
            // else if(order_type == 'Dine In') {
            //     toastr.error('Please add table for process to order.', 'Something wrong');
            // }
            processOrder(url, data, btn);

            // else if(orderTypes.includes(order_type)) {

            //     if(customer == '' && order_type == '') {
            //         toastr.error('Please select customer & order type', 'Something wrong');
            //     }
            //     else if(customer == '' && order_type != '') {
            //         toastr.error('Please select customer', 'Something wrong');
            //     }
            //     else if(customer != '' && order_type == '') {
            //         toastr.error('Please select order type', 'Something wrong');
            //     }
            //     else  {
            //         processOrder(url, data, btn);
            //     }
            // }
            // else {
            //     processOrder(url, data, btn);
            // }
        });

        const processOrder = (url, data, btn) => {

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'JSON',
                beforeSend: () => $(btn).addClass('disabled'),
                success: (response) => {
                    handleSuccess(response)
                    $('input#cart_update').val(JSON.stringify(null))
                    checkCartUpdate();
                },
                complete: (xhr, status) => {
                    $(btn).removeClass('disabled');
                    if (status === 'success') {
                        const response = xhr.responseJSON;
                        if (response.message === 'Order added successfully') {
                            const orderId = response.order_id;
                            const orderDiscount = response.order_discount;
                            const orderGrandTotal = response.order_grand_total;
                            const customerId = response.customer_id;
                            const finalize_order = response.finalize_order;
                            const customerWallet = response.customer_wallet;
                            openPaymentModal(orderId, orderDiscount, orderGrandTotal, finalize_order, customerId, customerWallet);
                        } else {
                            // Handle other cases if needed
                            console.error(response.message);
                        }
                    }
                },
                error: (err) => handleError(err)
            });

            
        }

        function openPaymentModal(orderId, orderDiscount, orderGrandTotal, finalize_order, customerId, customerWallet) {
            const paymentModal = $('#paymentModal');
            paymentModal.find('#order_id').val(orderId);
            paymentModal.find('#discount_amount').val(orderDiscount);
            paymentModal.find('#give_amount').val(orderGrandTotal);
            paymentModal.find('#payable_amount').html(`<div>${orderGrandTotal}</div>`);
            paymentModal.find('#payable_amount_hidden').val(orderGrandTotal);
            paymentModal.find('#customer_id').val(customerId);
            paymentModal.find('#customer_wallet').html(customerWallet);
            // paymentModal.find('#finalize_order').val(finalize_order);
            paymentModal.find('#finalize_order').attr('value', JSON.stringify(finalize_order));
            paymentModal.modal('show');
        }

        // Get Order list
        const getOrder = () => {

            $.ajax({
                type: 'GET',
                url: route('pos.order.index'),
                dataType: 'HTML',
                success: function (response) {
                    $('#order-carousel').html(response);
                    $('#order-carousel2').html(response);

                    // $('.order-processing-section a.btn').addClass('disabled');
                    // $('a.pay-btn, a#printBtn').addClass('disabled');
                }
            });
        }
        window.getOrder = getOrder;

        getOrder();


        // $(document).on('click', '#order-item', function() {
        //     $('.order-items .order-item').removeClass('active');
        //     $(this).addClass('active');
        //     const id = $(this).data('id');

        //     $('.order-processing-section a.btn').removeClass('disabled');
        //     $('.order-processing-section a.show-order-details').attr('href', route('pos.order.show', id));
        //     $('.order-processing-section a.show-kitchen-details').attr('href', `${route('pos.order.show', id)}?kitchen=1`);
        //     $('.order-processing-section a#cancelOrder').attr('href', route('pos.order.cancel', id));
        //     $('.order-processing-section a#editOrder').attr('href', route('pos.order.edit', id));
        //     $('a.pay-btn').removeClass('disabled').attr('href', route('pos.order.show', id));
        //     $('a#printBtn').removeClass('disabled').attr('href', route('pos.order.print', id));

        // });

        //
        $(document).on('click', 'a#printBtn', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            window.open(url, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
        });

        // Edit order
        $(document).on('click', '#editOrder', function(e) {
            e.preventDefault();

            const url = $(this).attr('href');
            const btn = $(this);

            swal({
                title: "Are you sure?",
                text: "You want edit this order....!",
                type: "error",
                showCancelButton: true,
                confirmButtonText: "Edit",
                padding: "2em",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        dataType: 'JSON',
                        beforeSend: function () {
                            $(btn).addClass("disabled");
                        },
                        success: function (response) {
                            getCartData();
                            $('input#cart_update').val(JSON.stringify(response))
                            checkCartUpdate();
                        },
                        complete: function () {
                            $(btn).removeClass("disabled");
                        },
                        error: function (e) {
                            handleError(e);
                        },
                    });
                }
            });
        });

        const checkCartUpdate = () => {
            const cart_update = JSON.parse($('input#cart_update').val())
            if(cart_update != null) {
                $('#orderData').attr('href', route('pos.order.update', cart_update.order_id)).attr('data-update', 1)
                $('#orderData p').text('Update');
            }
            else {
                $('#orderData').attr('href', route('pos.order.store')).attr('data-update', 0)
                $('#orderData p').text('Order');
            }
            getCustomer();
            // console.log('hello');
        }

        checkCartUpdate()


        // Cancel a specified order
        $(document).on('click', 'a#cancelOrder', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const btn = $(this);

            swal({
                title: "Are you sure?",
                text: "You want cancel this order...!",
                type: "error",
                showCancelButton: true,
                confirmButtonText: "Cancel",
                padding: "2em",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        dataType: 'JSON',
                        beforeSend: function () {
                            $(btn).addClass("disabled");
                        },
                        success: function (response) {
                            toastr.success(response, "Success!");
                            getOrder()
                        },
                        complete: function () {
                            $(btn).removeClass("disabled");
                        },
                        error: (err) => handleError(err)
                    });
                }
            });
        });

        const getOnlineOrder = () => {
            $.ajax({
                type: 'GET',
                url: route('pos.order.online'),
                dataType: 'HTML',
                success: function (response) {
                    $('header #onlineOrders').html(response);
                    $('header #online-order-count').text($('#onlineOrders .dropdown-item').length);
                }
            });
        }
        window.getOnlineOrder = getOnlineOrder;

        getOnlineOrder();


        // Accespt pending/online orders
        $(document).on('click', '#acceptOrder', function(e) {
            e.preventDefault();
            const btn = $(this);
            const url = $(this).attr('href');
            swal({
                title: "Are you sure?",
                text: "You want accept this order...!",
                type: "error",
                showCancelButton: true,
                confirmButtonText: "Accept",
                padding: "2em",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            _method: 'PUT',
                        },
                        dataType: 'JSON',
                        beforeSend: () => $(btn).addClass('disabled'),
                        success: (response) => handleSuccess(response),
                        complete: () => $(btn).addClass('disabled'),
                        error: (err) => handleError(err)
                    });
                }
            });
        });


        // Finalize Order Payment
        $(document).on('change', 'input#use_rewards, input#include_service_charge, input#customer_special_discount, select#discount_type', function () {
            calculateFinalAmount()
        });

        $(document).on('keyup', '#give_amount, input#discount_amount', function () {
            calculateFinalAmount()
        });
        const calculateFinalAmount = () => {
            
            const use_rewards = $('input#use_rewards').is(':checked');
            const include_service_charge    = $('input#include_service_charge').is(':checked');
            const customer_special_discount = $('input#customer_special_discount').is(':checked');
            const give_amount     = parseFloat($('input#give_amount').val());
            const discount_aamount = parseFloat($('input#discount_amount').val());
            const discount_type   = $('select#discount_type').val();
            
            const { grand_total, reward_amount, service_charge, special_discount, discount_amount } = JSON.parse($('input[name="finalize_order').val());
            
            let payable_amount = grand_total + parseFloat(discount_amount);

            if(discount_type == 'fixed') {
                payable_amount -= discount_aamount;
            }
            else {
                payable_amount -= (discount_aamount / 100) * payable_amount;
            }

            if(customer_special_discount == true) {
                payable_amount -= (special_discount / 100) * payable_amount;
            }
            if(use_rewards == true) {
                payable_amount -= reward_amount;
            }
            if(include_service_charge != true) {
                payable_amount -= service_charge;
            }

            if(give_amount != '' && give_amount != 0) {
                if(give_amount >= payable_amount){
                    $('#due_amount').val(0)
                    $('#change_amount').val((give_amount - payable_amount).toFixed(2))
                }
                else{
                    $('#change_amount').val(0)
                    $('#due_amount').val((payable_amount - give_amount).toFixed(2))
                }
            }
            else if(give_amount == 0){
                $('#due_amount').val(payable_amount)
                $('#change_amount').val(0)
            }
            $('#payable_amount').text(convertAmount(payable_amount))
        }
    }

});
