//////////////////////////////////////////////  TABLE START  //////////////////////////////////////////////
var $table = $('#table')

function CoursePhoto(value, row, index) {
    if (row['courseType'] == "直播") {
        return [
            '<img class="rounded m-auto d-block" width="64" src="../resource/image/teacher/live/' + row['courseImage'] + '">',
        ].join('')
    }
    else {
        return [
            '<img class="rounded m-auto d-block" width="64" src="../resource/image/teacher/film/' + row['courseImage'] + '">',
        ].join('')
    }
}

function TeacherPhoto(value, row, index) {
    return [
        '<img class="rounded m-auto d-block" width="64" src="../resource/image/student/photo/' + row['teacherPhoto'] + '">',
    ].join('')
}

function PayStatus(value, row, index) {
    if (row['payStatus'] == "0") {
        return [
            '未付款',
        ].join('')
    }
    else {
        return [
            '已付款',
        ].join('')
    }
}

function responseHandler(res) {
    $.each(res.rows, function (i, row) {
        row.state = $.inArray(row.id, selections) !== -1
    })

    return res
}

//  初始化TABLE
function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        height: 550,
        columns: [
            [{
                title: '課程名稱',
                field: 'courseName',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '課程類型',
                field: 'courseType',
                rowspan: 1,
                align: 'center',
                valign: 'middle',
                sortable: true,
            }, {
                title: '課程縮圖',
                field: 'courseImage',
                formatter: CoursePhoto,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: '老師姓名',
                field: 'teacherName',
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: '老師頭像',
                field: 'teacherPhoto',
                formatter: TeacherPhoto,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }, {
                title: '購買狀態',
                field: 'payStatus',
                formatter: PayStatus,
                rowspan: 1,
                sortable: true,
                align: 'center',
            }],
        ]
    })
}

function initSwitch() {
    $('.member_status').bootstrapSwitch({
        onText: "封鎖會員",
        offText: "解除封鎖",
        onColor: "success",
        offColor: "warning",

        onSwitchChange: function (event, state) {
            if (state == true) {
                UnblockMember(m_id);
            } else {
                blockadeMember(m_id);
            }
        }
    })
}

//  產生TABLE
$(function () {
    initSwitch();
    initTable();
})

//////////////////////////////////////////////  TABLE END  //////////////////////////////////////////////

function blockadeMember(m_id) {
    $('#block_member_window').modal();

    $('#block_member_confirm').unbind('click').bind('click', function (e) {
        var convey_data = {
            id: m_id,
            reason: $('#blocking_reason').val()
        };
        url = "../member_management/blockadeMember";
        ajax_share(convey_data, url);
        $('#block_member_window').modal('hide');
    });
}

function UnblockMember(m_id) {
    var convey_data = {
        id: m_id
    };
    url = "../member_management/UnblockMember";
    ajax_share(convey_data, url);
}

function ajax_share(convey_data, url) {
    $.ajax({
        type: 'POST',
        url: url,
        data: convey_data,
        datatype: 'json',
        success: function (res) {
            res = JSON.parse(res);
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        },
    });
}