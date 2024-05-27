$(document).ready(function () {

	if(window.location.pathname == '/') {
		// $('.select2').select2();
	}

	// Category Active class add/remove
	$(document).on('click', '.category-btn', function() {
		$('.category-btn').removeClass('active');
		$(this).addClass('active');
	});

	// Tab Button Active class add/remove
	$(document).on('click', '.tab-btn', function() {
		let aria = $(this).attr('aria-controls');
		let img  = $(this).children('.cart-heading-item').children('img');

		let images = $('.cart-heading-item img');
		$.each(images, function (index, image) { 
			let parentAria = $(image).parent().parent().attr('aria-controls');
			let imgName = parentAria.replace('pills-', '');
			$(image).attr('src', `./assets/images/cart/${imgName}.png`);
		});

		$(img).attr('src', `./assets/images/cart/${aria}.png`);

	});

	// Switch Theme
	$(document).on('click', '#switchTheme', function() {
		let theme = localStorage.geItem('theme');
		console.log(theme);
		if(typeof theme == 'undefined') {
			localStorage.setItem('theme', 'white');
		}
		else {
			localStorage.setItem('theme', theme == 'white' ? 'dark':'white');
		}
		themeSelect()
	});

	themeSelect();

	function themeSelect() {
		let theme = localStorage.getItem('theme');
		
		if(typeof theme !== 'undefined' && theme != null) {

			if(theme == 'white') {
				$('html').attr('data-theme', theme).removeClass('dark').addClass(theme);
				$('body').removeClass('dark-bg text-white').addClass('bg-white text-dark');
				$('#brand-logo').attr('src', './assets/images/logo/dark.png');
				$('.item-card').removeClass('item-card-dark').addClass('item-card-white');
				$('.cart-card').removeClass('cart-card-dark');
				$('.cart-card .cart-items').removeClass('cart-items-dark');
				$('.header .search').removeClass('search-dark');
				$('.dashboard-details-card').removeClass('card-dark');
				$('#switchTheme i').removeClass('icofont-moon').addClass('icofont-sun-alt');
			}
			else {
				$('html').attr('data-theme', theme).removeClass('white').addClass(theme);
				$('body').removeClass('bg-white text-dark').addClass('dark-bg text-white');
				$('#brand-logo').attr('src', './assets/images/logo/white.png');
				$('.item-card').removeClass('item-card-white').addClass('item-card-dark');
				$('.cart-card').addClass('cart-card-dark');
				$('.cart-card .cart-items').addClass('cart-items-dark');
				$('.header .search').addClass('search-dark');
				$('.dashboard-details-card').addClass('card-dark');
				$('#switchTheme i').removeClass('icofont-sun-alt').addClass('icofont-moon');
			}
		}
		return true;
	}

	// Menubar Close Open
	$(document).on('click', '#switch', function () {
		let switchBtn = $('#switch-button').is(":checked");

		let aside = {
			selector: $('.aside'),
			className: 'space-aside'
		};
		let header = {
			selector: $('.header'),
			className: 'space-header'
		};
		let mainContent = {
			selector: $('.main-content'),
			className: 'space-main-content'
		};

		if (switchBtn) {
			$(aside.selector).removeClass(aside.className)
			$(header.selector).removeClass(header.className)
			$(mainContent.selector).removeClass(mainContent.className)
		}
		else {
			$(aside.selector).addClass(aside.className)
			$(header.selector).addClass(header.className)
			$(mainContent.selector).addClass(mainContent.className)
		}
	});

	$(document).on('click', '.dropdown .nav-link', function() {
		let link    = $(this);
		let sibDrop = $(link).siblings('.dropdown-menus');
		
		
		if($(sibDrop).hasClass('d-none')) {
			$('.dropdown-menus').addClass('d-none');
			$(link).children('.dropdown-toggler').css("transform", "rotate(90deg)");
			$(sibDrop).removeClass('d-none');
		}
		else {
			$(link).children('.dropdown-toggler').css("transform", "rotate(0deg)");
			$(sibDrop).addClass('d-none');
		}
	});

});



