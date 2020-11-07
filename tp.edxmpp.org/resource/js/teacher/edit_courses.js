/*影片提示資訊*/
function video_info_open() {
    var content = [
        '<img src="../../resource/image/share/video_info.png">'
    ];
    $('#hint_text').html(content);
    $('#hint_window').modal();
}

/*切換證明文件照片*/
function handle(file, id) {
    var reader = new FileReader();

    id = "#" + id;
    reader.onload = function (e) {
        $(id).attr('src', e.target.result);
    }
    reader.readAsDataURL(file[0]);

}

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip(); //課程標籤提示文字框
    //初始化資料
    var type = document.getElementById("type").getAttribute("data[0]");
    $("#type").val(type);

    var video_url = "https://www.youtube.com/embed/";
    var url = document.getElementById("video").getAttribute("data[0]");
    $("#video").attr("src", video_url + url);

    //更改影片網址方法
    $("#video_url_input").bind("input propertychange", function (event) {
        if ($("#video_url_input").val()) {
            $("#video").attr("src", video_url + ($("#video_url_input").val()));
        }
    });


    //提示確認是否更新縮圖視窗
    $("#update_thumbnail_form").submit(function (event) {
        event.preventDefault();

        var file = event.target[0].files[0];
        var thumbnail_id = document.getElementById("thumbnail").getAttribute("data[0]");

        var fd = new FormData(); //欲傳送資料
        fd.append("thumbnail", file);
        fd.append("thumbnail_id", thumbnail_id);

        $.ajax({
            url: "../../live_courses/update_thumbnail",
            type: "POST",
            data: fd,
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                res = JSON.parse(res);
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
                $('#upload_image_btn').attr('disabled', true);
            },
        });
    })

    //傳送直播課程資料
    $("#live_courses_form").submit(function (event) {
        event.preventDefault(); //阻止自動提交

        var data = document.getElementsByClassName('live_courses-data'); //抓input資料
        var convey_data = { //欲傳送資料
            l_id: data[0].value,
            actualMovie: data[1].value,
            experienceFilm: data[2].value,
            type: data[3].value,
            introduction: CKEDITOR.instances.editor1.getData(),
            brief_introduction: CKEDITOR.instances.editor2.getData(),
            hours: data[4].value,
            numberPeople: data[5].value,
        };

        $.ajax({
            url: "../../live_courses/update_courses",
            type: "POST",
            data: convey_data,
            datatype: 'json',
            success: function (res) {
                res = JSON.parse(res);
                var errorText = document.getElementsByClassName('basic-data-error');
                for (var i = 0; i < errorText.length; ++i)
                    errorText[i].style.visibility = "hidden";

                var index = -1;

                if (res['msg'] == '請填寫直播名稱') {
                    errorText[0].style.visibility = "visible";
                    index = 0;
                } else if (res['msg'] == '請填寫體驗影片') {
                    errorText[1].style.visibility = "visible";
                    index = 1;
                } else if (res['msg'] == '請填寫課程類型') {
                    errorText[2].style.visibility = "visible";
                    index = 2;
                } else if (res['msg'] == '請填寫課程介紹') {
                    errorText[3].style.visibility = "visible";
                    index = 3;
                } else if (res['msg'] == '請填寫課程簡介') {
                    errorText[3].style.visibility = "visible";
                    index = 3;
                } else if (res['msg'] == '請填寫課程時數') {
                    errorText[4].style.visibility = "visible";
                    index = 4;
                } else if (res['msg'] == '上課人數只能填寫數字') {
                    errorText[5].style.visibility = "visible";
                    index = 5;
                } else {
                    // $('#hint_text').text(res['msg']);
                    // $('#hint_window').modal();
                }

                if (index != -1) {
                    errorText[index].scrollIntoView(true);
                    var viewportH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
                    window.scrollBy(0, -viewportH / 2);
                }

                if (res['status']) {
                    // 顯示訊息並於 3 秒後清除訊息
                    let parentElement = $("#editLiveCourseForm");
                    parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>儲存成功</p>")
                    var seconds = $("#__showMessage").attr("value");
                    var countdown = setInterval(function() {
                        seconds--;
                        $("#__showMessage").attr("value", seconds);
                        if (seconds <= 0) {
                            $("#__showMessage").empty();
                            clearInterval(countdown)
                        };
                    }, 1000);

                    // $('#hint_text').text(res['msg']);
                    // $('#hint_window').modal();
                    // $('#hint_window').on('hide.bs.modal', function () {
                    // });
                }
            },
        });
    });

    /*課程標籤初始資料載入區--------------------------------------------*/
    var l_id = document.getElementsByClassName('live_courses-data')[0].value; //抓課程ID

    var convey_data = {
        'status': 1, //直播
        'id': l_id
    };

    $.ajax({
        type: "POST",
        url: "../../live_courses/getCourseLabel",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            for (var i = 0; i < res.length - 1; i++) {
                $('.added_tag_area').append('<span class="tag" id="tag_id' + (i + 1) + '" data-index="' + (i + 1) + '">' + res[i] + '<i class="fa fa-close" onclick="real_del_tag(' + (i + 1) + ')"></i></span>');
            }
        }
    });

    tag_autocomplete("../../", "live_courses"); //自動完成標籤填詞
});

/*課程標籤區 --------------------------------------------------------------------------*/
function add_course_tag() {
    var tagLastItem = $('.added_tag_area .tag:last-child'); //取得最後一個tag元素
    if (tagLastItem.attr("data-index")) {
        var tag_length = parseInt(tagLastItem.attr("data-index")) + 1;//用來給下一個標籤設ID用的
    } else {
        var tag_length = 1;
    }

    if ($('#tag_value').val() != "") {
        $('.added_tag_area').append('<span class="tag temp_tag" id="tag_id' + tag_length + '" data-index="' + tag_length + '">' + $('#tag_value').val() + '<i class="fa fa-close" onclick="del_tag(' + tag_length + ')"></i></span>');
        $('#tag_value').val("");
    }

    var tagLastItem = $('.added_tag_area .tag:last-child');
    console.log(tagLastItem.attr("data-index"));
}

/*刪除的不影響後端資料庫*/
function del_tag(index) {
    $('#tag_id' + index).remove();
}

/*刪除的是真的資料庫本身資料*/
function real_del_tag(index) {
    var convey_data = {};
    var l_id = document.getElementsByClassName('live_courses-data')[0].value; //抓課程ID

    convey_data = {
        'label': $('#tag_id' + index).text(),
        'id': l_id,
        'status': 1, //直播
    };
    $('#tag_id' + index).remove(); //畫面呈現的刪除掉

    $.ajax({
        type: "POST",
        url: "../../live_courses/deleteCourseLabel",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            if (res['status'] == false) { //刪除失敗才跳提示
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
            }
        }
    });
}

function save_tag() {
    var convey_data = {};

    //排序元素

    var l_id = document.getElementsByClassName('live_courses-data')[0].value; //抓課程ID
    $('.added_tag_area .tag').each(function (index, n) {
        convey_data[index] = {'label': $('#' + n.id).text(), 'id': l_id};
    });

    $.ajax({
        type: "POST",
        url: "../../live_courses/addLiveLabel",
        data: convey_data,
        dataType: 'json',
        success: function (res) {

            let parentElement = $("#setTagForm");
            $("#__showMessage").remove(); //刪除原先的訊息
            parentElement.append("<p id='__showMessage' class='mt-2' value='3' style='color: red'>" + res["msg"] + "</p>")

            if (res['status']) { //儲存成功後
                // 顯示訊息並於 3 秒後清除訊息
                var seconds = $("#__showMessage").attr("value");
                var countdown = setInterval(function() {
                    seconds--;
                    $("#__showMessage").attr("value", seconds);
                    if (seconds <= 0) {
                        $("#__showMessage").empty();
                        clearInterval(countdown)
                    };
                }, 1000);

                $('.tag').removeClass('temp_tag'); //刪除暫存標籤樣式
                $('.tag .fa-close').each(function (index, n) {
                    var tag_index = n.onclick.toString().substr(n.onclick.toString().lastIndexOf('(') + 1, 1); //抓取是第幾個標籤
                    n.onclick = Function("real_del_tag(" + tag_index + ")"); //將所有tag的onclick修改成真正刪除後台的方法，而不只是刪除呈現而已
                });
            }
        }
    });
}