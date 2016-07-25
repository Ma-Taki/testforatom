
// .qaMark resize
jQuery(function($){
	$(".qaMark").each(function (){
		$(this).height($(this).width());
	});
	$( window ).resize(function(){
		$(".qaMark").each(function (){
			$(this).height($(this).width());
		});
	});
});

// Q&A
jQuery(function($){
	$('.anserElement').hide();
	$('span.clickText').each(function (){
		$(this).click(function (){
			var anser = $(this).parents('.question').find('.anserElement');
			var openMark = $(this).parents('.questionElement').find('.questionOpen')
			anser.toggle();
			if (anser.is(':visible')) {
				openMark.text('-');
			} else {
				openMark.text('+');
			}
		});
	})
});

jQuery(function($){
	$('.topJobInr').tile();
});

// smart-phone categoty
jQuery(function($){
	if (window.matchMedia( 'screen and (max-width: 640px)' ).matches) {
		$('.childCategory').hide();
		$('.parentCategory').click(function (){
			var childCategories = $(this).parent('a').parent('ul').find('.childCategory');
			childCategories.each(function (){
				$(this).toggle();
			});
			return false;
		});
	}
});

// tabMenu
jQuery(function($){
    var tabmenu = $('.tabMenu ul li');
    $('.tabBox .tabBoxInr').hide().eq(0).show();
    tabmenu.eq(0).addClass('navhit');
    tabmenu.click(function () {
        var no = $(this).parent().children().index(this);
        tabmenu.removeClass('navhit');
        $(this).addClass('navhit');
        $('.tabBox .tabBoxInr').hide().eq(no).show();
    });
});

// selectedTags radio-button
jQuery(function($){
	$('.srchCndtns_radio').change(function (){
        if ($('#cndtns_rate')[0]) {
            $('#cndtns_rate').parent('li').remove();
        }
        if ($(this).val() != 0) {
            var addText = $(this).parent('label').text();
            var addValue = $('<li>' + addText + '<span id="cndtns_rate" >×</span></li>');
            addValue.children('span').click(function(){
                $(this).parent('li').remove();
                $('.srchCndtns_radio').each(function(){
                    if($(this).val() == 0){
                        $('#tagSelectedRate').hide();
                        $(this).prop('checked', true);
                    }
                });
            });
            $('#tagSelectedRate').show();
            addValue.appendTo('#tagSelectedRate ul');
        } else {
            $('#tagSelectedRate').hide();
        }
    });
});

// selectedTags check-box
jQuery(function($){
    $('.srchCndtns_chkBx').each(function(){
        $(this).click(function(){
            var click_chkBox = $(this);
            var chkBox_label = $(this).parent('label').text();
            var selected_cndtns_type;
            switch (click_chkBox.attr('name')) {
                case 'skills[]': selected_cndtns_type = $('#tagSelectedSkill'); break;
                case 'sys_types[]': selected_cndtns_type = $('#tagSelectedSysType'); break;
                case 'biz_categories[]': selected_cndtns_type = $('#tagSelectedBizCategory'); break;
                case 'areas[]': selected_cndtns_type = $('#tagSelectedArea'); break;
                case 'job_types[]': selected_cndtns_type = $('#tagSelectedPosition'); break;
            }
            if($(this).prop('checked')){
                var addValue = $('<li>' + chkBox_label + '<span id="' + chkBox_label + '">×</span></li>');
                addValue.children('span').click(function(){
                    $(this).parent('li').remove();
                    if (selected_cndtns_type.find('li').length <= 0) {
                        selected_cndtns_type.hide();
                    }
                    click_chkBox.prop('checked', false);
                });
                selected_cndtns_type.show();
                addValue.appendTo(selected_cndtns_type.children('ul'));

            } else {
                selected_cndtns_type.find('li').each(function(){
                    if($(this).children('span').attr('id') == chkBox_label){
                        $(this).remove();
                        if (selected_cndtns_type.find('li').length <= 0) {
                            selected_cndtns_type.hide();
                        }
                    }
                });
            }
        });
    });
});

// current page
$(function(){
	$('.nav li a').each(function(){
		var $href = $(this).attr('href');
		if(location.href.match($href)) {
			$(this).parent('li').addClass('active');
		} else {
			$(this).parent('li').removeClass('active');
		}
	});
});

// btn-menu
$(document).ready(function() {
	$('.menu-trigger').on('click', function() {
		$(this).toggleClass('active');
		return false;
	});
});


// mobile-navi
$(function () {
	// Create mobile element
	var mobile = document.createElement('div');
	mobile.className = 'nav-mobile';
	document.querySelector('.nav').appendChild(mobile);

	// hasClass
	function hasClass(elem, className) {
		return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
	}

	// toggleClass
	function toggleClass(elem, className) {
		var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
		if (hasClass(elem, className)) {
			while (newClass.indexOf(' ' + className + ' ') >= 0) {
				newClass = newClass.replace(' ' + className + ' ', ' ');
			}
			elem.className = newClass.replace(/^\s+|\s+$/g, '');
		} else {
			elem.className += ' ' + className;
		}
	}

	// Mobile nav function
	var mobileNav = document.querySelector('.nav-mobile');
	var toggle = document.querySelector('.nav-list');
	mobileNav.onclick = function () {
		toggleClass(this, 'nav-mobile-open');
		toggleClass(toggle, 'nav-active');
	};
});
