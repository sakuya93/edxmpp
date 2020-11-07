//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
var $table = $('#table')
var $remove = $('#remove')
var selections = []

function sel_notice_object() {
    var s = $('#sel_notice_object').val();
    var url = "notice_record/getNoticeRecord/" + s;

    $("#table").attr("data-url", url);

    $("#table").bootstrapTable('refreshOptions', {
        url: url,
        silent: true
    });
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
    return res
}

function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
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

// 詳細資訊
function operateFormatter(value, row, index) {
    return [
        '<a class="info" onclick="open_detail_window(' + row['id'] + ')" title="詳細內容" style="color: #007bff">',
        '<i class="fa fa-info-circle" style="font-size: 28px"></i>',
        '</a>  ',
    ].join('')
}


//通知對象
function notice_object_operateFormatter(value, row, index) {
    if (value == 0) {
        return "全體";
    }
    else if (value == 1) {
        return "學生";
    }
    else if (value == 2) {
        return "老師";
    }
    else if (value == 3) {
        return "特定會員";
    }
    else if (value == 4) {
        return "特定老師";
    }
    else if (value == 5) {
        return "特定影片課程";
    }
    else if (value == 6) {
        return "特定直播課程";
    }
}

//寄信或通知
function email_or_notice_operateFormatter(value, row, index) {
    if (value == 0) {
        return "寄信與通知";
    }
    else if (value == 1) {
        return "寄信";
    }
    else if (value == 2) {
        return "通知";
    }
}

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        locale: $('#locale').val(),
        columns: [
            [{
                field: 'state',
                checkbox: true,
                rowspan: 1,
                align: 'center',
                valign: 'middle'
            }, {
                title: 'id',
                field: 'id',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '通知對象',
                field: 'notice_object',
                formatter: notice_object_operateFormatter,
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '通知方式',
                field: 'email_or_notice',
                formatter: email_or_notice_operateFormatter,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: '詳細資訊',
                field: 'operate',
                formatter: operateFormatter,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }],
        ]
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
function sel_object(val) {
    //點擊提示icon事件
    $('#specific_ID_hint_icon').unbind("click").bind("click", function () {
        $('.specific_ID_hint_area').toggle(1000);
    });

    $('#specific_ID_hint').html("");
    if (val == 3) {
        $('#specific_ID').attr('disabled', false);

        $('#specific_ID_Title').text("個人儀錶板");
        $('#specific_ID_hint').append("1.右側資訊欄點開來點選儀錶板<br>2.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "resource/image/admin/notice/dashboard_specific_ID.jpg");
    }
    else if (val == 4) {
        $('#specific_ID').attr('disabled', false);

        $('#specific_ID_Title').text("老師頁面");
        $('#specific_ID_hint').append("1.右側資訊欄點開來點選儀錶板<br>2.點選個人資訊區的老師頁面按鈕進入<br>3.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "resource/image/admin/notice/teacherPage_specific_ID.jpg");
    }
    else if (val == 5) {
        $('#specific_ID').attr('disabled', false);

        $('#specific_ID_Title').text("影片頁面");
        $('#specific_ID_hint').append("1.選擇對應的影片課程進入後<br>2.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "resource/image/admin/notice/filmCourse_specific_ID.jpg");
    }
    else if (val == 6) {
        $('#specific_ID').attr('disabled', false);

        $('#specific_ID_Title').text("直播頁面");
        $('#specific_ID_hint').append("1.選擇對應的直播課程進入後<br>2.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "resource/image/admin/notice/liveCourse_specific_ID.jpg");
    }
    else if (val == 7) {
        $('#specific_ID').attr('disabled', false);

        $('#specific_ID_Title').text("募資頁面");
        $('#specific_ID_hint').append("1.選擇對應的募資課程進入後<br>2.複製網址列上的ID碼");
        $('#specific_ID_img').attr("src", "resource/image/admin/notice/fundraisingCourse_specific_ID.jpg");
    }
    else {
        $('#specific_ID').attr('disabled', true);
        $('#specific_ID_hint_icon').unbind("click");
        $('.specific_ID_hint_area').css('display', 'none');
    }
}

function addNoticeRecord() {
    $('#notice_record_window').modal();

    $('#add_confirm').unbind("click").bind('click', function () {
        var convey_data = {
            notice_object: document.getElementById('notice_object').value,
            specificObject: document.getElementById('specific_ID').value,
            email_or_notice: document.getElementById('email_or_notice').value,
            message_title: document.getElementById('message_title').value,
            send_message: CKEDITOR.instances.editor1.getData()
        };

        $.ajax({
            type: "POST",
            url: "notice_record/addNoticeRecord",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                if (res['status']) {
                    CKEDITOR.instances.editor1.setData("");
                    $('#hint_window').on('hidden.bs.modal', function () {
                        $('#notice_record_window').modal('hide');
                        document.getElementById('notice_object').value = "0";
                        document.getElementById('email_or_notice').value = "0";
                        document.getElementById('message_title').value = "";
                        document.getElementById('send_message').value = "";
                        initTable();
                    })
                }
            }
        });
    })
}

function deleteNoticeRecord() {
    var select_data = getIdSelections(); //獲取選擇的資料

    if (select_data.length != 0) { //有選取資料時
        var convey_data = {};

        for (var i = 0; i < select_data.length; i++) {
            convey_data[i] = select_data[i];
        }

        $('#confirm').css('display', 'block');
        $('#hint_text').text("你確定要刪除這" + select_data.length + "筆通知嗎?");
        $('#hint_window').modal();

        $('#confirm').unbind("click").bind('click', function () {
            $.ajax({
                type: "POST",
                url: "notice_record/deleteNoticeRecord",
                data: convey_data,
                dataType: 'json',
                success: function (res) {
                    $('#hint_text').text(res['msg']);
                    $('#hint_window').modal();
                    $('#confirm').css('display', 'none');

                    if (res['status']) {
                        $('#hint_window').on('hidden.bs.modal', function () {
                            initTable();
                        })
                    }
                }
            });
        });

        $('#close').unbind("click").bind('click', function () {
            $('#confirm').css('display', 'none');
        });
    }
    else {
        $('#hint_text').text("請至少選取一筆要刪除的通知");
        $('#hint_window').modal();
    }
}

//查看詳細通知訊息視窗
function open_detail_window(id) {
    var convey_data = {id: id};

    $.ajax({
        type: "POST",
        url: "notice_record/getNoticeDetail",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#notice_record_detail_window').modal();

            //修改值
            $('#notice_object_detail').val(res['notice_object']);
            $('#email_or_notice_detail').val(res['email_or_notice']);
            $('#message_title_detail').val(res['message_title']);
            $('#message_detail').html(res['message']);
            $('#date_detail').val(res['date']);
        }
    });
}