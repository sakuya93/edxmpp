// 在頁面任何地方顯示 工具提示
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

var modal_content = [
    "<div class=\"modal-header\">" +
    "                <h5 class=\"modal-title\"  id=\"hint_Title\">提示</h5>" +
    "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>" +
    "            </div>" +
    "            <div class=\"modal-body\" id=\"hint_text\">" +
    "            </div>" +
    "            <div class=\"modal-footer\">" +
    "                <button type=\"button\" id=\"close\" class=\"btn btn-secondary\" data-dismiss=\"modal\">關閉</button>" +
    "            </div>"
];

//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
var $table = $('#table')
var $remove = $('#remove')
var selections = []

function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id
    })
}

function responseHandler(res) {
    $.each(res.rows, function (i, row) {
        row.state = $.inArray(row.id, selections) !== -1
    })
    return res
}

function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}


// 直播類型功能按鈕
function liveOperateFormatter(value, row, index) {
    if (row['comment'] == null && row['score'] == null) { //未評分
        return [
            '<i class="fa fa-pencil-square tool feedBack" data-toggle="tooltip" data-placement="top" title="回饋評語"></i>',
            // '<i class="fa fa-times-circle tool delete_comment" data-toggle="tooltip" data-placement="top" title="刪除評語"></i>',
            '<i class="fa fa-share-square tool link" data-toggle="tooltip" data-placement="top" title="瀏覽課程"></i>',
        ].join('')
    } else { //已評分
        return [
            '<i class="fa fa-share-square tool link" data-toggle="tooltip" data-placement="top" title="瀏覽課程"></i>',
        ].join('')
    }
}

// 圖片格式化
function picFormatter(value, row, index) {
    if (typeof(row['cf_thumbnail']) == "undefined")
        return [
            '<img width="100" src="../resource/image/teacher/live/' + row['l_thumbnail'] + '?value=' + Math.random() + '">'
        ].join('')
    else
        return [
            '<img width="100" src="../resource/image/teacher/film/' + row['cf_thumbnail'] + '?value=' + Math.random() + '">'
        ].join('')
}

//  直播類型操作事件
window.liveOperateEvents = {
    'click .link': function (e, value, row, index) {
        window.location.href = '../Teacher_sales/live/' + row['l_id']+"?c=TWD";
    },
    'click .feedBack': function (e, value, row, index) {
        var modal_body = [
            "<div class=\"container\">" +
            "       <div class=\"col-sm-12 rating_block\">評分:" +
            "           <div>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "           </div>" +
            "           <div>" +
            "            1~5分: <input class=\"rating data-" + row['l_id'] + "\"type=\"number\" name=\"quantity\" min=\"1\" max=\"5\">" +
            "           </div>" +
            "        </div>" +
            "        <div class=\"col-sm-12 comment_block\">" +
            "            <i class=\"fa fa-pencil\">撰寫評論</i>" +
            "            <div class=\"input-group comment\">" +
            "               <textarea class=\"form-control data-" + row['l_id'] +
            "                   \" aria-label=\"With textarea\"></textarea>" +
            "            </div>" +
            "            <button class=\"btn btn-success send-btn\" type=\"submit\" onclick=\"sendComment('" + row['l_id'] + "','" + row['shoppingCartID'] + "')\">" +
            "                送出" +
            "                <i class=\"fa fa-send\"></i>" +
            "            </button>" +
            "        </div>" +
            "    </div>"
        ];

        /// 調整頁面內容
        $('header').css("z-index", "10");
        $('#comment_window .modal-dialog').addClass("modal-dialog-centered modal-lg");
        $('#comment_window .modal-title, #comment_window .modal-footer').remove();


        $('#comment_text').html(modal_body);


        //判斷是否有評論過
        if (row['courseEvaluationID']) {
            $('.rating').val(row['score'])
            $('.comment textarea').text(row['comment'])
            $('.send-btn').attr('onclick', 'editComment(\'' + row['l_id'] + '\')');
        } else {
            $('.send-btn').attr('onclick', 'sendComment(\'' + row['l_id'] + "\',\'" + row['shoppingCartID'] + '\')');
        }

        $('#comment_window').modal('show');
        $('#comment_window').on('hidden.bs.modal', function () {
            //復原顯示視窗前狀態
            $('header').css("z-index", "10001");
            $('#comment_window .modal-dialog').removeClass("modal-dialog-centered modal-lg");
            $('#modal-content').html(modal_content);
        })

    }
}

//撰寫評論送出
function sendComment(l_id, shoppingCartID) {
    $('#comment_window').modal('hide');
    var data = document.getElementsByClassName('data-' + l_id);

    var convey_data = {
        id1: l_id,
        id2: shoppingCartID,
        score: data[0].value,
        comment: data[1].value,
    };

    $.ajax({
        type: "POST",
        url: "../addComment",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
            if (res['status'] == true) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    refresh();
                })
            }
        }, error: function (res) {
            $('#hint_text').text("發送錯誤");
            $('#hint_window').modal();
        }
    });
}

function sendCommentFilm(cf_id, shoppingCartID) {
    $('#comment_window').modal('hide');
    var data = document.getElementsByClassName('data-' + cf_id);

    var convey_data = {
        id1: cf_id,
        id2: shoppingCartID,
        score: data[0].value,
        comment: data[1].value,
    };

    $.ajax({
        type: "POST",
        url: "../addCommentFilm",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
            if (res['status'] == true) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    refresh();
                })
            }
        }, error: function (res) {
            $('#hint_text').text("發送錯誤");
            $('#hint_window').modal();
        }
    });
}

function deleteComment(shoppingCartID, l_id) {
    $('#comment_window').modal('hide');
    var convey_data = {
        id1: shoppingCartID,
        id2: l_id
    }

    console.log(convey_data)

    $.ajax({
        type: "POST",
        url: "../MLP_deleteComment",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal('show');
            if (res['status'] == true) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    refresh();
                })
            }
        }, error: function (res) {
            $('#hint_text').text("發送錯誤");
            $('#hint_window').modal();
        }
    });
}

function editComment(l_id) {
    var data = document.getElementsByClassName('data-' + l_id);

    var convey_data = {
        id: l_id,
        comment: data[1].value,
        score: data[0].value,
    }

    console.log("edit")
    console.log(convey_data)

    $.ajax({
        type: "POST",
        url: "../MLP_changeComment",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            console.log("yes");
            console.log(res);
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal('show');
            if (res['status'] == true) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    refresh();
                })
            }
        }, error: function (res) {
            $('#hint_text').text("發送錯誤");
            $('#hint_window').modal();
        }
    });
}


// 影片類型功能按鈕
function filmOperateFormatter(value, row, index) {
    if (row['comment'] == null && row['score'] == null) { //未評分
        return [
            '<i class="fa fa-pencil-square tool feedBack" data-toggle="tooltip" data-placement="top" title="回饋評語"></i>',
            // '<i class="fa fa-times-circle tool delete_comment" data-toggle="tooltip" data-placement="top" title="刪除評語"></i>',
            '<i class="fa fa-share-square tool link" data-toggle="tooltip" data-placement="top" title="瀏覽課程"></i>',
        ].join('')
    } else { //已評分
        return [
            '<i class="fa fa-share-square tool link" data-toggle="tooltip" data-placement="top" title="瀏覽課程"></i>',
        ].join('')
    }
}

// 影片類型操作事件
window.filmOperateEvents = {
    'click .link': function (e, value, row, index) {
        window.location.href = '../film_courses/' + row['cf_id']+"?c=TWD";
    },
    'click .feedBack': function (e, value, row, index) {
        var modal_body = [
            "<div class=\"container\">" +
            "       <div class=\"col-sm-12 rating_block\">評分:" +
            "           <div>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "               <i class=\"fa fa-star\"></i>" +
            "           </div>" +
            "           <div>" +
            "            1~5分: <input class=\"rating data-" + row['cf_id'] + "\"type=\"number\" name=\"quantity\" min=\"1\" max=\"5\">" +
            "           </div>" +
            "        </div>" +
            "        <div class=\"col-sm-12 comment_block\">" +
            "            <i class=\"fa fa-pencil\">撰寫評論</i>" +
            "            <div class=\"input-group comment\">" +
            "               <textarea class=\"form-control data-" + row['cf_id'] +
            "                   \" aria-label=\"With textarea\"></textarea>" +
            "            </div>" +
            "            <button class=\"btn btn-success send-btn\" type=\"submit\" onclick=\"sendCommentFilm('" + row['cf_id'] + "','" + row['shoppingCartID'] + "')\">" +
            "                送出" +
            "                <i class=\"fa fa-send\"></i>" +
            "            </button>" +
            "        </div>" +
            "    </div>"
        ];

        /// 調整頁面內容
        $('header').css("z-index", "10");
        $('#comment_window .modal-dialog').addClass("modal-dialog-centered modal-lg");
        $('#comment_window .modal-title, #comment_window .modal-footer').remove();


        $('#comment_text').html(modal_body);


        //判斷是否有評論過
        if (row['courseEvaluationID']) {
            $('.rating').val(row['score'])
            $('.comment textarea').text(row['comment'])
            $('.send-btn').attr('onclick', 'editComment(\'' + row['cf_id'] + '\')');
        } else {
            $('.send-btn').attr('onclick', 'sendCommentFilm(\'' + row['cf_id'] + "\',\'" + row['shoppingCartID'] + '\')');
        }

        $('#comment_window').modal('show');
        $('#comment_window').on('hidden.bs.modal', function () {
            //復原顯示視窗前狀態
            $('header').css("z-index", "10001");
            $('#comment_window .modal-dialog').removeClass("modal-dialog-centered modal-lg");
            $('#modal-content').html(modal_content);
        })

    }
};


function totalTextFormatter(data) {
    return 'Total'
}

function totalNameFormatter(data) {
    return data.length
}

function totalPriceFormatter(data) {
    var field = this.field
    return '$' + data.map(function (row) {
        return +row[field].substring(1)
    }).reduce(function (sum, i) {
        return sum + i
    }, 0)
}

window.field; //table欄位

function chooseField(type) {
    if (type == "type_film_course") {
        //類型顯示: 影片
        $('.title').text("學習歷程-影片課程");

        //上課老師、課程名稱、影片縮圖
        window.field = [
            [{
                title: '課程名稱',
                field: 'cf_name',
                sortable: true,
                align: 'center'
            }, {
                title: '上課老師',
                field: 'teacherName',
                sortable: true,
                align: 'center',
            }, {
                title: '影片縮圖',
                formatter: picFormatter,
                align: 'center',
            }, {
                title: '老師評語',
                field: 'comment',
                align: 'center',
                sortable: true,
            }, {
                title: '老師評分',
                field: 'score',
                align: 'center',
                sortable: true,
            }, {
                title: '操作',
                align: 'center',
                formatter: filmOperateFormatter,
                events: window.filmOperateEvents
            }]
        ];
    } else if (type == "type_live_course") {
        //類型顯示: 直播
        $('.title').text("學習歷程-直播課程");

        //上課老師、課程名稱、影片縮圖、老師評語、老師評分
        window.field = [
            [{
                title: '課程名稱',
                field: 'l_actualMovie',
                sortable: true,
                align: 'center'
            }, {
                title: '上課老師',
                field: 'teacherName',
                sortable: true,
                align: 'center',
            }, {
                title: '影片縮圖',
                width: 200,
                align: 'center',
                formatter: picFormatter,
            }, {
                title: '老師評語',
                field: 'comment',
                align: 'center',
                sortable: true,
            }, {
                title: '老師評分',
                field: 'score',
                align: 'center',
                sortable: true,
            }, {
                title: '操作',
                align: 'center',
                formatter: liveOperateFormatter,
                events: window.liveOperateEvents
            }]
        ]
    }
}

function refresh() {
    $table.bootstrapTable('refresh');
}

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        columns: window.field,
        url: "../my_learn_process-getComment?s=" + location.pathname,
    })
    $table.on('check.bs.table uncheck.bs.table ' +
        'check-all.bs.table uncheck-all.bs.table',
        function () {
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)

            // save your data, here just save the current page
            selections = getIdSelections()
            // push or splice the selections if you want to save all data selections
        })
    $table.on('all.bs.table', function (e, name, args) {
        // console.log(name, args)
    })
    $remove.click(function () {
        var ids = getIdSelections()
        $table.bootstrapTable('remove', {
            field: 'id',
            values: ids
        })
        $remove.prop('disabled', true)
    })
}

//  產生TABLE
$(function () {
    initTable()

    $('#locale').change(initTable)
})
//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////


//////////////////////////////////////////////  RWD START  //////////////////////////////////////////////


/*  頁面載入初始化 開始  */
var wdth = $(window).width();

if (wdth <= 386) {
    $(".flex-nowrap").css("flex-wrap", "wrap !important");
    $(".float-right").css("float", "wrap !important");
}
/*  頁面載入初始化 結束  */


//////////////////////////////////////////////  RWD END  //////////////////////////////////////////////
