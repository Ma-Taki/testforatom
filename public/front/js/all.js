
// sp_condition_search
jQuery(function($){
	$('.condition_name').each(function(){
		$(this).click(function(){
			var $slideArea = $(this).parents('.condition_content').find('.condition_slideArea');
			var $state = $(this).children('span');
			$slideArea.slideToggle(700, function(){
				if ($(this).is(':visible')) {
					$state.text('-');
				} else {
					$state.text('+');
				}
			})
		})
	})

	$('.sp_condition_search_btn button').click(function(){
		window.location.href = setParameter(getParameter());
	})

	function setParameter( paramsArray ) {
		var resurl = 'http://ts.engineer-route.com/item/search/condition'
        //var resurl = location.href.replace(/\?.*$/,"");
        for ( key in paramsArray ) {
			if (key != 'page') {
				for (i in paramsArray[key]) {
            		resurl += (resurl.indexOf('?') == -1) ? '?':'&';
            		resurl += key + '=' + paramsArray[key][i]
				}
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
				if (paramItem[0] in paramsArray) {
					paramsArray[paramItem[0]].push(paramItem[1]);
				} else {
					paramsArray[paramItem[0]] = [paramItem[1]];
				}

        	}
    	}
    	return paramsArray;
    }
});
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

// Q&A
jQuery(function($){
	$('.anserElement').hide();
	$(".questionText").click(function(){
		var $ansr = $(this).parents('.question').find('.anserElement');
		var $mark = $(this).parents('.questionElement').find('.questionOpen');
		$ansr.slideToggle('normal', function(){
			if($ansr.is(':hidden')) {
				$mark.text('+');
			} else {
				$mark.text('-');
			}
		})
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

jQuery(function($){
	$('.topJobInr').tile();
});

// smart-phone categoty
jQuery(function($){
	if (window.matchMedia( 'screen and (max-width: 640px)' ).matches) {
		$('.childCategories').hide();
		$('.parentCategory').click(function (){
			var $parent = $(this);
			$('.childCategories:visible').each(function(){
				if($(this).get(0) != $parent.parent().find('.childCategories').get(0)){
					$(this).slideUp('fast', function(){
						$(this).parent().find('.parentCategory span').text('+');
					});
				}
			});
			var $childs = $(this).parent().find('.childCategories');
			$childs.slideToggle('fast', function(){
				if ($(this).is(':visible')) {
					$(this).parent().find('.parentCategory span').text('-');
				} else {
					$(this).parent().find('.parentCategory span').text('+');
				}
			});
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
				var addValue;
				if (window.matchMedia( 'screen and (max-width: 640px)' ).matches) {
					// sp用html
					addValue = $('<li><p>' + chkBox_label + '</p><span id="' + chkBox_label + '">×</span></li>');
				} else {
					// pc tab用html
					addValue = $('<li>' + chkBox_label + '<span id="' + chkBox_label + '">×</span></li>');
				}

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

// itemDetail init selectedTagBox
jQuery(function($){
	var params = getParameter();
	$('.srchCndtns_chkBx').each(function(){
		if ('skills[]' in params) {
			for (var i = 0; i < params['skills[]'].length; i++) {
				if ('skills[]' === $(this).prop('name')
					&& params['skills[]'][i] == $(this).val()){
					$(this).trigger("click");
				}
			}
		}
		if ('sys_types[]' in params) {
			for (var i = 0; i < params['sys_types[]'].length; i++) {
				if ('sys_types[]' === $(this).prop('name')
					&& params['sys_types[]'][i] == $(this).val()){
						$(this).trigger("click");
				}
			}
		}
		if ('biz_categories[]' in params) {
			for (var i = 0; i < params['biz_categories[]'].length; i++) {
				if ('biz_categories[]' === $(this).prop('name')
					&& params['biz_categories[]'][i] == $(this).val()){
						$(this).trigger("click");
				}
			}
		}
		if ('areas[]' in params) {
			for (var i = 0; i < params['areas[]'].length; i++) {
				if ('areas[]' === $(this).prop('name')
					&& params['areas[]'][i] == $(this).val()){
						$(this).trigger("click");
				}
			}
		}
		if ('job_types[]' in params) {
			for (var i = 0; i < params['job_types[]'].length; i++) {
				if ('job_types[]' === $(this).prop('name')
					&& params['job_types[]'][i] == $(this).val()){
						$(this).trigger("click");
				}
			}
		}
	})

	$('.srchCndtns_radio').each(function(){
		if ('search_rate' in params) {
			for (var i = 0; i < params['search_rate'].length; i++) {
				if ('search_rate' === $(this).prop('name')
					&& params['search_rate'][i] == $(this).val()){
						$(this).trigger("change");
				}
			}
		}
	})

	function getParameter(){
    	var paramsArray = [];
    	var url = decodeURI(location.href);
    	parameters = url.split("#");
    	if( parameters.length > 1 ) {
        	url = parameters[0];
    	}
    	parameters = url.split("?");
    	if( parameters.length > 1 ) {
        	var params   = parameters[1].split("&");
        	for ( i = 0; i < params.length; i++ ) {
           		var paramItem = params[i].split("=");
				if (paramItem[0] in paramsArray) {
					paramsArray[paramItem[0]].push(paramItem[1]);
				} else {
					paramsArray[paramItem[0]] = [ paramItem[1] ];
				}
        	}
    	}

    	return paramsArray;
    }
});

// read more
jQuery(function($){

	var morePage = {
		load: false,
		hide: function() {
			$('#sp_morePage').hide();
		},
		moreText: function() {
			$('#sp_morePage').text('もっと見る');
		}
	}
	var nextPage = 2;

	$('#sp_morePage').click(function(){
		if (morePage.loading) return;
		$(this).text("読込中です");

		var url = location.href;
		params = url.split("?");
		spparams = '';
		if( params.length > 1 ) {
			spparams = params[1].split("&");
		}

		var paramArray = [];
		for ( i = 0; i < spparams.length; i++ ) {
			vol = spparams[i].split("=");
			paramArray.push(vol[0]);
			paramArray[vol[0]] = vol[1];
		}
		morePage.loading = true;
		$.ajax({
			type: 'get',
			url: '/item/search/readmore',
			dataType : 'json',
			data: {'order': paramArray['order'],
				   'limit': paramArray['limit'],
				   'page': nextPage,
				   'path': location.pathname},
			success: function(data) {
				// DOMツリー作成
				for(var i in data['items']){
					var $item_dom =
					$('<div class="item">' +
					  '<div class="itemHeader">' +
					  '<div class="table-row">' +
					  '<p class="name">' + data['items'][i].name +
					  '<span class="sys_type">' + data['items'][i].biz_category_name +
					  '</span></p></div></div>' +
					  '<div class="itemInfo clear">' +
					  '<div class="itemInfoInr">' +
					  '<div class="pickUp">' +
					  '<div class="pickUpRate">' +
					  '<div class="rate"><p>報　酬</p></div>' +
					  '<div class="rate_detail">' +
					  '<p>' + data['items'][i].rate_detail +
					  '</p></div></div>' +
					  '<div class="pickUpArea">' +
					  '<div class="area"><p>エリア</p></div>' +
					  '<div class="area_detail">' +
					  '<p>' + data['items'][i].area_detail +
					  '</p></div></div></div>' +
					  '<div class="other">' +
					  '<p class="otherName">システム種別</p>' +
					  '<p class="otherValue">' + data['items'][i].sys_type +
					  '</p></div>' +
					  '<p class="otherName">ポジション</p>' +
					  '<p class="otherValue">' + data['items'][i].job_type +
					  '</p></div>' +
					  '<p class="detail">'+ data['items'][i].detail + '</p>' +
					  '<div class="commonCenterBtn">' +
					  '<a href="/item/detail?id=' + data['items'][i].id +'">' +
					  '<button><p>詳細を見る<p></button></a>' +
					  '</div></div></div></div>');
					  /*  登録が時間単位で一週間以内は新着 */
					  if (data['items'][i].new_item_flg) {
						  var $p_new = $('<p class="new">新着</p>');
						  $item_dom.find('.table-row').prepend($p_new);
					  }
					  $('#itemList').append($item_dom);
				  }
				nextPage++;
				if (!data['hasMorePages']) {
					morePage.hide();
				}


			}
	   }).done(function() {
		   morePage.loading = false;
		   morePage.moreText();
	   });
	   return false;
   })
});
