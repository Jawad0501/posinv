$(document).ready(function () {

    if(location.pathname == '/staff/kitchen/panel') {

        const playAudio = () => {
            const audio = document.getElementById("audio").value;
            var sound = new Audio(audio);
            sound.play();
        };

        const getKitchenOrders = () => {
            const formData = $("#kitchen_form").serialize();
            $.ajax({
                type: "GET",
                url: route("kitchen.order"),
                data: formData,
                dataType: "HTML",
                beforeSend: function () {
                    $("#kitchen-loading").removeClass("d-none");
                },
                success: function (response) {
                    $("#all_orders").html(response);
                    countUpTime();
                    const unseen_order = $("#unseen_order").val();
                    $("#new-order").text(unseen_order);

                    if (unseen_order > 0) playAudio();
                },
                complete: function () {
                    $("#kitchen-loading").addClass("d-none");
                },
                error: function (err) {
                    console.error(err);
                },
            });
        };

        getKitchenOrders();

        // Single order item select/unselect
        $(document).on("click", "#order-item", function (e) {
            let status = $(this).data("item-status");
            let selected = $(this).attr("data-selected");

            if (selected == 1) {
                if (status == statuses.cooking) {
                    $(this).removeClass("order-ready").addClass("order-cooking").attr("data-selected", 0);
                }
                else if (status == statuses.ready) {
                    $(this).addClass("order-ready").removeClass("order-served").attr("data-selected", 0);
                }
                else if (status == statuses.pending) {
                    $(this).removeClass("order-ready order-cooking").attr("data-selected", 0);
                }
            }
            else {
                if (status == statuses.pending) {
                    $(this).addClass("order-cooking").attr("data-selected", 1);
                }
                else if (status == statuses.cooking) {
                    $(this).addClass("order-ready").removeClass("order-cooking").attr("data-selected", 1);
                }
                else if (status == statuses.ready) {
                    $(this).addClass("order-served").removeClass("order-ready").attr("data-selected", 1);
                }
            }

            if (status != "done") {
                const parent = $(this).parent().siblings(".kitchen-card-footer")[0];
                updateButton(parent);
            }
        });

        // Order Item select unselect
        $(document).on("click", "#select-unselect", function (e) {
            const role = $(this).data("role");
            const parent = $(this).parent();

            let isDone = true;
            parent.siblings("#kitchen-card-body").children().each(function () {
                let status = $(this).data("item-status");
                if (role == "select") {
                    if (status == statuses.pending && status != statuses.ready) {
                        $(this).addClass("order-cooking").attr("data-selected", 1);
                    }
                    else if (status == statuses.cooking && status != statuses.ready) {
                        $(this).addClass("order-ready").attr("data-selected", 1);
                    }
                    else if (status == statuses.ready && status != statuses.served) {
                        $(this).addClass("order-served").attr("data-selected", 1);
                    }
                }
                else {
                    if (status == statuses.pending) {
                        $(this).removeClass("order-cooking").attr("data-selected", 0);
                    }
                    else if (status == statuses.cooking) {
                        $(this).removeClass("order-ready").addClass("order-cooking").attr("data-selected", 0);
                    }
                    else if (status == statuses.ready) {
                        $(this).removeClass("order-served").addClass("order-ready").attr("data-selected", 0);
                    }
                }
                if (isDone != false) {
                    if (status == "select") isDone = false;
                }
                if (status != statuses.served) updateButton(parent, role);
            });
        });

        // update selected items button
        const updateButton = (parent, role = "select") => {
            $(parent).children("#update-order").remove();
            if (role == "select") {
                let button =
                    `<button type="button" class="btn cooking-btn" id="update-order" data-role="${statuses.cooking}">${statuses.cooking}</button>
                    <button type="button" class="btn ready-btn" id="update-order" data-role="${statuses.ready}">${statuses.ready}</button>
                    <button type="button" class="btn served-btn" id="update-order" data-role="${statuses.served}">${statuses.served}</button>`;
                $(parent).append(button);
            }
        };

        // Update kitchen panel order status
        $(document).on("click", "#update-order", function (e) {
            e.preventDefault();
            const btn = $(this);
            const role = $(this).data("role");

            const orderItems = $(this).parent().siblings("#kitchen-card-body").children();
            let order_id;
            let item_ids = [];
            $.each(orderItems, function (index, orderItem) {
                let selected = $(orderItem).data("selected");
                if (selected === 1) {
                    order_id = $(orderItem).data("order-id");
                    item_ids.push($(orderItem).data("item-id"));
                }
            });
            if (item_ids.length > 0) {
                $.ajax({
                    type: "POST",
                    url: route("kitchen.order.update", { order_id: order_id }),
                    data: {
                        _token: csrf_token,
                        _method: "PUT",
                        item_ids: item_ids,
                        status: role,
                    },
                    dataType: "JSON",
                    beforeSend: function () {
                        $(btn).addClass("disabled");
                    },
                    success: function (response) {
                        handleSuccess(response);
                        getKitchenOrders();
                    },
                    complete: function () {
                        $(btn).removeClass("disabled");
                    },
                    error: function (err) {
                        handleError(err);
                    },
                });
            }
        });

        // Count Time
        const countTime = () => {
            $("input#available_time").each(function () {
                let {hour, min, sec} = JSON.parse($(this).val());
                if (hour >= 0 && min >= 0 && sec >= 0) {
                    if (sec <= 1 && min > 0) {
                        sec = 60;
                        if (min > 0) min--;
                        if (min <= 0 && hour > 0) {
                            hour--;
                            min = 59;
                        }
                    }
                    if (sec > 0) sec--;

                    var formatted = hour.toString().padStart(2, '0') + ':' + min.toString().padStart(2, '0') + ':' + sec.toString().padStart(2, '0');
                    $(this).siblings("#show_available_time").text(formatted);
                    $(this).val(JSON.stringify({hour, min, sec}));
                }
            });
        };

        const countUpTime = () => {
            $("input#available_time").each(function () {
                var sec_num = parseInt($(this).val()); // don't forget the second param
                var hours   = Math.floor(sec_num / 3600);
                var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                var seconds = sec_num - (hours * 3600) - (minutes * 60);

                var formatted = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
                $(this).siblings("#show_available_time").text(formatted);
                $(this).val(sec_num + 1)
            });
        }

        setInterval(() => countUpTime(), 1000);
        setInterval(() => getKitchenOrders(), 15000);

        $(document).on("click", "#refreshKitchen", function () {
            getKitchenOrders();
        });
    }
});
