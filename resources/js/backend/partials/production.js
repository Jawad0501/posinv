$(document).ready(function () {
    $(document).on('change', 'select#food_name', function() {
        let value = $(this).val();

        $.ajax({
            type: 'GET',
            url: route('production.variant', value),
            dataType: 'JSON',
            success: function (response) {
                let variants = `<option value="">Select variant name</option>`
                $.each(response, function (indexInArray, value) { 
                    variants += `<option value="${value.id}">${value.name}</option>`
                });

                $('select#variant_name').html(variants).select2();
            },
            error: (err) => handleError(err)
        });
    });

    const getProductionUnitItem = () => {

        let length = $('table#unit-items tbody tr').length;
        $.ajax({
            type: 'GET',
            url: route('production.ingredient'),
            dataType: 'JSON',
            success: function (response) {
                let item = `<tr>
                    <td>
                        ${length}
                        <input type="hidden" name="items[]" id="items" value="0" />
                    </td>
                    <td>
                        <select name="products[]" id="product_id_${length}" class="form-control" required>`;
                            item += `<option value="">Select option</option>`
                            $.each(response.ingredients, function (index, ingredient) {
                                // convention is id_unit_price
                                if(ingredient.purchase_items_count > 0) {
                                    item += `<option value="${ingredient.id}_${ingredient.unit.slug}_${ingredient.purchase_items[0].unit_price}">${ingredient.name} (${ingredient.unit.name})</option>`
                                }
                                
                            });
                item +=    `</select>
                    </td>
                    <td>
                        <input type="number" name="qunatity[]" id="qunatity" min="1" class="form-control text-end" value="1" required placeholder="0.00" autocomplete="off" />
                    </td>
                    <td>
                        <select name="units[]" id="unit_id" class="form-control" required></select></select>
                    </td>
                    <td>
                        <input name="price[]" id="price" class="form-control text-end" placeholder="0.00" readonly />
                    </td>
                    <td>
                        <a href="#" id="removeUnitItem" class="btn btn-warning">Delete</a>
                    </td>
                </tr>`;

                $('#unit-items tbody').append(item);
                $(`select#product_id_${length}`).select2({
                    placeholder: 'Select option'
                });
            }
        });
    }

    // getProductionUnitItem()

    $(document).on('click', '#addMoreItem', () => getProductionUnitItem());

    const calculateAmount = (element, qunatity, unit, price) => {
        
        if(unit != null && price != null) {
            let html  = '';
            let units = [];

            if(['kg', 'gm'].includes(unit)) units = ['kg', 'gm']
            else if(['ltr', 'ml'].includes(unit)) units = ['ltr', 'ml']
            else if(['pcs'].includes(unit)) units = ['pcs']

            $.each(units, function (key, value) { 
                html += `<option value="${value}" key="${key}" ${value == unit ? 'selected':''}>${value}</option>` 
            });

            const unitElement  = $(element).parent().siblings().find('#unit_id')[0];
            const priceElement = $(element).parent().siblings().find('#price')[0];

            $(unitElement).html(html);
            $(priceElement).val(price * qunatity);

            sumGrandTotal()
        }
    }

    // Grand total amount count
    const sumGrandTotal = () => {
        let prices = $('table#unit-items tbody tr td input#price');
        let grand_total = 0;
        $.each(prices, function (index, element) { 
            grand_total += parseFloat($(element).val()) 
        });
        $('td#grand_total').text(convertAmount(grand_total));
    }


    // Calculate amount
    $(document).on('change', 'select[name="products[]"]', function() {
        const product    = $(this).val().split('_');
        const qtyElement = $(this).parent().siblings().find('input#qunatity')[0];
        const qunatity   = $(qtyElement).val();

        calculateAmount($(this), qunatity, product[1], product[2]);
    });

    // if input quantity/unit then call this function
    const isInputQtyOrUnit = ($this, qunatity, unit) => {
        
        const element     = $($this).parent().siblings().find('select[name="products[]"]')[0];
        const product     = $(element).val().split('_');
        const currentUnit = product[1];
        const price       = parseFloat(product[2]);

        let pPrice = 0;
        if((currentUnit == 'kg' && unit == 'gm') || (currentUnit == 'ltr' && unit == 'ml')) {
            pPrice += price / 1000;
        }
        else if((currentUnit == 'gm' && unit == 'kg') || (currentUnit == 'ml' && unit == 'ltr')) {
            pPrice += price * 1000;
        }
        else pPrice += price;
        
        calculateAmount($this, qunatity, unit, pPrice > 0 ? pPrice : null)
    }
    
    $(document).on('input', 'input#qunatity', function(e) {
        console.log(e);
        const qunatity   = $(this).val();

        const unitElement = $(this).parent().siblings().find('#unit_id')[0];
        const unit        = $(unitElement).val();

        isInputQtyOrUnit($(this), qunatity, unit);
    });


    $(document).on('change', 'select[name="units[]"]', function() {

        const unit       = $(this).val();
        const qtyElement = $(this).parent().siblings().find('input#qunatity')[0];
        const qunatity   = $(qtyElement).val()
        
        isInputQtyOrUnit($(this), qunatity, unit);
    });

    // Remove Production unit item
    $(document).on('click', '#removeUnitItem', function(e) {
        e.preventDefault();

        const btn     = $(this);
        const url     = $(this).attr('href')
        const element = $(this).parent().parent();

        deleteData(btn, url, element);

        setTimeout(() => sumGrandTotal(), 3000);
    });
});