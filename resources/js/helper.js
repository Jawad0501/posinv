// window.csrf_token = $('meta[name="csrf-token"').attr("content");
window.csrf_token = document.querySelector('meta[name="csrf-token"').getAttribute('content');

const showUploadedFile = (avatar) => {
    return avatar != null ? `${location.origin}/storage/${avatar}` : 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Image_not_available.png/640px-Image_not_available.png';
}
window.showUploadedFile = showUploadedFile

const convertAmount = (number, decimals = 2, dec_point = '.', thousands_sep = ',') => {

    var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    toFixedFix = function (n, prec) {
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        var k = Math.pow(10, prec);
        return Math.round(n * k) / k;
    },
    s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    const currency_position = $('input[name="currency_position"]').val();
    const currency_symbol   = $('input[name="currency_symbol"]').val();

    if(currency_position == 'Before Amount') {
        return currency_symbol+s.join(dec);
    }
    return s.join(dec)+currency_symbol;
}
window.convertAmount = convertAmount


/* Send delete request in server */
const deleteData = (btn, url, removeElement = null) => {
    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "error",
        showCancelButton: true,
        confirmButtonText: "Delete",
        padding: "2em",
    }).then(function (result) {
        if (result.value) {
            if(url != '#') {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        _method: "DELETE",
                        _token: csrf_token
                    },
                    dataType: "JSON",
                    beforeSend: function () {
                        $(btn).addClass("disabled");
                    },
                    success: function (response) {
                        handleSuccess(response);
                        if(removeElement != null) $(removeElement).remove();
                        // swal("Deleted!", "Your file has been deleted.", "success");
                    },
                    complete: function () {
                        $(btn).removeClass("disabled");
                    },
                    error: function (e) {
                        handleError(e);
                    },
                });
            }
            else {
                if(removeElement != null) $(removeElement).remove();
            }
            return true;
        }
        else return false;
    });
}
window.deleteData = deleteData


/* Handle success request response */
const handleSuccess = (response, redirect = null) => {
    toastr.clear();

    // console.log(response.error !== undefined);

    if(response.error !== undefined){
        return toastr.error(response.error, "Error!");
    }

    toastr.success(response.message || response.status, "Success!");

    if (typeof response.redirect !== 'undefined' || (typeof redirect !== 'undefined' && redirect !== null)) {
        window.location.replace(redirect || response.redirect);
    }
    else {
        $('#modal').modal('hide');
        $("#modal .modal-content").html('');
        // getSelect2Data()

        if(location.pathname == '/staff/pos') {
            getCartData()
            getCustomer()
            getOrder()
            getMenu(route('pos.menu.index'));
            // getOnlineOrder();

            if (typeof response.tables !== 'undefined') {
                $('input[name="process_without_table"]').val(response.tables);
            }
        }
        if (typeof response.print_url !== 'undefined') {
            window.open(response.print_url, '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
        }

        const payroll = '/staff/payroll/';
        if([`${payroll}leave`, `${payroll}employee`, `${payroll}salary`].includes(location.pathname)) {
            getPayrollData()
        }

        if(location.pathname == '/staff/hr/clock-in') {
            $('button[type="submit"]').addClass('disabled').text('Clock In');
            $('input#id_number').val('');
        }

        if(typeof table !== 'undefined') {
            try {
                table.ajax.reload();
            } catch (error) {
                console.error(error);
            }
        }
    }
}

window.handleSuccess = handleSuccess

/* Handle error request response */
const handleError = (e, fillupError = true) => {
    toastr.clear();

    if (e.status === 0) {
        toastr.error(
            "Not connected Please verify your network connection.",
            "Connect Internet"
        );
    }
    else if (e.status === 404) {
        toastr.error("The requested data not found.", "Not Found");
    }
    else if (e.status === 403) {
        toastr.error("You are not allowed this action", "UNAUTHORIZED");
    }
    else if (e.status === 419) {
        toastr.error("CSRF token mismatch", "Something wrong");
    }
    else if (e.status === 500) {
        toastr.error("Internal Server Error.", "Server Error");
    }
    else if (e === "parsererror") {
        toastr.error("Requested JSON parse failed.", "Opps!!");
    }
    else if (e === "timeout") {
        toastr.error("Requested Time out.", "Try Again");
    }
    else if (e === "abort") {
        toastr.error("Request aborted.", "Something Wrong");
    }
    else if(e === "crash") {
        toastr.error(e.statusText);
    }
    else if (e.status === 422) {
        $.each(e.responseJSON.errors, function (index, error) {
            $("#invalid_" + index).text(error[0]);
            $("#" + index).addClass("is-invalid");
        });
        if(fillupError) {
            toastr.error("Please fillup all required fieled", "Opps!!");
        }
    }
    else if ([300, 301, 302].includes(e.status)) {
        toastr.error(e.responseJSON.message, "Opps!!");
    }
    else {
        toastr.error(e.statusText, "Something Wrong");
    }

    return true;
};

window.handleError = handleError
