$(document).ready(function() {
    $('#entryList').dataTable( {
        "bFilter": false,
        "oLanguage": {
            "sLengthMenu": "表示行数 _MENU_ 件",
            "oPaginate": {
                "sNext": "次のページ",
                "sPrevious": "前のページ"
            },
            "sInfo": "全_TOTAL_件中 _START_件から_END_件を表示",
            "sInfoEmpty": "0 件中 0 から 0 まで表示",
            "sZeroRecords": "データはありません。",
            "sSearch": "検索："
        },
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 4, 5 ] }
        ],
        "aaSorting": [ [0, "desc"] ],
    });
    $('#memberList').dataTable( {
        "bFilter": false,
        "oLanguage": {
            "sLengthMenu": "表示行数 _MENU_ 件",
            "oPaginate": {
                "sNext": "次のページ",
                "sPrevious": "前のページ"
            },
            "sInfo": "全_TOTAL_件中 _START_件から_END_件を表示",
            "sInfoEmpty": "0 件中 0 から 0 まで表示",
            "sZeroRecords": "データはありません。",
            "sSearch": "検索："
        },
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 6 ] }
        ],
        "aaSorting": [ [0, "desc"] ],
    });
});
