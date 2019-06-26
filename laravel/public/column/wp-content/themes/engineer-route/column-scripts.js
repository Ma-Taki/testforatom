jQuery(function($){
	// mobile-navi
	$(function () {
		// Create mobile element
		var mobile = document.createElement('div');
		mobile.className = 'nav-mobile';
		document.querySelector('.nav').appendChild(mobile);
		$('.wrap').prepend('<div class="overlay" id="js__overlay"></div>');

		// Mobile nav function
		var mobileNav = $('.nav-mobile');
		var toggle = $('.nav-list');
		mobileNav.click(function () {
			$(this).toggleClass('nav-mobile-open');
			toggle.toggleClass('nav-active');
			$('.overlay').toggleClass('overlay--show');
		});

		$('#js__overlay').click(function(){
			toggle.toggleClass('nav-active');
			mobileNav.removeClass('nav-mobile-open');
			$(this).removeClass('overlay--show');
		});
	});
});