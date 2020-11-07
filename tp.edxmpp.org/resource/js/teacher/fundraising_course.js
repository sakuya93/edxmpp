/*        影片提示資訊        */
function video_info_open(path) {
    var content = [
        '<img src=' + path + 'resource/image/share/video_info.png>'
    ];
    $('#hint_text').html(content);
    $('#hint_window').modal();
}

/*        影片網址提示        */
function video_url_info_open() {
    var content = [
        '<img src="resource/image/share/film_url_info.png">'
    ];
    $('#hint_text').html(content);
    $('#hint_window').modal();
}

/*        切換課程縮圖        */
function handle(file, id) {

    var reader = new FileReader();

    id = "#" + id;
    reader.onload = function (e) {
        $(id).attr('src', e.target.result);
    }
    reader.readAsDataURL(file[0]);

}

//傳送募資課程資料
$("#fundraising_course_form").submit(function (event) {
    event.preventDefault();
    var data = document.getElementsByClassName('fundraising_course-data');

    var date = new Date();
    var m = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1); //月
    var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(); //日
    var today = date.getFullYear() + "-" + m + "-" + day + " " + date.getHours() + ":" + date.getMinutes(); //今天日期

    if (Date.parse(today).valueOf() > Date.parse(data[8].value).valueOf()) {
        // 顯示訊息並於 3 秒後清除訊息
        let parentElement = $("#setFundraisingCourseForm");
        parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>募資結束時間不能早於現在時間</p>")
        var seconds = $("#__showMessage").attr("value");
        var countdown = setInterval(function() {
            seconds--;
            $("#__showMessage").attr("value", seconds);
            if (seconds <= 0) {
                $("#__showMessage").empty();
                clearInterval(countdown)
            };
        }, 1000);
    }
    else {
        var fd = new FormData();
        fd.append('course_name', data[0].value);
        fd.append('film_rul', data[1].value);
        fd.append('type', data[2].value);
        fd.append('courseIntroduction', CKEDITOR.instances.editor1.getData());
        fd.append('brief_introduction', CKEDITOR.instances.editor2.getData());
        fd.append('currency', data[3].value);
        fd.append('hours', data[4].value);
        fd.append('normal_price', data[5].value);
        fd.append('fundraising_price', data[6].value);
        fd.append('expected_number', data[7].value);
        fd.append('endTime', data[8].value);
        fd.append('image', event.target[12].files[0]);

        var tag_array = [];
        $('.added_tag_area .tag').each(function (index, n) {
            tag_array[index] = $('#' + n.id).text();
        });

        fd.append("label", tag_array);

        $.ajax({
            url: "fundraisingCourse_management/addFundraisingCourse",
            type: "POST",
            data: fd,
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                res = JSON.parse(res);

                var errorText = document.getElementsByClassName('basic-data-error');
                for (var i = 0; i < errorText.length; ++i)
                    errorText[i].style.visibility = "hidden";

                var index = -1;

                if (res['msg'] == '請填寫課程名稱') {
                    errorText[0].style.visibility = "visible";
                    index = 0;
                } else if (res['msg'] == '請填寫影片網址') {
                    errorText[1].style.visibility = "visible";
                    index = 1;
                } else if (res['msg'] == '請選擇募資課程的上課類型') {
                    errorText[2].style.visibility = "visible";
                    index = 2;
                } else if (res['msg'] == '請填寫課程介紹') {
                    errorText[3].style.visibility = "visible";
                    index = 3;
                } else if (res['msg'] == '請填寫課程簡介') {
                    errorText[4].style.visibility = "visible";
                    index = 4;
                } else if (res['msg'] == '請選擇貨幣種類') {
                    errorText[5].style.visibility = "visible";
                    index = 5;
                } else if (res['msg'] == '課程時數只能填寫數字') {
                    errorText[6].style.visibility = "visible";
                    index = 6;
                } else if (res['msg'] == '原始價格只能填寫數字') {
                    errorText[7].style.visibility = "visible";
                    index = 7;
                } else if (res['msg'] == '募資價格只能填寫數字') {
                    errorText[8].style.visibility = "visible";
                    index = 8;
                } else if (res['msg'] == '預計人數只能填寫數字') {
                    errorText[9].style.visibility = "visible";
                    index = 9;
                } else if (res['msg'] == '請填寫結束時間') {
                    errorText[10].style.visibility = "visible";
                    index = 10;
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
                    window.location = "course_management/index/type_fundraising_course";
                }
            },
        });
    }
});

$("#edit_fundraising_course_form").submit(function (event) {
    event.preventDefault(); //阻止自動提交
    var data = document.getElementsByClassName('fundraising_course-data'); //抓input資料

    var date = new Date();
    var m = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1); //月
    var day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(); //日
    var today = date.getFullYear() + "-" + m + "-" + day + " " + date.getHours() + ":" + date.getMinutes(); //今天日期

    if (Date.parse(today).valueOf() > Date.parse(data[8].value).valueOf()) {
        // 顯示訊息並於 3 秒後清除訊息
        let parentElement = $("#setFundraisingCourseForm");
        parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>募資結束時間不能早於現在時間</p>")
        var seconds = $("#__showMessage").attr("value");
        var countdown = setInterval(function() {
            seconds--;
            $("#__showMessage").attr("value", seconds);
            if (seconds <= 0) {
                $("#__showMessage").empty();
                clearInterval(countdown)
            };
        }, 1000);
    }
    else{
        var Data = {
            id: location.href.substring(location.href.length - 13),
            course_name: data[0].value,
            film_rul: data[1].value,
            type: data[2].value,
            courseIntroduction: CKEDITOR.instances.editor1.getData(),
            brief_introduction: CKEDITOR.instances.editor2.getData(),
            currency: data[3].value,
            hours: data[4].value,
            normal_price: data[5].value,
            fundraising_price: data[6].value,
            expected_number: data[7].value,
            endTime: data[8].value
        };

        $.ajax({
            url: "../teacher/fundraisingCourse_management/editFundraisingCourse",
            type: "POST",
            data: Data,
            datatype: 'json',
            success: function (res) {
                res = JSON.parse(res);
                var errorText = document.getElementsByClassName('basic-data-error');
                for (var i = 0; i < errorText.length; ++i)
                    errorText[i].style.visibility = "hidden";

                var index = -1;

                if (res['msg'] == '請填寫課程名稱') {
                    errorText[0].style.visibility = "visible";
                    index = 0;
                } else if (res['msg'] == '請填寫影片網址') {
                    errorText[1].style.visibility = "visible";
                    index = 1;
                } else if (res['msg'] == '請選擇募資課程的上課類型') {
                    errorText[2].style.visibility = "visible";
                    index = 2;
                } else if (res['msg'] == '請填寫課程介紹') {
                    errorText[3].style.visibility = "visible";
                    index = 3;
                } else if (res['msg'] == '請填寫課程簡介') {
                    errorText[4].style.visibility = "visible";
                    index = 4;
                } else if (res['msg'] == '請選擇貨幣種類') {
                    errorText[5].style.visibility = "visible";
                    index = 5;
                } else if (res['msg'] == '課程時數只能填寫數字') {
                    errorText[6].style.visibility = "visible";
                    index = 6;
                } else if (res['msg'] == '原始價格只能填寫數字') {
                    errorText[7].style.visibility = "visible";
                    index = 7;
                } else if (res['msg'] == '募資價格只能填寫數字') {
                    errorText[8].style.visibility = "visible";
                    index = 8;
                } else if (res['msg'] == '預計人數只能填寫數字') {
                    errorText[9].style.visibility = "visible";
                    index = 9;
                } else if (res['msg'] == '請填寫結束時間') {
                    errorText[10].style.visibility = "visible";
                    index = 10;
                } else {

                }

                if (index != -1) {
                    errorText[index].scrollIntoView(true);
                    var viewportH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
                    window.scrollBy(0, -viewportH / 2);
                }

                if (res['status']) {
                    // 顯示訊息並於 3 秒後清除訊息
                    let parentElement = $("#setFundraisingCourseForm");
                    parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>" + res["msg"] + "</p>")
                    var seconds = $("#__showMessage").attr("value");
                    var countdown = setInterval(function() {
                        seconds--;
                        $("#__showMessage").attr("value", seconds);
                        if (seconds <= 0) {
                            $("#__showMessage").empty();
                            clearInterval(countdown)
                        };
                    }, 1000);
                }
            },
        });
    }
});


$(document).ready(function () {
    //更改影片網址方法
    var video_url = "https://www.youtube.com/embed/";
    if ($("#video_url_input").val()) {
        $("#video").attr("src", video_url + ($("#video_url_input").val()));
    }
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
        fd.append("id", location.href.substring(location.href.length - 13));

        $.ajax({
            url: "../fundraisingCourse_management/update_image",
            type: "POST",
            data: fd,
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                res = JSON.parse(res);
                // $('#hint_text').text(res['msg']);
                // $('#hint_window').modal();

                // 顯示訊息並於 3 秒後清除訊息
                let parentElement = $("#setThumbnailForm");
                parentElement.append("<p id='__showMessage' class='ml-2 d-flex justify-content-center align-items-center' value='3' style='color: red'>儲存成功</p>")
                var seconds = $("#__showMessage").attr("value");
                var countdown = setInterval(function() {
                    seconds--;
                    $("#__showMessage").attr("value", seconds);
                    if (seconds <= 0) {
                        $("#__showMessage").empty();
                        clearInterval(countdown)
                    };
                }, 1000);


                $('#upload_image_btn').attr('disabled', true);
            },
        });
    });

    var url_type = location.href.substring(location.href.lastIndexOf('/') + 1); //取得頁面功能(是否為新增頁面)
    
    if (url_type != 'add_fundraising_course') { //如果是編輯頁面才執行
        tag_autocomplete("../", "fundraisingCourse_management");
    }else{
        tag_autocomplete("", "fundraisingCourse_management");
    }


});

/*課程標籤區 --------------------------------------------------------------------------*/
var l_id = location.href.substring(location.href.lastIndexOf('/') + 1).substring(0,13); //取得課程ID

function add_course_tag() {
    var tagLastItem = $('.added_tag_area .tag:last-child'); //取得最後一個tag元素
    if (tagLastItem.attr("data-index")) {
        var tag_length = parseInt(tagLastItem.attr("data-index")) + 1;//用來給下一個標籤設ID用的
    } else {
        var tag_length = 1;
    }


    if ($('#tag_value').val() != "") {
        $('.added_tag_area').append('<span class="tag temp_tag" id="tag_id' + tag_length + '" + data-index="' + tag_length + '">' + $('#tag_value').val() + '<i class="fa fa-close" onclick="del_tag(' + tag_length + ')"></i></span>');
        $('#tag_value').val("");
    }
}

/*刪除的不影響後端資料庫*/
function del_tag(index) {
    $('#tag_id' + index).remove();
}

/*刪除的是真的資料庫本身資料*/
function real_del_tag(index) {
    var convey_data = {};

    convey_data = {
        'label': $('#tag_id' + index).text(),
        'id': l_id,
        'status': 2, //影片課程
    };
    $('#tag_id' + index).remove(); //畫面呈現的刪除掉

    $.ajax({
        type: "POST",
        url: "../fundraisingCourse_management/deleteCourseLabel",
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

    $('.added_tag_area .tag').each(function (index, n) {
        convey_data[index] = {'label': $('#' + n.id).text(), 'id': l_id};
    });

    $.ajax({
        type: "POST",
        url: "../fundraisingCourse_management/addFundraisingLabel",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            // $('#hint_text').text(res['msg']);
            // $('#hint_window').modal();
            // 顯示訊息並於 3 秒後清除訊息
            let parentElement = $("#setTagForm");
            parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>" + res["msg"] + "</p>")

            if (res['status']) { //儲存成功後
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


