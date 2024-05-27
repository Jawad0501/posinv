import PerfectScrollbar from "perfect-scrollbar";

import horwheel from "horwheel";

$(document).ready(function () {

    $('#preloader').addClass('d-none');

    if (location.pathname === "/staff/pos") {
        const orders = document.querySelectorAll(".orders");
        orders.forEach((order) => horwheel(order));

        new PerfectScrollbar(".product-section");
        // new PerfectScrollbar(".orders-container");
        new PerfectScrollbar(".table-view");
    }

    const selectAll = document.getElementById('selectAll')
    if (selectAll) {
        selectAll.addEventListener('click', (e) => {
            const checkboxes = document.querySelectorAll('input.permission-checkbox');
            checkboxes.forEach(element => element.checked = e.target.checked ? true: false);
        })

        const permissionCheckboxes = document.querySelectorAll('input.permission-checkbox');
        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                let allChecked = true;
                permissionCheckboxes.forEach(cb => {
                    if (!cb.checked) {
                        allChecked = false;
                    }
                });
                selectAll.checked = allChecked;
            });
        });
    }

    const posHeight = () => {
        if (innerWidth >= 1200) {
            const productSection = document.querySelector(".product-section");
            const tableView = document.querySelector(".table-view");
            productSection.style.height = `${
                innerHeight < 900
                    ? innerHeight - 130
                    : innerHeight - 150
            }px`;

            tableView.style.height = `${
                innerHeight >= 700 || innerHeight <= 950
                    ? innerHeight - 433
                    : innerHeight - 420
            }px`;

            $("body").css("overflow", "hidden");
            $(".topbar").css("right", "30%");
        }
        else {
            $("body").attr("style", "");
            $(".topbar").attr("style", "");
        }
    };

    if (location.pathname === "/staff/pos") {
        posHeight();
        window.addEventListener("resize", posHeight);
    }

    if (
        location.pathname != "/staff/pos" &&
        location.pathname != "/staff/kitchen/panel" &&
        location.pathname != "/staff/login"
    ) {

        new PerfectScrollbar(".header-message-list");
    }

    // try {
    //     new PerfectScrollbar(".header-notifications-list");
    // }
    // catch (error) {
    //     console.log(error);
    // }


    $(".mobile-toggle-menu").on("click", function () {
        $(".wrapper").addClass("toggled");
    });

    $(".mobile-search-icon").on("click", function () {
        $(".search-bar").addClass("full-search-bar");
    });

    $(".search-close").on("click", function () {
        $(".search-bar").removeClass("full-search-bar");
    });


    $(".toggle-icon").click(function () {
        toggleSidebar();
    });

    const toggleSidebar = () => {
        $(".wrapper").hasClass("toggled")
            ?
            ($(".wrapper").removeClass("toggled"), $(".sidebar-wrapper").unbind("hover"))
            :
            ($(".wrapper").addClass("toggled"), $(".sidebar-wrapper").hover(
                function() {
                    $(".wrapper").addClass("sidebar-hovered");
                },
                function() {
                    $(".wrapper").removeClass("sidebar-hovered");
                }
            ));
    }
    if (window.innerWidth >= 1024) {
        toggleSidebar();
    }


    try {
        $("#menu").metisMenu();
    } catch (error) {
        console.log(error);
    }

    $(".switcher-btn").on("click", function () {
        $(".switcher-wrapper").toggleClass("switcher-toggled");
    });
    $(".close-switcher").on("click", function () {
        $(".switcher-wrapper").removeClass("switcher-toggled");
    });

    $(document).on("click", "#changePassType", function () {
        const input = $(this).siblings("input")[0];
        const password = $(input).attr("type");

        if (password == "password") {
            $(input).attr("type", "text");
            $(this)
                .children("i.fa-solid")
                .removeClass("fa-eye-slash")
                .addClass("fa-eye");
        } else {
            $(input).attr("type", "password");
            $(this)
                .children("i.fa-solid")
                .removeClass("fa-eye")
                .addClass("fa-eye-slash");
        }
    });

    // Switch to light/dark mode
    const themeSelect = () => {
        const theme = localStorage.getItem("theme");
        if (theme != null) {
            $("html").attr("class", theme);

            let img;
            let logo;
            if (theme == "dark") {
                img = `${origin}/build/assets/backend/images/icons/sun.svg`;
                logo = $('input[name="light_mode_logo"]').val();
            }
            else {
                img = `${origin}/build/assets/backend/images/icons/moon.svg`;
                logo = $('input[name="dark_mode_logo"]').val();
                // var style = "background-color: #fff !important;";
            }

            $("#switchTheme img").attr("src", img);
            $(".logo-icon").attr("src", logo);

            if (location.pathname == "/staff/login") {
                $("body").attr("style", style ?? "");
            }
            else {
                $.each($('input[name="changeTheme"]'), function (key, value) {
                    if (theme == $(value).val()) $(value).attr("checked", true);
                });
            }
        }
        return true;
    };
    themeSelect();

    // Switch Theme
    $(document).on("click", 'input[name="changeTheme"]', function (e) {
        localStorage.setItem("theme", e.target.value);
        themeSelect();
    });

    $(document).on("click", "#switchTheme", function (e) {
        const theme = localStorage.getItem("theme");
        localStorage.setItem(
            "theme",
            theme == "dark" || typeof theme == "undefined" ? "light" : "dark"
        );
        themeSelect();
    });
});
