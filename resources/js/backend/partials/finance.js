$(document).ready(function () {
    /* Selected file type */
    $(document).on('change', 'select#ingredient', function() {
        let $this = $(this);
        let val   = $(this).val();
        let role  = $(this).data('role');

        const ids = $('td #ingredient_id');
        const checkIds = [];
        $.each(ids, function (index, id) {
            checkIds.push($(id).val())
        });

        if (checkIds.includes(val)) {
            swal("Alert!", "Ingredient already remains in cart, you can change Quantity/Amount", "warning");
        }
        else {
            if(val != '') {
                $.ajax({
                    type: 'GET',
                    url: route('purchase.ingredient', {ingredient: val}),
                    dataType: 'JSON',
                    success: function (response) {
                        let item = '';
                        if(role !== 'undefined' && role == 'stock-adjusment') {
                            item = `<tr>
                                        <td>${ids.length + 1}</td>
                                        <td>
                                            ${response.name}(${response.code})
                                            <input type="hidden" name="ingredient_id[]" id="ingredient_id" value="${response.id}" />
                                        </td>
                                        <td class="d-flex justify-content-start align-items-center" id="quantity">
                                            <input type="text" class="form-control" name="quantity_amount[]" id="quantity_amount" value="1" placeholder="Unit Price" style="width:80%" />
                                            <span class="ms-2">${response.unit.name}</span>
                                        </td>
                                        <td>
                                            <select name="consumption_status[]" id="consumption_status" class="form-control">
                                                <option value="">Select</option>
                                                <option value="plus">Plus</option>
                                                <option value="minus">Minus</option>
                                            </select>
                                        </td>
                                        <td>
                                            <a href="#" class="btn primary-text" id="removeIngredientItem"><i class='icofont-ui-delete'></i></a>
                                        </td>
                                    </tr>`;
                            $('#items').append(item);
                        }
                        else {
                            item = `<tr>
                                        <td>${ids.length + 1}</td>
                                        <td>
                                            ${response.name}(${response.code})
                                            <input type="hidden" name="ingredient_id[]" id="ingredient_id" value="${response.id}" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="unit_price[]" id="unit_price" placeholder="Unit Price" value="${response.purchase_price}" />
                                        </td>
                                        <td class="d-flex justify-content-start align-items-center" id="quantity">
                                            <input type="text" class="form-control" name="quantity_amount[]" id="quantity_amount" value="1" placeholder="Unit Price" style="width:80%" />
                                            <span class="ms-2">${response.unit.name}</span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="total[]" id="total" value="${response.purchase_price}" readonly />
                                        </td>
                                        <td>
                                            <a href="#" class="btn primary-text" id="removeIngredientItem"><i class='icofont-ui-delete'></i></a>
                                        </td>
                                    </tr>`;

                            $('#items').append(item);
                            sumTotalIngredientAmount();
                            $(".datepicker").flatpickr();
                        }
                    },
                    complete: function () {
                        $($this).val(null);
                    },
                    error: function (e) {
                        handleError(e)
                    }
                });
            }
        }

        // $(this).val(null).trigger('change')
    });

    // Remove Ingredient Item
    $(document).on('click', '#removeIngredientItem', function(e) {
        e.preventDefault();
        const btn     = $(this);
        const url     = $(this).attr('href')
        const element = $(this).parent().parent();

        deleteData(btn, url, element);

        sumTotalIngredientAmount()
    });

    // Sum Ingredient Item
    const sumTotalIngredientAmount = () => {
        const prices = $('[name="unit_price[]"]');

        let amount = 0;
        $.each(prices, function (index, price) {
            let qty = $(price).parent().siblings('#quantity').children('input').val();
            amount += $(price).val() * qty;
        });
        $('input#total_amount').val(amount);
        countDue();
    }

    // Update quantity
    $(document).on('input', 'input#unit_price', function() {
        let unit_price = $(this).val();
        let qty = $(this).parent().siblings('td').children('#quantity_amount').val();
        $(this).parent().siblings('td').children('#total').val(unit_price * qty);
        sumTotalIngredientAmount()
    });

    // Update quantity
    $(document).on('input', 'input#quantity_amount', function(e) {
        let qty = $(this).val();
        let unit_price = $(this).parent().siblings('td').children('#unit_price').val();
        $(this).parent().siblings('td').children('#total').val(unit_price * qty);
        sumTotalIngredientAmount()
    });

    // Update quantity
    $(document).on('input', 'input#paid, input#discount, input#shipping_charge', function(e) {
        console.log(e); 
        countDue();
    });
    $(document).on('change', 'select#discount_type', function() { countDue(); });

    // Purchase Due count
    const countDue = () => {
        let total_amount    = parseFloat($('input#total_amount').val());
        let shipping_charge = parseFloat($('input#shipping_charge').val());
        let paid            = $('input#paid').val();

        if(total_amount != '') {
            let discount      = 0;
            let discount_type = $('select#discount_type').val();
            if(discount_type && $('input#discount').val() != '') {
                if(discount_type == 'percentage') {
                    discount = parseFloat(($('input#discount').val() / 100) * total_amount);
                }
                else {
                    discount = parseFloat($('input#discount').val());
                }
            }
            
            let grand_total = (total_amount + shipping_charge) - discount;
            
            $('input#grand_total').val(grand_total);
            if(paid != '' && paid != 0) {
                if(paid >= grand_total){
                    $('input#due').val(0)
                    $('input#change').val(paid - grand_total);
                }
                else if(paid < grand_total){
                    $('input#change').val(0);
                    $('input#due').val(grand_total - paid)
                }
            }
            else if(paid == 0){
                $('input#change').val(0);
                $('input#due').val(grand_total - paid)
            }

        }
    }

    // Handle food selection for waste management
    $(document).on('change', 'select#food', function() {
        let $this = $(this);
        let val   = $(this).val();

        const ids = $('td #food_id');
        const checkIds = [];
        $.each(ids, function (index, id) {
            checkIds.push($(id).val())
        });

        if (checkIds.includes(val)) {
            swal("Alert!", "Food already remains in cart, you can change Quantity/Amount", "warning");
        }
        else {
            if(val != '') {
                $.ajax({
                    type: 'GET',
                    url: route('waste.food', {id: val}),
                    dataType: 'JSON',
                    success: function (response) {
                        let item = '';
                        item = `<tr>
                                    <td>${ids.length + 1}</td>
                                    <td>
                                        ${response.name}
                                        <input type="hidden" name="food_id[]" id="food_id" value="${response.id}" />
                                        <input type="hidden" name="food_name[]" id="food_name" value="${response.name}" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="quantity[]" id="quantity" value="1" placeholder="Quantity" />
                                        <input type="hidden" name="price[]" id="price" value="${response.price}" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="total[]" id="total" value="${response.price}" readonly />
                                    </td>
                                    <td>
                                        <a href="#" class="btn primary-text" id="removeFoodItem"><i class='icofont-ui-delete'></i></a>
                                    </td>
                                </tr>`;

                        $('#items').append(item);
                        sumTotalWasteAmount()
                    },
                    complete: function () {
                        $($this).val(null);
                    },
                    error: function (e) {
                        handleError(e)
                    }
                });
            }
        }
    });

    // Sum Food Item
    const sumTotalWasteAmount = () => {
        const prices = $('input#price');
        let amount   = 0;
        $.each(prices, function (index, price) {
            let qty = $(price).siblings('input#quantity').val();
            amount += $(price).val() * qty;
        });
        $('input#total_loss').val(amount);
    }

    // Update Food quantity
    $(document).on('input', 'input#quantity', function(e) {
        let qty = $(this).val();
        let price = $(this).siblings('input#price').val();
        $(this).parent().siblings('td').children('#total').val(price * qty);
        sumTotalWasteAmount()
    });

    // Remove Ingredient Item
    $(document).on('click', '#removeFoodItem', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        sumTotalWasteAmount()
    });


    const getSelect2Data = () => {
        const element = $('input#get_select2_data');
        const val = element.val();

        if(typeof val !== "undefined") {
            const url = element.data('url');
            const selected = element.data('selected');

            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'JSON',
                beforeSend: function () {
                    element.attr('disabled', true);
                },
                success: function (response) {
                    let html = `<option value="">Select ${val}</option>`;
                    $.each(response, function (index, res) {
                        html += `<option value="${res.id}" ${selected == true && index == 0 ? 'selected' : ''}>${res.name}</option>`;
                    });
                    $(`select#${val}`).html(html).select2();
                },
                complete: function () {
                    element.attr('disabled', false);
                },
                error: function (e) {
                    handleError(e)
                }
            });
        }
    }


});