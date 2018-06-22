$(function() {

    /**
     * コピーして登録画面表示(親)
     * 子カテゴリー選択時にボタン表示(次へ・登録)の切り替え
     */
    //チェックボックス配列
    var checkBox = [];
    //ページを読み込んだとき
    $(window).on("load",function(){
        //カテゴリーメニュー内のコピーして登録画面のとき
        if('/admin/category/copy-parent-input' == $(location).attr('pathname')){
            //チェックボックスの状態を取得
            $('input:checkbox[name="children[]"]:checked').each(function() {
                //配列へ追加
                checkBox.push($(this).val());
            });

            if(window.sessionStorage.getItem('checkBox')){
                //セッションデータがあるとき
                $('.submitBtn').hide();
                $('.nextBtn').show();
            }else{
                //セッションデータがないとき
                $('.nextBtn').hide();
                $('.submitBtn').show();
            }
        }
    });

    //カテゴリー一覧画面
    if('/admin/category/search' == $(location).attr('pathname')){
        //セッションデータがあるとき
        if(window.sessionStorage.getItem('checkBox')){
            window.sessionStorage.removeItem('checkBox');
        }
        ajaxSessionForget();
    }

    //チェックボックスを変更したとき
    $('.copyChildren').change(function(){
        window.sessionStorage.removeItem('checkBox');

        if($.inArray($(this).val(), checkBox) == -1){
            //配列に追加
            checkBox.push($(this).val());
        }else{
            for(i=0; i<checkBox.length; i++){
                if(checkBox[i] == $(this).val()){
                    //要素を削除
                    checkBox.splice(i, 1);
                }
            }
        }
        //セッションデータへ保存
        window.sessionStorage.setItem('checkBox',checkBox);

        if(window.sessionStorage.getItem('checkBox')){
            //セッションデータがあるとき
            $('.submitBtn').hide();
            $('.nextBtn').show();
        }else{
            //セッションデータがないとき
            $('.nextBtn').hide();
            $('.submitBtn').show();  
        }
    });

 
    /**
     * 親カテゴリー変更時に表示順を切り替え・親表示順切り替え
     * POST:/admin/category/selectBox
     */
    
    //子の表示順を変えたとき
    $('.selectChildeSort').change(function() {
        var childSort = $(this).val();
    
        window.sessionStorage.setItem(['childSort'],[childSort]);
    });

    //新規登録画面
    if("/admin/category/input" == $(location).attr('pathname') || "/admin/category/copy-child-input" == $(location).attr('pathname')){
        //エラー文字が表示されたとき
        if($('.alert-danger').is(':visible')){
            var parentSort = $('.selectParentName').val();
            var oldChildSort = window.sessionStorage.getItem(['childSort']);
            if($(location).attr('search')　!= ""){
                selectAjax(parentSort, 1, oldChildSort);
            }
        }
    }

    //親カテゴリーを変更したとき
    $('.selectParentName').change(function() {
        var parentSort = $(this).val();
        //画面判定
        var status = 0;
        if("/admin/category/input" == $(location).attr('pathname') || "/admin/category/copy-child-input" == $(location).attr('pathname')){
            //新規登録画面
            status = 1;
        }
        selectAjax(parentSort, status);
    });

    //親に対応する子セレクトボックスを表示
    function selectAjax(parentSort, status, oldChildSort) {
        var oldNum = oldChildSort;
        $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: '/admin/category/selectBox',
            type: 'POST',
            data: { 
                'parent_id' : parentSort,
                'status' : status
                },
            dataType: 'json',
        })
        .done(function(data){   
            for(var childSort in data){
                switch(childSort){
                    case "max":
                        var options = "";
                        for (var sortNum = 1; sortNum <= data[childSort]; sortNum++) {
                            options += '<option ';

                            if(oldNum == sortNum) {

                                    options += 'selected ';
                            }
                            options += 'value="'+ sortNum +'">'+ sortNum +'</option>';
                            console.log(options);
                        }
                        $('.selectChildeSort').html(options);
                        break;
                    case "parent_sort":
                        $('.parentSort').html('<input type="hidden" class="parentSort" name="parent_sort" value="'+ data[childSort] +'">');
                        break;
                    default:break;
                }  
            }
        })
        .fail(function(){
          alert('正しい結果を得られませんでした。');
        });
    }

    /**
     * セッションデータ削除
     * ①登録前
     * ②登録後一覧画面に遷移したとき
     * ③コピーして登録の親画面で入力、その後子画面の入力途中でもどるボタン2回押下、一覧画面を表示したとき
     * POST:/admin/category/session-forget
     */
    function ajaxSessionForget() {
        $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: '/admin/category/session-forget',
            type: 'POST',
        })
        .fail(function(){
          alert('正しい結果を得られませんでした。');
        });
    }

    /**
     * スキルカテゴリー変更時に表示順を切り替え
     * POST:/admin/skill/selectBox
     */
    
    //スキルの表示順を変えたとき
    $('.selectSkillSort').change(function() {
        var skillVal = $(this).val();
        window.sessionStorage.setItem(['skillVal'],[skillVal]);
    });
    
    //新規登録画面
    if("/admin/skill/input" == $(location).attr('pathname')){
        //エラー文字が表示されたとき
        if($('.alert-danger').is(':visible')){
            var skillCategoryVal = $('.selectSkillCategoryName').val();
            var oldSKillSort = window.sessionStorage.getItem(['skillVal']);
            selectSkillCategoryAjax(skillCategoryVal, oldSKillSort);
        }
    }

    //スキルカテゴリーを変更したとき
    $('.selectSkillCategoryName').change(function() {
        var skillCategoryVal = $(this).val();
        selectSkillCategoryAjax(skillCategoryVal);
    });
    
    //スキルカテゴリーに対するスキルのセレクトボックスを表示
    function selectSkillCategoryAjax(skillCategoryId, oldSKillSort) {
        $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        
        $.ajax({
            url: '/admin/skill/selectBox',
            type: 'POST',
            data: { 
                'id' : skillCategoryId,
                },
            dataType: 'json',
        })
        .done(function(data){   
            for(var max in data){
                var options = "";
                for (var sortNum = 1; sortNum <= data[max]; sortNum++) {
                    options += '<option ';
                    if(oldSKillSort == sortNum) {
                            options += 'selected ';
                    }
                    options += 'value="'+ sortNum +'">'+ sortNum +'</option>';
                }
                $('.selectSkillSort').html(options);
            }
        })
        .fail(function(){
          alert('正しい結果を得られませんでした。');
        });
    }
});