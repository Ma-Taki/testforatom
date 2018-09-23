// sp_condition_search
jQuery(function($){

	$('.condition_name').each(function(){
		$(this).click(function(){
			var $slideArea = $(this).parents('.condition_content').find('.condition_slideArea');
			var $state = $(this).children('span');
			$slideArea.slideToggle('fast', function(){
				if ($(this).is(':visible')) {
					$state.text('-');
				} else {
					$state.text('+');
				}
			})
		})
	})

	$('.add-conditions button').click(function(){
		window.location.href = setParameter(getParameter());
	})

	function setParameter( paramsArray ) {
		var resurl = '/item/search/condition';
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

//　itemSearch sort
jQuery(function($){

	$('#order').change(function (){
		var params = getParameter();
        params['order'] = [$(this).val()];
        window.location.href = setParameter(params);
    })

	$('#limit').change(function (){
		var params = getParameter();
        params['limit'] = [$(this).val()];
        window.location.href = setParameter(params);
	})

    function setParameter( paramsArray ) {
        var resurl = location.href.replace(/\?.*$/,"");
        for ( key in paramsArray ) {
			// 必ず1ページ目から表示するためpageは除く
			if (key != 'page') {
            	resurl += (resurl.indexOf('?') == -1) ? '?':'&';
				for (var i = 0; i < paramsArray[key].length; i++) {
					resurl += (i > 0) ? '&' : '';
					resurl += key + '=' + paramsArray[key][i];
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
		// http......item/search? の状態でもlengthが2のため空要素でないかもチェック
		if( parameters.length > 1 && parameters[1] != '') {
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

// カテゴリー検索（スマホ）
jQuery(function($){

	if (window.matchMedia( 'screen and (max-width: 640px)' ).matches) {
		$('.js__category-childs').hide();
		$('div.category__parent').click(function (){
			var $parent = $(this);
			var $arrow = $parent.parent().find('div.category__parent span');
			$('.js__category-childs:visible').each(function(){
				if($(this).get(0) != $parent.parent().find('.js__category-childs').get(0)){
					$(this).parent().find('div.category__parent span').toggleClass('js__arrow--open');
					$(this).slideUp('fast');
				}
			});
			$parent.parent().find('.js__category-childs').slideToggle('fast');
			$arrow.toggleClass('js__arrow--open');
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
					// sp用DOM
					addValue = $('<li><p>' + chkBox_label + '</p><span id="' + chkBox_label + '">×</span></li>');
				} else {
					// pc tab用DOM
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


//検索条件をすべてリセットする
$(function(){
	$("#check-all-clear-btn").on("click",function(){
		//すべてのチェックボックスをオフにする
		$(".srchCndtns_chkBx,.srchCndtns_radio").prop("checked",false);
		//選択中ボックスに追加されたすべての条件を削除
		$(".searchElement > div > ul").empty();
		//選択中ボックスを非表示
		$(".searchElement > div").hide();
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
						$(this).trigger("click");
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

							paramItem[0] = paramItem[0].replace(/\[.*\]/,"[]");

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
			$('#js__read-more').hide();
		},
		moreText: function() {
			$('#js__read-more').text('もっと見る');
		}
	}
	var nextPage = 2;

	$('#js__read-more').click(function(){
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

				// DOM作成
				for(var i in data['items']){
					var $item_dom =
					$('<div class="item">' +
					  '<div class="itemHeader">' +
					  '<div class="table-row">' +
					  '<p class="name">' + data['items'][i].name +
					  '<p class="sys_type">' + data['items'][i].biz_category_name +
					  '</p></p></div></div>' +
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
						'<div class="other">' +
					  '<p class="otherName">ポジション</p>' +
					  '<p class="otherValue">' + data['items'][i].job_type +
					  '</p></div>' +
					  '<p class="detail">'+ data['items'][i].detail + '</p>' +
					  '<div class="cmmn-btn">' +
					  '<a href="/item/detail?id=' + data['items'][i].id + '" target="_blank">詳細を見る</a>' +
						'<a href="javascript:void(0);" class="consider-btn" name="'+ data['items'][i].id +'">検討する</a>' +
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

jQuery(function($){

	//検討中[登録]ボタン
	$(document).on("click",".consider-btn",function(){

		var self = $(this);
		var url = window.location.href;

		if(!self.hasClass('registrated')){

			self.text("登録中...");

			$.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

			$.ajax({
				type: "POST",
				url: "/considers/register",
				data: {
					item_id : self.attr("name") //案件id
				},
				success: function(data){
					if(url.match(/detail\?id\=/) != null){
						self.removeClass("consider-btn").addClass("consider_delete-btn").text("この案件を検討中から外す");
					}else{
						self.addClass("registrated").text("検討中");
					}
					$("#considers_length").text(data);
				},
				error: function(XMLHttpRequest,textStatus, errorThrown){
					self.text("検討する");
					alert("通信に失敗しました。もう一度ボタンを押してください。");
				}
			});
			return false;
		}
	});

	//検討中[削除]ボタン
	$(document).on("click",'.consider_delete-btn',function(){

	  var self = $(this);
		var url = window.location.href ;

		if(url.match(/detail\?id\=/) == null){
			var html = self.parent().parent().parent().parent();
			html.fadeOut('fast', function() { $(this).remove(); });
		}

	  $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

	  $.ajax({
	    type: "POST",
	    url: "/considers/delete",
	    data: {
	      item_id : self.attr("name") //案件id
	    },
	    success: function(data){

				if(url.match(/detail\?id\=/) != null){
					self.removeClass("consider_delete-btn").addClass("consider-btn").text("検討する");
				}
				$("#considers_length").text(data);
	      if(Number(data)　==　0) $("#no_consider_message").text("検討している案件はありません");
	    },
	    error: function(XMLHttpRequest,textStatus, errorThrown){
	      alert("通信に失敗しました。もう一度ボタンを押してください。");
	    }
	  });
	  return false;
	});

	//~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
	//
	//履歴書・職務経歴書・スキルシートのアップロード処理
	//
	//~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
	//=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*
	//新規登録のとき
	//=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*
	//後で登録、または今登録を選択したときの表示
	$('[name="resume"]:radio').change( function() {
		switch($(this).val()){
	        case "later" : 	laterDisplay();
	        				break;
	        case "now"   : 	nowDisplay();
	        				break;
	        default		 :  break;
    	}
	});

	//新規登録画面のドラッグ&ドロップ、またはファイル選択、またはメールを選択したときの表示
	if('/user/regist' == $(location).attr('pathname') && 'ticket' == $(location).attr('search').substr(1,6)){
		$('[name="file_type"]:radio').change( function() {
			switch($(this).val()){
		        case "user_input_dd":  	ddDisplay();
		        						break;
		        case "user_input_fe":   feDisplay();
		        						break;
		        case "user_input_fma":  fmaDisplay();
		        						break;
		        default:   break;
			}
		});

		//セッション値があるとき
		if(window.sessionStorage.getItem('resumeCheck')){
			//後で登録・今登録
			switch(window.sessionStorage.getItem('resumeCheck')){
		        case "later": 	laterDisplay();
		        				$('input[name="resume"]:eq(0)').prop('checked', true);
		        				window.sessionStorage.removeItem('file');
		        				break;
		        case "now"  : 	nowDisplay();
		        				$('input[name="resume"]:eq(1)').prop('checked', true);
				    			break;
		        default		: 	break;
	    	}

			//ドラッグ&ドロップ・ファイル選択・メール
			switch(window.sessionStorage.getItem('file')){
		        case "user_input_dd":   ddDisplay();
				        				$('input[name="file_type"]:eq(0)').prop('checked', true);
				        				break;

		        case "user_input_fe":   feDisplay();
				        				$('input[name="file_type"]:eq(1)').prop('checked', true);
				        				break;

		        case "user_input_fma": 	fmaDisplay();
				        				$('input[name="file_type"]:eq(2)').prop('checked', true);
				        				break;

	        	default    			: 	break;
			}
		}
	}

	//登録URLのとき
	if('/user/regist' == $(location).attr('pathname') && '' == $(location).attr('search').substr(1,7)){
		//セッションデータがあれば削除
		if(window.sessionStorage.getItem('resumeCheck')){
			window.sessionStorage.removeItem('resumeCheck');
		}
		if(window.sessionStorage.getItem('file')){
			window.sessionStorage.removeItem('file');
		}
	}

	//「後で登録」を選択したときの表示
	function laterDisplay(){
		$('.input_resume_type').css('display', 'none');
		$('.ddrop_files').css('display', 'none');
		$('.explorer_files').css('display', 'none');
		$('.mail_files').css('display', 'none');
		$('.resume-note').css('display', 'none');
		window.sessionStorage.setItem('resumeCheck','later');
	}

	//「今登録」を選択したときの表示
	function nowDisplay(){
		$('.input_resume_type').css('display', 'block');
		$('.ddrop_files').css('display', 'none');
		$('.explorer_files').css('display', 'none');
		$('.mail_files').css('display', 'none');
		$('.resume-note').css('display', 'block');
		window.sessionStorage.setItem('resumeCheck','now');
	}

	//「ドラッグ&ドロップ」を選択したときの表示
	function ddDisplay(){
		$('.ddrop_files').css('display', 'block');
		$('.explorer_files').css('display', 'none');
		$('.mail_files').css('display', 'none');
		$('.resume-note').css('display', 'block');
		window.sessionStorage.setItem('file','dd');
	}

	//「ファイルを選択する」を選択したときの表示
	function feDisplay(){
		$('.ddrop_files').css('display', 'none');
		$('.explorer_files').css('display', 'block');
		$('.mail_files').css('display', 'none');
		$('.resume-note').css('display', 'block');
		window.sessionStorage.setItem('file','fe');
	}

	//「メール」を選択したときの表示
	function fmaDisplay(){
		$('.ddrop_files').css('display', 'none');
		$('.explorer_files').css('display', 'none');
		$('.mail_files').css('display', 'block');
		$('.resume-note').css('display', 'block');
		window.sessionStorage.setItem('file','fma');
	}

	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	//ユーザー登録・同意して会員登録ボタンを押したとき
	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$('form[name="js-user-input-form"]').submit(function(e){
		var file_type = '';
		var resume = '';
		// HTMLでの送信をキャンセル
		e.preventDefault();

		//お名前(性)
		fd.append('last_name', $('[name=last_name]').val());
		//お名前(名)
		fd.append('first_name', $('[name=first_name]').val());
		//お名前(せい)
		fd.append('last_name_kana', $('[name=last_name_kana]').val());
		//お名前(めい)
		fd.append('first_name_kana', $('[name=first_name_kana]').val());
		//性別
		if(!$('input[name="gender"]:checked').val()){
			fd.append('gender', '');
		}else{
			fd.append('gender', $('input[name="gender"]:checked').val());
		}
		//生年月日
		fd.append('birth', $('[name=birth]').val());
		fd.append('birth_year', $('[name=birth_year]').val());
		fd.append('birth_month', $('[name=birth_month]').val());
		fd.append('birth_day', $('[name=birth_day]').val());
		//最終学歴
		fd.append('education', $('[name=education]').val());
		//国籍
		fd.append('country', $('[name=country]').val());
		//希望の契約形態
		$("[name='contract_types[]']:checked").map(function () {
			fd.append('contract_types_' + uploadCount + '[]', $(this).val());
		}).get();
		//住所（都道府県）
		fd.append('prefecture_id', $('[name=prefecture_id]').val());
		//最寄り駅
		fd.append('station', $('[name=station]').val());
		//電話番号
		fd.append('phone_num', $('[name=phone_num]').val());
		//メールアドレス
		fd.append('mail', $('[name=mail]').val());
		//パスワード
		fd.append('password', $('[name=password]').val());
		fd.append('password_confirmation', $('[name=password_confirmation]').val());
		//メールマガジン配信
		if(!$('input[name="magazine_flag_temp"]:checked').val()){
			//未選択
			fd.append('magazine_flag', 0);
		}else{
			//選択済
			fd.append('magazine_flag', 1);
		}
		//経歴書・職務経歴書・スキルシートの登録時期
		if(!$('input[name="resume"]:checked').val()){
			fd.append('resume', '');
		}else{
			fd.append('resume', $('input[name="resume"]:checked').val());
			resume = $('input[name="resume"]:checked').val();
		}

		//経歴書・職務経歴書・スキルシートの登録形式
		if(!$('input[name="file_type"]:checked').val()){
			//未選択
			fd.append('file_type', '');
		}else{
			//選択済
			fd.append('file_type', $('input[name="file_type"]:checked').val());
		}

		//「ファイルを選択」のとき
		file_type = $('input[name="file_type"]:checked').val();
		if(resume == 'now' && file_type == 'user_input_fe'){
			$(".input-file").each(function(i) {
				if(typeof $(this)[0].files[0] === "undefined") {
					fd.append('skillsheet_' + uploadCount + '[' + i + ']', '');
				}else{
					fd.append('skillsheet_' + uploadCount + '[' + i + ']', $(this)[0].files[0]);
				}
			});
		}

		//「ドラッグ&ドロップ」のとき
		if(resume == 'now' && file_type == 'user_input_dd'){
			if(0 < fileList.length){
				for(var i=0;i < fileList.length;i++){
					fd.append('skillsheet_' + uploadCount + '[' + i + ']', fileList[i]);
				}
			}else{
				fd.append('skillsheet_' + uploadCount + '[0]', '');
				fd.append('skillsheet_' + uploadCount + '[1]', '');
				fd.append('skillsheet_' + uploadCount + '[2]', '');
			}
		}
		fd.append('uploadCount', uploadCount);
		uploadCount++;
		fd.append('ticket', $('[name=ticket]').val());

		//Ajaxデータ送信
		$.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
		$.ajax({
        	url: '/user/regist',
            type: 'POST',
            data: fd,
            processData: false,
      		contentType: false,
      		cache: false,
           	dataType: 'json',
           	timeout: 10000,
        })
        .done(function(dataContent){
            for(var val in dataContent){
				switch(val){
                    case "custom_error_messages":
			        	var str = '<div class="alert alert-danger"><ul>';
			        	for(var item in dataContent[val]){
							str = str + '<li>' + dataContent[val][item] + '</li>';
			        	}
			        	str = str + '</ul></div>';
			        	$('div.alert_design').html(str);
			        	$('html,body').animate({scrollTop:0}, 'fast');
			        	break;
			        case "url":
			        	//画面遷移
                        window.location.assign(dataContent[val]);
                        break;
                    default:break;
                }
            }
        }).
        fail(function( jqXHR, textStatus, errorThrown ){
        	//422HTTPステータスコードのとき(バリデーションチェックで引っかかったとき)
        	if(jqXHR.status == 422){
	        	var str = '<div class="alert alert-danger"><ul>';
	        	var resJson = jqXHR.responseJSON;
	        	for(var item in resJson){
					str = str + '<li>' + resJson[item] + '</li>';
	        	}
	        	str = str + '</ul></div>';
	        	$('div.alert_design').html(str);
	        	$('html,body').animate({scrollTop:0}, 'fast');
        	}
        });
	});

	//=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*
	//エントリーするとき
	//=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*
	//ドラッグ&ドロップ、またはファイル選択、またはメールを選択したときの表示
	$('[name="file_type"]:radio').change( function() {
		if($('.upload-box').is(':hidden')){
			$('.upload-box').css('display', 'block');
		}
		switch($(this).val()){
	        case "entry_dd": 	$('.ddrop_files').css('display', 'block');
								$('.explorer_files').css('display', 'none');
								$('.mail_files').css('display', 'none');
        						break;

	        case "entry_fe":   	$('.ddrop_files').css('display', 'none');
								$('.explorer_files').css('display', 'block');
								$('.mail_files').css('display', 'none');
	        					break;

	        case "entry_fma":  	$('.ddrop_files').css('display', 'none');
								$('.explorer_files').css('display', 'none');
								$('.mail_files').css('display', 'block');
	        					break;

	        default     	:   break;
		}
	});

	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	//エントリー・この内容でエントリーするボタンを押したとき
	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$('form[name="js-entry-form"]').submit(function(e){
		var file_type = '';
		// HTMLでの送信をキャンセル
		e.preventDefault();

		//案件ID
		fd.append('item_id', $('[name=item_id]').val());
		//トークン
		fd.append('_token', $('[name=_token]').val());
		//経歴書・職務経歴書・スキルシートの登録形式
		if(!$('input[name="file_type"]:checked').val()){
			//未選択
			fd.append('file_type', '');
		}else{
			//選択済
			fd.append('file_type', $('input[name="file_type"]:checked').val());
			file_type = $('input[name="file_type"]:checked').val();
		}

		//「ファイルを選択」のとき
		file_type = $('input[name="file_type"]:checked').val();
		if(file_type == 'entry_fe'){
			$(".input-file").each(function(i) {
				if(typeof $(this)[0].files[0] === "undefined") {
					fd.append('skillsheet_' + uploadCount + '[' + i + ']', '');
				}else{
					fd.append('skillsheet_' + uploadCount + '[' + i + ']', $(this)[0].files[0]);
				}
			});
		}

		//「ドラッグアンドドロップ」のとき
		if(file_type == 'entry_dd'){
			if(0 < fileList.length){
				for(var i=0;i < fileList.length;i++){
					fd.append('skillsheet_' + uploadCount + '[' + i + ']', fileList[i]);
				}
			}else{
				fd.append('skillsheet_' + uploadCount + '[0]', '');
				fd.append('skillsheet_' + uploadCount + '[1]', '');
				fd.append('skillsheet_' + uploadCount + '[2]', '');
			}
		}
		fd.append('uploadCount', uploadCount);
		uploadCount++;

		//Ajaxデータ送信
     	$.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
		$.ajax({
        	url: '/entry',
            type: 'POST',
            data: fd,
            processData: false,
      		contentType: false,
      		cache: false,
           	dataType: 'json',
           	timeout: 10000,
        })
        .done(function(dataContent){
             for(var val in dataContent){
				switch(val){
                    case "custom_error_messages":
			        	var str = '<div class="alert alert-danger"><ul>';
			        	for(var item in dataContent[val]){
							str = str + '<li>' + dataContent[val][item] + '</li>';
			        	}
			        	str = str + '</ul></div>';
			        	$('div.alert_design').html(str);
			        	$('html,body').animate({scrollTop:0}, 'fast');
			        	break;
			        case "url":
			        	//画面遷移
                        window.location.assign(dataContent[val]);
                        break;
                    default:break;
                }
            }
        }).
        fail(function( jqXHR, textStatus, errorThrown ){
        	console.log(jqXHR, textStatus, errorThrown);
        });
	});

	//=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*
	//プロフィール変更のとき
	//=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*
	var tag_visible_count = 0;
	if('/user/edit' == $(location).attr('pathname') && '' == $(location).attr('search').substr(1,7)){
		//ファイルカウントを更新
		editUploadCount = $('.delete_btn').filter(':visible').length;

		//ドラッグ&ドロップ、またはファイル選択、またはメールを選択したときの表示
		$('[name="file_type"]:radio').change(function(){
			switch($(this).val()){
		        case "user_edit_dd" :  	$('.ddrop_files').css('display', 'block');
										$('.explorer_files').css('display', 'none');
										$('.mail_files').css('display', 'none');
		        						break;

		        case "user_edit_fe" :   $('.ddrop_files').css('display', 'none');
										$('.explorer_files').css('display', 'block');
										$('.mail_files').css('display', 'none');
										//表示しているスキルシートの個数
										tag_visible_count = $('.uploadfile_edit_frame').filter(':visible').length;
										var str = [];
										for(var i = 0; i <= 2 - tag_visible_count; i++){
									 		str[i] = '<div class="input-file-box"><div class="input-file-btn"><p>ファイルを選択</p><input type="file" class="input-file"></div><span>選択されていません</span></div>';
										}
										$('.explorer_files').html(str);
										
		        						break;

		        case "user_edit_fma":   $('.ddrop_files').css('display', 'none');
										$('.explorer_files').css('display', 'none');
										$('.mail_files').css('display', 'block');
		        						break;

		        default     		:   break;
    		}
		});
	}

	$('.delete_btn').each(function(){
		//アップロードファイル　削除ボタンをクリックしたとき
		$(this).click(function(e){
			// HTMLでの送信をキャンセル
			e.preventDefault();
			var result = confirm('削除しますか？');
			if(result){
				var str = '';
				$(this).parent().css('display', 'none');
				//ラジオボタン
				if($('.input_resume_type_edit').is(':hidden')){
					$('.input_resume_type_edit').css('display', 'block');
				}
				//説明文
				if($('.resume-note-edit').is(':hidden')){
					$('.resume-note-edit').css('display', 'block');
					$('.resume-note').css('display', 'none');
				}

				switch($('input[name="file_type"]:checked').val()){
			        case "user_edit_dd" :  	if($('.ddrop_files').is(':hidden')){
												$('.ddrop_files').css('display', 'block');
											}
			        						break;

			        case "user_edit_fe" :   if($('.explorer_files').is(':hidden')){
												$('.explorer_files').css('display', 'block');
											}
											//表示しているスキルシートの個数
											tag_visible_count = $('.uploadfile_edit_frame').filter(':visible').length;
											var str = [];
											for(var i = 0; i <= 2 - tag_visible_count; i++){
									 			str[i] = '<div class="input-file-box"><div class="input-file-btn"><p>ファイルを選択</p><input type="file" class="input-file"></div><span>選択されていません</span></div>';
									 		}
									 		$('.explorer_files').html(str);
			        						break;

			        case "user_edit_fma":   if($('.mail_files').is(':hidden')){
												$('.mail_files').css('display', 'block');
											}
			        						break;

			        default     		:   break;
				}		
			}
			//ファイルカウントを更新
			editUploadCount = $('.delete_btn').filter(':visible').length;
		})
	})

	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	//プロフィール変更・変更内容を登録するボタンを押したとき
	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$('form[name="userForm"]').submit(function(e){
		//スキルシートアップのみ
		var skillsheet_upOnly  = ['','',''];
		//スキルシート全部
		var skillsheet_all = ['','',''];
		var file_type = '';

		// HTMLでの送信をキャンセル
		e.preventDefault();

		//お名前(性)
		fd.append('last_name', $('[name=last_name]').val());
		//お名前(名)
		fd.append('first_name', $('[name=first_name]').val());
		//お名前(せい)
		fd.append('last_name_kana', $('[name=last_name_kana]').val());
		//お名前(めい)
		fd.append('first_name_kana', $('[name=first_name_kana]').val());
		//性別
		if(!$('input[name="gender"]:checked').val()){
			fd.append('gender', '');
		}else{
			fd.append('gender', $('input[name="gender"]:checked').val());
		}
		//生年月日
		fd.append('birth', $('[name=birth]').val());
		fd.append('birth_year', $('[name=birth_year]').val());
		fd.append('birth_month', $('[name=birth_month]').val());
		fd.append('birth_day', $('[name=birth_day]').val());
		//最終学歴
		fd.append('education', $('[name=education]').val());
		//国籍
		fd.append('country', $('[name=country]').val());
		//希望の契約形態
		$("[name='contract_types[]']:checked").map(function () {
			fd.append('contract_types_' + uploadCount + '[]', $(this).val());
		}).get();
		//住所（都道府県）
		fd.append('prefecture_id', $('[name=prefecture_id]').val());
		//最寄り駅
		fd.append('station', $('[name=station]').val());
		//電話番号
		fd.append('phone_num', $('[name=phone_num]').val());
		//メールマガジン配信
		if(!$('input[name="magazine_flag_temp"]:checked').val()){
			//未選択
			fd.append('magazine_flag', 0);
		}else{
			//選択済
			fd.append('magazine_flag', 1);
		}
		//経歴書・職務経歴書・スキルシートの登録形式
		if(!$('input[name="file_type"]:checked').val()){
			//未選択
			fd.append('file_type', '');
		}else{
			//選択済
			fd.append('file_type', $('input[name="file_type"]:checked').val());
			file_type = $('input[name="file_type"]:checked').val();
		}
		
		//表示されているスキルシート
		for(var i=0;i<3;i++){
			if ($('#filename' + i).is(':visible')) {
		    	skillsheet_all[i] = $('#filename' + i).data('file');
			}
		}

		//「ファイルを選択」のとき
		if(file_type == 'user_edit_fe'){
			$(".input-file").each(function(i) {
				if(typeof $(this)[0].files[0] === "undefined") {
				}else{
					//スキルシート全部・スキルシートアップのみに追加
					if($.isEmptyObject(skillsheet_all[i])){
						skillsheet_all[i] = $(this)[0].files[0];
						if($.isEmptyObject(skillsheet_upOnly[i])){
							skillsheet_upOnly[i] = $(this)[0].files[0];
						}
					}else if($.isEmptyObject(skillsheet_all[i+1])){
						skillsheet_all[i+1] = $(this)[0].files[0];
						if($.isEmptyObject(skillsheet_upOnly[i+1])){
							skillsheet_upOnly[i+1] = $(this)[0].files[0];
						}
					}else if($.isEmptyObject(skillsheet_all[i+2])){
						skillsheet_all[i+2] = $(this)[0].files[0];
						if($.isEmptyObject(skillsheet_upOnly[i+2])){
							skillsheet_upOnly[i+2] = $(this)[0].files[0];
						}
					}
				}
			});
		}

		//「ドラッグアンドドロップ」のとき、かつファイルがあるとき
		if(file_type == 'user_edit_dd' && 0 < fileList.length){
			for(var i=0;i < fileList.length;i++){
				//スキルシート全部・スキルシートアップのみに追加
				if($.isEmptyObject(skillsheet_all[i])){
					skillsheet_all[i] = fileList[i];
					if($.isEmptyObject(skillsheet_upOnly[i])){
						skillsheet_upOnly[i] = fileList[i];
					}
				}else if($.isEmptyObject(skillsheet_all[i+1])){
					skillsheet_all[i+1] = fileList[i];
					if($.isEmptyObject(skillsheet_upOnly[i+1])){
						skillsheet_upOnly[i+1] = fileList[i];
					}
				}else if($.isEmptyObject(skillsheet_all[i+2])){
					skillsheet_all[i+2] = fileList[i];
					if($.isEmptyObject(skillsheet_upOnly[i+2])){
						skillsheet_upOnly[i+2] = fileList[i];
					}
				}
			}
		}

		//スキルシートの値を入れていく
		for(var i=0;i<3;i++){
			//アップのみ
			fd.append('skillsheet_upOnly_' + uploadCount + '[' + i + ']', skillsheet_upOnly[i]);
			//全部
			fd.append('skillsheet_all_' + uploadCount + '[' + i + ']', skillsheet_all[i]);
		}
		fd.append('uploadCount', uploadCount);
		uploadCount++;
		
		//Ajaxデータ送信
     	$.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
		$.ajax({
        	url: '/user/edit',
            type: 'POST',
            data: fd,
            processData: false,
      		contentType: false,
      		cache: false,
           	dataType: 'json',
           	timeout: 10000,
        })
        .done(function(dataContent){
             for(var val in dataContent){
				switch(val){
                    case "custom_error_messages":
			        	var str = '<div class="alert alert-danger"><ul>';
			        	for(var item in dataContent[val]){
							str = str + '<li>' + dataContent[val][item] + '</li>';
			        	}
			        	str = str + '</ul></div>';
			        	$('div.alert_design').html(str);
			        	$('html,body').animate({scrollTop:0}, 'fast');
			        	break;
			        case "url":
			        	//画面遷移
                        window.location.assign(dataContent[val]);
                        break;
                    default:break;
                }
            }
        }).
        fail(function( jqXHR, textStatus, errorThrown ){
        	//422HTTPステータスコードのとき(バリデーションチェックで引っかかったとき)
        	if(jqXHR.status == 422){
	        	var str = '<div class="alert alert-danger"><ul>';
	        	var resJson = jqXHR.responseJSON;
	        	for(var item in resJson){
					str = str + '<li>' + resJson[item] + '</li>';
	        	}
	        	str = str + '</ul></div>';
	        	$('div.alert_design').html(str);
	        	$('html,body').animate({scrollTop:0}, 'fast');
        	}
        });
	});

	//~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
	//
	//履歴書・職務経歴書・スキルシート
	//ドラッグ&ドロップしたときの処理
	//
	//~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
	if( window.FormData ){
		var fd = new FormData();
	}
	var fileCount = 0;
	var objDdField = $("#dragandrop");
	//ファイルデータ送信用配列
	var fileList = [];
	var ramdomList = [];
	var uploadCount = 0;
	//プロフィール変更のときのアップロードファイルカウント
	var editUploadCount = 0;

	//ドロップ領域に入ったとき
	objDdField.on('dragenter', function (e){
	    $(this).css('background-color', '#F8F3F0');
	    $(this).css('border', '1px solid #D46363');
	    $(this).css('color', '#D46363');
	   return false;
	});

	//ドラッグ＆ドロップ必須
	objDdField.on('dragover', function (e){
		e.preventDefault();
	    return false;
	});

	//領域内にドロップされたとき
	objDdField.on('drop', function (_e){
		$(this).css('background-color', '#eef3f4');
		$(this).css('border', '1px solid #5e8796');
		$(this).css('color', '#5e8796');
		
	    var e = _e;
        if( _e.originalEvent ){
            e = _e.originalEvent;
        }
       	e.stopPropagation();
        e.preventDefault();
        var dt = e.dataTransfer;
        var files = dt.files;

        $('.registry-upload-dd__txt').css('display', 'none');
        //領域内にファイル情報を表示
	   	addWaitList(files, editUploadCount);
	});

	$(document).on('dragenter', function (e) {
    	return false;
	});
	$(document).on('dragover', function (e) {
		return false;
	});
	$(document).on('drop', function (e) {
		return false;
	});

	//領域内にファイル情報を表示
	function addWaitList(files, editUploadCount){
		//プロフィール変更のとき
		if('/user/edit' == $(location).attr('pathname') && '' == $(location).attr('search').substr(1,7)){
			for(var i=0;i<files.length;i++){
				if(fileList.length + editUploadCount < 3){
					//ファイルデータ送信用配列に追加
					fileList.push(files.item(i));
					var addIndex = fileList.indexOf(files.item(i));
					var status = new createStatusbar($("#dd_text"), addIndex);
					//ファイルサイズを表示
				    status.setFileNameSize(files[i].name, files[i].size);
				    //削除ボタンを押したときの処理
				    status.setAbort($.ajax());
				    $('#js-dd-uploaded').hide();
				}
			}
		}else{
			for(var i=0;i<files.length;i++){
				if(fileList.length < 3){
					//ファイルデータ送信用配列に追加
					fileList.push(files.item(i));
					var addIndex = fileList.indexOf(files.item(i));
					var status = new createStatusbar($("#dd_text"), addIndex);
					//ファイルサイズを表示
				    status.setFileNameSize(files[i].name, files[i].size);
				    //削除ボタンを押したときの処理
				    status.setAbort($.ajax());
				}
			}
		}
	}
	
	//ドラッグ&ドロップしたときのファイル表示
	function createStatusbar(obj, addIndex){
		fileCount++;
		var row = "odd";
		if(fileCount % 2 == 0){
			row = "even";
		}
		
		//html要素を追加
		this.statusbar = $("<div class='statusbar " + row + "'></div>");
		//ファイル名
		this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
		//サイズ
		this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
		//削除ボタン
		this.abort = $("<div class='abort' id='" + addIndex + "'>×</div>").appendTo(this.statusbar);
		obj.after(this.statusbar);

		//ファイルサイズを表示
		this.setFileNameSize = function(name, size){
			var sizeMB = size / 1024 / 1024;
			var sizeStr = sizeMB.toFixed(2) + " MB";
			this.filename.html(name);
			this.size.html(sizeStr);
		}

		//削除ボタンを押したときの処理
		this.setAbort = function(jqxhr){
			var sb = this.statusbar;
			var delNum = 0;

			this.abort.click(function(){
				fileCount--;
				jqxhr.abort();
				//表示削除
				sb.remove();
				//ファイルデータ送信用配列から削除
				delNum = $(sb[0]).children('.abort').attr('id');
				fileList.splice(delNum, 1);

				$('.abort').map(function(i){
					var id = $(this).attr('id');
					if(id > delNum){
						$(this).attr('id', --id);
					}
				});
				// ファイルが０なら再度メッセージを表示
				if(fileCount == 0){
					$('#js-dd-uploaded').show();
				}
			});
		}
	}

	//~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
	//
	//履歴書・職務経歴書・スキルシート
	//ファイルを選択したときの処理
	//
	//~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*
	$('form').on('change', 'input[type="file"]', function(e) {
		var file_name = $(this).prop('files')[0].name;
    	$(this).parent().next().html(file_name);
   	});
});
