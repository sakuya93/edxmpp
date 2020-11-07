//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
var $table = $('#table')
var $remove = $('#remove')
var selections = []

function class_status() { //選取上課狀態
    var s = $('#class_status').val();
    var url = "";
    if (s == "未上課") {
        // url = "teacherCheck/getNotCheck";
        // $("#table").attr("data-url", url);
    } else if (s == "已上課") {
        // url = "teacherCheck/getCheck";
        // $("#table").attr("data-url", url);
    }

    $("#table").bootstrapTable('refreshOptions', {
        url: url,
        silent: true
    });
}

function course_options() { //選取課程選項
    var s = $('#course_options').val();
    var url = "";

    console.log(type);

    if (type == "type_live_course") {
        url = "../classStudent_information_fun/getFilmComment/";
    } else if (type == "") {
        url = "../classStudent_information_fun/getLiveComment/" + s;
    }
    $("#table").attr("data-url", url);

    console.log(url);

    $("#table").bootstrapTable('refreshOptions', {
        url: url,
        silent: true
    });
}

// 圖片格式化
function picFormatter(value, row, index) {
    return [
        '<img width="100" src="../resource/image/student/photo/' + row['photo'] + '?value=' + Math.random() + '">'
    ].join('')
}

function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id
    })
}

function responseHandler(res) {
    $.each(res.rows, function (i, row) {
        row.state = $.inArray(row.id, selections) !== -1
    })
    console.log(res);
    return res
}

function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
}

//評分&評語
function operateFormatter(value, row, index) {
    if (typeof row['ce_comment'] == "string") //有些時候會有物件，所以導致replace這方法會錯誤，這邊判斷只有是字串的才去處理
        row['ce_comment'] = row['ce_comment'].replace(/\r\n|\n/g, "<br>"); //評論有可能有換行，但傳到方法的時候有換行的字符就會錯誤

    return [
        '<a class="score_icon" onclick="open_window(\'' + row['id'] + '\',\'' + row['memberID'] + '\',\'' + row['ce_id'] + '\',\'' + row['shoppingCartID'] + '\',\'' + row['memberName'] + '\',\'' + row['className'] + '\',\'' + row['ce_level'] + '\',\'' + row['ce_comment'] + '\');" title="編輯評價">',
        '<i class="fa fa-edit" style="font-size: 26px"></i>',
        '</a>  ',
    ].join('')
}

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

window.field = [
    [{
        title: '學生資訊',
        colspan: 6,
        align: 'center'
    }],
    [{
        title: '學生頭貼',
        formatter: picFormatter,
        align: 'center',
    }, {
        title: '學生姓名',
        field: 'memberName',
        sortable: true,
        align: 'center'
    }, {
        title: '課程名稱',
        field: 'className',
        sortable: true,
        align: 'center',
    },
        {
            title: '評分',
            field: 'ce_level',
            align: 'center',
            sortable: true
        },
        {
            title: '評語',
            field: 'ce_comment',
            align: 'center',
            sortable: true
        },
        {
            title: '老師評價編輯',
            field: 'evaluation_edit',
            align: 'center',
            sortable: true,
            formatter: operateFormatter
        }]
]

function chooseField(type) {
    var url = "";
    var s = $('#course_options').val();
    if (type == "type_film_course") {
        $(".title").text("上課學生資料-影片");
        url = "../classStudent_information_fun/getFilmComment/";
    } else if (type == "type_live_course") {
        $(".title").text("上課學生資料-直播");
        url = "../classStudent_information_fun/getLiveComment/" + s;
    }

    console.log(url);

    $("#table").attr("data-url", url);

    $("#table").bootstrapTable('refreshOptions', {
        url: url,
        silent: true
    });
}

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        columns: window.field,
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

function open_window(id, m_id, ce_id, sc_id, m_name, c_name, ce_level, ce_comment) {
    ce_comment = ce_comment.replace(/<br>/g, "\n");  // 這邊 /g 代表所有<br>字串將被改回換行符號\n
    $('#score_and_comment_window').modal();

    //用來顯示對應的按鈕
    if (ce_level != "null" && ce_comment != "null") { //有評價
        $('#window_confirm').css('display', 'none');
        $('#window_edit').css('display', 'block');
        $('#window_delete').css('display', 'block');

        $('#window_delete').attr('onclick', 'delete_evaluation(\'' + m_id + "\',\'" + sc_id + '\')');
        $('#window_edit').attr('onclick', 'change_evaluation(\'' + ce_id + '\')');

        //model 初始化
        $('.score_content').val(ce_level);
        $('.comment_content').val(ce_comment);
    }
    else { //無評價
        $('#window_confirm').css('display', 'block');
        $('#window_edit').css('display', 'none');
        $('#window_delete').css('display', 'none');

        //model值 初始化
        $('.score_content').val(1);
        $('.comment_content').val("");
    }

    //隨著點擊的來自動載入對應的資料
    $('#s_name').val(m_name);
    $('#c_name').val(c_name);

    //點擊送出按鈕
    $('#window_confirm').unbind('click').bind('click', function () {
        var convey_data = {
            id1: m_id,
            id2: sc_id,
            id3: id,
            comment: $(".comment_content").val(),
            score: $(".score_content").val()
        };

        // console.log(convey_data);

        if (convey_data['score'] >= 1 && convey_data['score'] <= 5) {
            $.ajax({
                url: "../classStudent_information_fun/addComment",
                type: "POST",
                data: convey_data,
                datatype: 'json',
                success: function (res) {
                    res = JSON.parse(res);

                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();

                    if (res['status']) {
                        $('#hint_window').on('hidden.bs.modal', function () {
                            $('#score_and_comment_window').modal('hide');
                            $table.bootstrapTable('refresh');
                        })
                    }
                },
            });
        }
        else {
            $('#hint_text').text("評分級數1~5，超出範圍!");
            $('#hint_window').modal();
        }
    });
}

//刪除評價
function delete_evaluation(m_id, sc_id) {
    var convey_data = {
        id1: sc_id,
        id2: m_id
    };

    // console.log(convey_data);

    $.ajax({
        url: "../classStudent_information_fun/deleteComment",
        type: "POST",
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);

            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();

            if (res['status']) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    $('#score_and_comment_window').modal('hide');
                    $table.bootstrapTable('refresh');
                })
            }
        },
    });
}

//修改評價
function change_evaluation(ce_id) {
    var convey_data = {
        id: ce_id,
        comment: $(".comment_content").val(),
        score: $(".score_content").val()
    };

    if (convey_data['score'] >= 1 && convey_data['score'] <= 5) {
        $.ajax({
            url: "../classStudent_information_fun/changeComment",
            type: "POST",
            data: convey_data,
            datatype: 'json',
            success: function (res) {
                res = JSON.parse(res);

                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                if (res['status']) {
                    $('#hint_window').on('hidden.bs.modal', function () {
                        $('#score_and_comment_window').modal('hide');
                        $table.bootstrapTable('refresh');
                    })
                }
            },
        });
    }
    else {
        $('#hint_text').text("評分級數1~5，超出範圍!");
        $('#hint_window').modal();
    }
}