$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': csrf_token }
    });

    // toastr.options.timeOut = 50;

    const defineSelec2 = () => {
        const select2 = $('.select2');
        if (select2.length) {
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            select2.each(function () {
                const tags = $(this).attr('tags')
                $(this).select2({
                    tags: typeof tags !== 'undefined',
                    dropdownParent: $(this).parent()
                })
            });
        }
    }
    defineSelec2()

    // Logout
    $(document).on('click', '#logout', function(e) {
        e.preventDefault();

        let btn = $(this);
        let url = $(this).attr('href');
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                _token: csrf_token
            },
            dataType: 'JSON',
            beforeSend: function () {
                $(btn).addClass('disabled');
            },
            success: function (response) {
                handleSuccess(response, route('staff.login'));
            },
            complete: function () {
                $(btn).addClass('disabled');
            },
            error: function (err) {
                handleError(err);
            }
        });
    });

    $(document).on('change', 'input[type="file"]', function (e) {
        let show = $(this).data('show-image');
        showImage(e, show);
    });

    const showImage = (event, show) => {
        let file = event.target.files[0];

        let reader = new FileReader();
        reader.onload = (e) => {
            $(`#${show}`).attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    }

    // Add modal show
    $(document).on('click', '#addBtn, #editBtn, #showBtn, #qrBtn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const url = $(this).attr("href");

        sendGetRequest(url, btn)
    });

    /* Send a GET request in the server */
    const sendGetRequest = (url, btn) => {
        $('#modal').modal('hide');
        $('#modal').remove();
        $.ajax({
            type: "GET",
            url: url,
            dataType: "HTML",
            beforeSend: function () {
                $(btn).addClass("disabled");
            },
            success: function (response) {
                // $("#modal .modal-content").html(response);
                // $("#modal").modal("show");

                $('.page-content').append(response);
                $("#modal").modal("show");

                defineSelec2();

                $("input.datepicker, input.clockpicker").each(function () {
                    const options = typeof $(this).data('options') !== 'undefined' ? $(this).data('options') : {};
                    $(this).flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                    });
                })

            },
            complete: function () {
                $(btn).removeClass("disabled");
            },
            error: function (e) {
                handleError(e)
            }
        });
    }

    /* Submit post request in server without file upload */
    $(document).on("submit", "form#form", function (e) {
        e.preventDefault();
        const url      = $(this).attr("action");
        const redirect = $(this).data('redirect');
        const btn      = $(this).find('button[type="submit"]')[0];
        const spinner  = $(btn).find('#submit-spinner')[0];
        const data     = $(this).serialize();

        submitForm(url, data, btn, spinner, redirect);
    });

    /* Submit post request in server with file upload */
    $(document).on("submit", "#fileForm", function (e) {
        e.preventDefault();

        const url      = $(this).attr("action");
        const redirect = $(this).data('redirect');
        const data     = new FormData($(this)[0]);
        const btn      = $(this).find('button[type="submit"]')[0];
        const spinner  = $(btn).find('#submit-spinner')[0];

        submitForm(url, data, btn, spinner, redirect, true);
    });

    /* Send post request in server */
    const submitForm = (url, data, btn, spinner, redirect, isFile = false) => {
        $(".form-control").removeClass("is-invalid");
        $(".error-message").text("");
        $(".invalid-feedback").text("");

        if (isFile == false) {
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "JSON",
                beforeSend: function () {
                    $(btn).addClass("disabled");
                    $(spinner).removeClass("d-none");
                },
                success: function (response) {
                    handleSuccess(response, redirect);
                },
                complete: function () {
                    $(btn).removeClass("disabled");
                    $(spinner).addClass("d-none");
                },
                error: function (err) {
                    handleError(err);
                }
            });
        }
        else {
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "JSON",
                // async: false,
                // cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                beforeSend: function () {
                    $(btn).addClass("disabled");
                    $(spinner).removeClass("d-none");
                },
                success: function (response) {
                    handleSuccess(response, redirect)
                },
                complete: function () {
                    $(btn).removeClass("disabled");
                    $(spinner).addClass("d-none");
                },
                error: function (e) {
                    handleError(e)
                }
            });
        }
    };

    // Delete Data
    $(document).on("click", "#deleteBtn", function (e) {
        e.preventDefault();
        var $this = $(this);
        var url = $($this).attr("href");
        deleteData($this, url);
    });


    // Menu/Food add/update check categories start
    $(document).on('change', 'select[name="categories[]"]', () => checkMenuCategory());

    const checkMenuCategory = () => {
        const categories = $('select[name="categories[]"]').val();

        if(typeof categories !== 'undefined' && categories.length > 0) {
            // $('input#price').val('')
            $.ajax({
                type: 'GET',
                url: route('food.menu.category.check'),
                data: {categories},
                dataType: 'JSON',
                success: function (response) {
                    if(response > 0) {
                        $('.is_no_drinks').addClass('d-none');
                        $('.is_drinks').removeClass('d-none');
                    }
                    else {
                        $('.is_no_drinks').removeClass('d-none');
                        $('.is_drinks').addClass('d-none');
                    }
                }
            });
        }
        else {
            $('.is_no_drinks').removeClass('d-none');
            $('.is_drinks').addClass('d-none');
        }
    }
    checkMenuCategory();

    $(document).on('change', '#ingredient_name', function() {
        let ingredient = $(this).val();

        $.ajax({
            type: 'GET',
            url: route('food.menu.ingredient', ingredient),
            dataType: 'JSON',
            success: function (response) {
                $('input#price').val(response.price)
            }
        });

    });
    // Menu/Food add/update check categories end


    // Label Printing Start
    $(document).on('input', 'input#seach-menu', function(e) {

        if(e.target.value !== '') {
            $.ajax({
                type: 'GET',
                url: route('food.printlabel.menu', {_query: {keyword: e.target.value}}),
                dataType: 'JSON',
                success: function (response) {
                    let menus = '';
                    response.forEach(element => {
                        menus += `<li class="border-bottom py-2 px-3" data-id="${element.id}" id="selectLabelMenu">${element.name}</li>`
                    });
                    $('#searched-menus').html(menus);
                }
            });
        }
        else {
            $('#searched-menus').empty();
        }
    });


    $(document).on('click', '#selectLabelMenu', function() {
        const id = $(this).data('id');

        $('input#seach-menu').val('')
        $('#searched-menus').empty();

        let length = $('table#labelMenus tbody tr').length;

        $.ajax({
            type: 'GET',
            url: route('food.printlabel.menu', {_query: {id: id, length: length}}),
            dataType: 'HTML',
            success: function (response) {
               $('table#labelMenus tbody').append(response);
               defineSelec2();
               $('button[type="submit"]').attr('disabled', false);
            }
        });
    })
    // Label Printing End


    // Table Layout QR Code Generate Start

    // $('#generateQrForm').ready(
    //     function() {
    //         let number = ''
    //         let qr_div = $(`#qr_div_${number}`);
    //         let table_number = $('#table_number').val();
    //         let category = 0;
    //         let type = $('#type').val();
    //         let url = route('menu', {_query: {table_number, category}})
    //         $(`#qr_div_${number}`).qrcode({
    //             width: 200,
    //             height: 200,
    //             text: url,
    //         });
    //         if (type == 'png') {
    //             let qrDataURL = qr_div.find('canvas')[0].toDataURL("image/png");
    //             let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
    //             qr_downloadLink.href = qrDataURL;
    //             qr_downloadLink.download = `${table_number}.png`
    //         }
    //         else {
    //             htmlToImage.toSvg(qr_div[0], {})
    //                 .then(function(dataUrl) {
    //                     let svg = decodeURIComponent(dataUrl.split(',')[1])
    //                     const base64doc = btoa(unescape(encodeURIComponent(svg)));
    //                     let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
    //                     qr_downloadLink.href = base64doc;
    //                     qr_downloadLink.download = `${table_number}.svg`
    //                 });
    //         }

    //     }
    // );

    // $('#categories').change(
    //     function() {
    //         let number = ''
    //         let qr_div = $(`#qr_div_${number}`);
    //         let table_number = $('#table_number').val();
    //         let category = $('#categories').val();
    //         let type = $('#type').val();
    //         let url = route('menu', {_query: {table_number, category}})
    //         $(`#qr_div_${number}`).html('')
    //         $(`#qr_div_${number}`).qrcode({
    //             width: 200,
    //             height: 200,
    //             text: url
    //         });

    //         if (type == 'png') {
    //             let qrDataURL = qr_div.find('canvas')[0].toDataURL("image/png");
    //             let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
    //             qr_downloadLink.href = qrDataURL;
    //             qr_downloadLink.download = `${table_number}.png`
    //         } else {
    //             htmlToImage.toSvg(qr_div, {})
    //                 .then(function(dataUrl) {
    //                     let svg = decodeURIComponent(dataUrl.split(',')[1])
    //                     const base64doc = btoa(unescape(encodeURIComponent(svg)));

    //                     let qr_downloadLink = $(`#qrDownload_${table_number}`)[0];
    //                     qr_downloadLink.href = base64doc;
    //                     qr_downloadLink.download = `${table_number}.svg`
    //                 });
    //         }
    //     }
    // );
    // Table Layout QR Code Generate End

});
