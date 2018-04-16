$(function() {
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
    if("/admin/category/input" == $(location).attr('pathname')){
        //エラー文字が表示されたとき
        if($('.alert-danger').is(':visible')){
            var parentSort = $('.selectParentName').val();
            var oldChildSort = window.sessionStorage.getItem(['childSort']);
            selectAjax(parentSort, 1, oldChildSort);
        }
    }

    //親カテゴリーを変更したとき
    $('.selectParentName').change(function() {
        var parentSort = $(this).val();
        //画面判定
        var status = 0;
        if("/admin/category/input" == $(location).attr('pathname')){
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
});