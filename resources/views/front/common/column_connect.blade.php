@foreach($columnConnects as $columnConnect)
    @if(\File::exists('./../storage/app/public/id'.$columnConnect->connect_id.'_title.php'))
        <!-- wordPress固定ページを表示 -->
        <div class="main-content__body">
            <div class="content__element_bottomSpace">
                <div class="item">
                    <a href={{Request::root().'/column/id'.$columnConnect->connect_id.'/'}} target="_blank">
                        <div class="itemHeader">
                            <div class="table-row">
                                <p class="name background_color01 border_color01">
                                  {{ File::get('./../storage/app/public/id'.$columnConnect->connect_id.'_title.php') }}
                                </p>
                                <p class="item_id background_color01 border_color01"><!-- 案件詳細と同じレイアウトにするため空タグ --></p>
                            </div>
                        </div>
                        <div class="itemInfo clear border_color01">
                            <div class="itemInfoInr">
                                {!! File::get('./../storage/app/public/id'.$columnConnect->connect_id.'_content.php') !!}
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
@endforeach
