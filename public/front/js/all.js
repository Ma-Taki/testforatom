
// itemDetail tab toggle
jQuery(function($){
	$(".openTab").parents('.search').find('.tab').hide();
	$(".openTab").click(function(){
    	var tabBox = $(this).parents('.search').find('.tab');
		tabBox.slideToggle(700, function(){
			if (tabBox.is(':visible')) {
				$(".openTab").text('−　タブを閉じる');
			} else {
				$(".openTab").text('＋　検索条件を変更する');
			}
		});
	});
});


jQuery(function($){
	$(".clickText").parents('.question').find('.anserElement').hide();
	$(".clickText").click(function(){
		$(this).parents('.question').find('.anserElement').slideToggle();
		$(this).parents('.questionElement').find('.questionOpen').text('+')
	});
});

//　itemSearch
jQuery(function($){

	$('#order').change(function (){
		var params = getParameter();
        params['order'] = $(this).val();
        window.location.href = setParameter(params);
    })

	$('#limit').change(function (){
		var params = getParameter();
        params['limit'] = $(this).val();
        window.location.href = setParameter(params);
	})

    function setParameter( paramsArray ) {
        var resurl = location.href.replace(/\?.*$/,"");
        for ( key in paramsArray ) {
			if (key != 'page') {
            	resurl += (resurl.indexOf('?') == -1) ? '?':'&';
            	resurl += key + '=' + paramsArray[key];
			}
        }
        return resurl;
    }

    function getParameter(){
    	var paramsArray = [];
    	var url = location.href;
    	parameters = url.split("#");
    	if( parameters.length > 1 ) {
        	url = parameters[0];
    	}
    	parameters = url.split("?");
    	if( parameters.length > 1 ) {
        	var params   = parameters[1].split("&");
        	for ( i = 0; i < params.length; i++ ) {
           		var paramItem = params[i].split("=");
           		paramsArray[paramItem[0]] = paramItem[1];
        	}
    	}
    	return paramsArray;
    }
});

// slick-slider
jQuery(function($) {
	if (window.matchMedia( 'screen and (max-width: 640px)' ).matches) {
		$('.slider-item').slick({
			arrows: false,            // 前へ/次へナビ
			infinite: true,           // 無限ループ
			dots:false,               // カレントナビ(ドット)
			slidesToShow: 1,          // 見えているスライド数
			centerMode: true,         // 中央寄せ
			centerPadding:'20px',     // 両サイドの見えている部分のサイズ
			autoplay:true,            // 自動再生
		});
	} else {
		$('.slider-item').slick({
			arrows: true,
			infinite: true,
			dots:true,
			slidesToShow: 1,
			centerMode: true,
			centerPadding:'150px',
			autoplay:true,
		});
	};
});

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
/*
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
*/

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
		var href = $(this).attr('href');
		var locHref = location.href.replace(/\/$/g, '')
		if (href === locHref) {
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
