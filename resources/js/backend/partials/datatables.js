$(function() {

    const dataTableInitilize = (options, element = '#table') => {
        console.log(DataTable)
        var table = $(element).DataTable(options);
        window.table = table
    }

    /*
        MENU MANAGEMENT DATATABLES START
    */

    // MENU MANAGEMENT
    if(route().current('food.menu.index')) {
        var options = {
            ajax: route('food.menu.index'),
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price', render: function(data) {
                    return convertAmount(data);
                }},
                {data: 'tax_vat', name: 'tax_vat'},
                {data: 'image', name: 'image', searchable: false, orderable: false, render: function(data, type, row, meta) {
                    return `<img src="${showUploadedFile(data)}" alt="${row.name}" width="70px" />`;
                }},
                {data: 'action', searchable: false, orderable: false}
            ]
        };
        dataTableInitilize(options)
    }
    // ADDON MANAGEMENT
    if(route().current('food.addon.index')) {
        var options = {
            ajax: route('food.addon.index'),
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'addon', name: 'addon'},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price', render: (data) => convertAmount(data)},
                {data: 'action', searchable: false, orderable: false}
            ]
        };
        dataTableInitilize(options)
    }
    // MEAL PERIOD MANAGEMENT
    if(route().current('food.meal-period.index')) {
        var options = {
            ajax: route('food.meal-period.index'),
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'name', name: 'name'},
                {data: 'time_slot.start_time', name: 'time_slot.start_time'},
                {data: 'time_slot.end_time', name: 'time_slot.end_time'},
                {data: 'action', searchable: false, orderable: false}
            ]
        };
        dataTableInitilize(options)
    }
    /*
        MENU MANAGEMENT DATATABLES END
    */

    /*
        CLIENT MANAGEMENT DATATABLES START
    */

    // CUSTOMER MANAGEMENT
    if(route().current('client.customer.index')) {
        var options = {
            ajax: route('client.customer.index'),
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'full_name', name: 'full_name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'action', searchable: false, orderable: false}
            ]
        };
        dataTableInitilize(options)
    }
    // GIFT CARD MANAGEMENT
    if(route().current('client.gift-card.index')) {
        var options = {
            ajax: route('client.gift-card.index'),
            columns: [
                {data: 'DT_RowIndex', name: 'id', searchable: false},
                {data: 'user.full_name', name: 'first_name'},
                {data: 'amount', name: 'amount',render: (data) => convertAmount(data)},
                {data: 'status', name: 'status'},
                {data: 'trx', name: 'trx'},
                {data: 'btc_wallet', name: 'btc_wallet'},
                {data: 'action', searchable: false, orderable: false}
            ]
        };
        dataTableInitilize(options)
    }
    /*
        CLIENT MANAGEMENT DATATABLES END
    */

    /*
        REPORT MANAGEMENT DATATABLES START
    */

        // Sum of footer values
        let footerValueSum = (api, ...i) => {
            // Remove the formatting to get integer data for summation
            const intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            i.forEach(element => {
                // let total = api.column(element).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                let pageTotalDue = api.column(element, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                api.column(element).footer().innerHTML = convertAmount(pageTotalDue);
            });
        }

        // PURCHASE REPORT MANAGEMENT
        if(route().current('report.purchase')) {
            var options = {
                ajax: {
                    url: route('report.purchase'),
                    data: function (d) {
                        d.from = $('input[name="from"]').val()
                        d.to = $('input[name="to"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'reference_no', name: 'reference_no'},
                    {data: 'supplier.name', name: 'supplier.name'},
                    {data: 'date', name: 'date'},
                    {data: 'total_amount', name: 'total_amount', render: (data) => convertAmount(data)},
                    {data: 'due_amount', name: 'due_amount', render: (data) => convertAmount(data)},
                    {data: 'action', searchable: false, orderable: false}
                ],
                footerCallback: function () { footerValueSum(this.api(), 4, 5) }
            };
            dataTableInitilize(options)
        }

        // EXPENSE REPORT MANAGEMENT
        if(route().current('report.expense')) {
            var options = {
                ajax: {
                    url: route('report.expense'),
                    data: function (d) {
                        d.from = $('input[name="from"]').val()
                        d.to = $('input[name="to"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'category.name', name: 'category.name'},
                    {data: 'staff.name', name: 'staff.name'},
                    {data: 'amount', name: 'amount', render: (data) => convertAmount(data)},
                    {data: 'date', name: 'date'},
                    {data: 'note', name: 'note'},
                    {data: 'status', name: 'status', render: (data) => data ? 'Active': 'Disabled'}
                ],
                footerCallback: function () { footerValueSum(this.api(), 3) }
            };
            dataTableInitilize(options)
        }

        // BANK TRANSACTIONS REPORT MANAGEMENT
        if(route().current('report.bank-transaction')) {
            var options = {
                ajax: {
                    url: route('report.bank-transaction'),
                    data: function (d) {
                        d.bank = $('select[name="bank"]').val()
                        d.from = $('input[name="from"]').val()
                        d.to = $('input[name="to"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'bank.name', name: 'bank.name'},
                    {data: 'date', name: 'date'},
                    {data: 'type', name: 'type'},
                    {data: 'withdraw_deposite_id', name: 'withdraw_deposite_id'},
                    {data: 'amount', name: 'amount', render: (data) => convertAmount(data)},
                ],
                footerCallback: function () { footerValueSum(this.api(), 5) }
            };
            dataTableInitilize(options)
        }

        // WASTE REPORT MANAGEMENT
        if(route().current('report.waste')) {
            var options = {
                ajax: {
                    url: route('report.waste'),
                    data: function (d) {
                        d.person = $('select[name="person"]').val()
                        d.from = $('input[name="from"]').val()
                        d.to = $('input[name="to"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'reference_no', name: 'reference_no'},
                    {data: 'date', name: 'date'},
                    {data: 'total_loss', name: 'total_loss', render: (data) => convertAmount(data)},
                    {data: 'staff.name', name: 'staff.name'},
                    {data: 'note', name: 'note'},
                    {data: 'added_by', name: 'added_by'},
                    {data: 'action', searchable: false, orderable: false}
                ],
                footerCallback: function () { footerValueSum(this.api(), 3) }
            };
            dataTableInitilize(options)
        }

        // INGREDIENT REPORT MANAGEMENT
        if(route().current('report.ingredient')) {
            var options = {
                ajax: {
                    url: route('report.ingredient'),
                    data: function (d) {
                        d.category = $('select[name="category"]').val()
                        d.unit = $('select[name="unit"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'category.name', name: 'category.name'},
                    {data: 'purchase_price', name: 'purchase_price', render: (data) => convertAmount(data)},
                    {data: 'alert_qty', name: 'alert_qty'},
                    {data: 'unit.name', name: 'unit.name'}
                ],
                footerCallback: function () { footerValueSum(this.api(), 4) }
            };
            dataTableInitilize(options)
        }

        // STOCK REPORT MANAGEMENT
        if(route().current('report.stock')) {
            var options = {
                ajax: {
                    url: route('report.stock'),
                    data: function (d) {
                        d.category = $('select[name="category"]').val()
                        d.ingredient_id = $('select[name="ingredient_id"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'ingredient_name', name: 'ingredient.name'},
                    {data: 'ingredient.category.name', name: 'ingredient.category.name'},
                    {data: 'stock_qty', name: 'ingredient.stock.qty_amount'},
                    {data: 'alert_qty', name: 'ingredient.alert_qty'},
                ]
            };
            dataTableInitilize(options)
        }

        // SALE REPORT MANAGEMENT
        if(route().current('report.sale')) {
            var options = {
                ajax: {
                    url: route('report.sale'),
                    data: function (d) {
                        d.customer = $('select[name="customer"]').val()
                        d.from = $('input[name="from"]').val()
                        d.to = $('input[name="to"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'invoice', name: 'invoice'},
                    {data: 'user.full_name', name: 'user.first_name'},
                    {data: 'grand_total', name: 'grand_total', render: (data) => convertAmount(data)},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', searchable: false, orderable: false}
                ],
                footerCallback: function () { footerValueSum(this.api(), 3) }
            };
            dataTableInitilize(options)
        }



        // FOR LEDGER
        if(route().current('client.user-ledger.index')) {
            var options = {
                ajax: {
                    url: route('client.user-ledger.index'),
                    data: function (d) {
                        d.customer = $('select[name="customer"]').val()
                        d.from = $('input[name="from"]').val()
                        d.to = $('input[name="to"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'date', name: 'date'},
                    {data: 'invoice', name: 'invoice'},
                    {data: 'total_items', name: 'total_items'},
                    {data: 'total', name: 'total'},
                    {data: 'payment.give_amount', name: 'paid', render: (data) => convertAmount(data)},
                    {data: 'payment.change_amount', name: 'change', render: (data) => convertAmount(data)},
                    {data: 'payment.due_amount', name: 'due', render: (data) => convertAmount(data)},
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    
                    // Calculate the sum for each column
                    var totalItemSum = api.column(3, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var totalSum = api.column(4, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var paidSum = api.column(5, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var changeSum = api.column(6, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var dueSum = api.column(7, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    
                    // Update the footer cells with the sums
                    $(api.column(3).footer()).html(convertAmount(totalItemSum));
                    $(api.column(4).footer()).html(convertAmount(totalSum));
                    $(api.column(5).footer()).html(convertAmount(paidSum));
                    $(api.column(6).footer()).html(convertAmount(changeSum));
                    $(api.column(7).footer()).html(convertAmount(dueSum));
                }
            };
            dataTableInitilize(options)
        }



        if(route().current('ledger-supplier.index')) {
            var options = {
                ajax: {
                    url: route('ledger-supplier.index'),
                    data: function (d) {
                        d.supplier = $('select[name="supplier"]').val()
                        d.from = $('input[name="from"]').val()
                        d.to = $('input[name="to"]').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'date', name: 'date'},
                    {data: 'reference_no', name: 'invoice'},
                    {data: 'total_items', name: 'total_items'},
                    {data: 'total', name: 'total'},
                    {data: 'paid_amount', name: 'paid', render: (data) => convertAmount(data)},
                    {data: 'change_amount', name: 'change', render: (data) => convertAmount(data)},
                    {data: 'due_amount', name: 'due', render: (data) => convertAmount(data)},
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    
                    // Calculate the sum for each column
                    var totalItemSum = api.column(3, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var totalSum = api.column(4, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var paidSum = api.column(5, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var changeSum = api.column(6, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    var dueSum = api.column(7, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                    
                    // Update the footer cells with the sums
                    $(api.column(3).footer()).html(convertAmount(totalItemSum));
                    $(api.column(4).footer()).html(convertAmount(totalSum));
                    $(api.column(5).footer()).html(convertAmount(paidSum));
                    $(api.column(6).footer()).html(convertAmount(changeSum));
                    $(api.column(7).footer()).html(convertAmount(dueSum));
                }
            };
            dataTableInitilize(options)
        }

        
        // LOSS PROFIT DATA
        const lossProfit = () => {
            var from = $('#from').val();
            var to   = $('#to').val();
            if(from != '' && to != '') {
                var url = route('report.profit.loss.view', { _query: { from: from, to: to} });
            }
            else var url = route('report.profit.loss.view');

            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'HTML',
                success: function (response) {
                    $('#loss_profit_report').html(response);
                }
            });
        }

        // SALE REPORT MANAGEMENT
        if(route().current('report.profit.loss')) {

            var options = {
                ajax: route('report.profit.loss.gross', 'food'),
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'purchase_id', name: 'purchase_id'},
                    {data: 'unit_price', name: 'unit_price'},
                    {data: 'quantity_amount', name: 'quantity_amount'},
                    {data: 'sold_qty', name: 'sold_qty'},
                    {data: 'gross_profit', name:'gross_proft'}
                ],
                footerCallback: function () { 
                    var api = this.api();
                    
                    // Calculate the sum for each column
                    var grossSum = api.column(6, {page: 'current'}).data().reduce(function (acc, curr) {
                        return acc + parseFloat(curr);
                    }, 0);
                   
                    
                    // Update the footer cells with the sums
                    $(api.column(6).footer()).html(convertAmount(grossSum));
                 }
            };
            dataTableInitilize(options)

            var options = {
                ajax: route('report.profit.loss.gross', 'invoice'),
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'invoice', name: 'invoice'},
                    {data: 'grand_total', name: 'grand_total', render: function(data) {
                        return convertAmount(data);
                    }}
                ],
                footerCallback: function () { footerValueSum(this.api(), 2) }
            };
            dataTableInitilize(options, '#invoice_table')

            var options = {
                ajax: route('report.profit.loss.gross', 'customer'),
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'full_name', name: 'first_name'},
                    {data: 'orders_sum_grand_total', name: 'orders_sum_grand_total', render: (data) => convertAmount(data)}
                ],
                footerCallback: function () { footerValueSum(this.api(), 2) }
            };
            dataTableInitilize(options, '#customer_table')

            var options = {
                ajax: route('report.profit.loss.gross', 'date'),
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'date', name: 'date'},
                    {data: 'grand_total', name: 'grand_total', render: (data) => convertAmount(data)}
                ],
                footerCallback: function () { footerValueSum(this.api(), 2) }
            };
            dataTableInitilize(options, '#date_table')

            
            lossProfit();

            $(document).on('click', '#printData', function(e) {
                e.preventDefault();
                var printContents = document.getElementById("loss_profit_report").innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            });
        }

        $(document).on('submit', '#filterData', function(e) {
            e.preventDefault();
            console.log(route().current('report.profit.loss'));
            if(route().current('report.profit.loss')) {
                lossProfit();
            } else {
                table.ajax.reload();
            }
        });

        $(document).on('click', '#reloadData', function(e) {
            e.preventDefault();

            $('input[name="from"]').val('');
            $('input[name="to"]').val('');

            if(route().current('report.profit.loss')) {
                lossProfit();
                return;
            }

            $('select[name="bank"]').val(null).trigger('change');
            $('select[name="person"]').val(null).trigger('change');
            $('select[name="customer"]').val(null).trigger('change');
            $('select[name="category"]').val(null).trigger('change');
            $('select[name="ingredient_id"]').val(null).trigger('change');
            $('select[name="unit"]').val(null).trigger('change');
            
            table.ajax.reload();
        });
    /*
        REPORT MANAGEMENT DATATABLES END
    */

})
