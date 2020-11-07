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

/*        系列課程影片        */
//傳送影片課程資料
$("#film_courses_form").submit(function (event) {
    event.preventDefault();
    var data = document.getElementsByClassName('film_courses-data');
    var fd = new FormData();
    fd.append('name', data[0].value);
    fd.append('experienceFilmName', data[1].value);
    fd.append('experienceFilm', data[2].value);
    fd.append('type', data[3].value);
    fd.append('introduction', CKEDITOR.instances.editor1.getData());
    fd.append('brief_introduction', CKEDITOR.instances.editor2.getData());
    fd.append('hours', data[4].value);
    fd.append('currency', data[5].value);
    fd.append('price', data[6].value);
    fd.append('thumbnail', event.target[5].files[0])

    var tag_array = [];
    $('.added_tag_area .tag').each(function (index, n) {
        tag_array[index] = $('#' + n.id).text();
    });

    fd.append("label", tag_array);

    $.ajax({
        url: "teacher/Film_course/add_coursesBasicInformation",
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
            } else if (res['msg'] == '請填寫影片介紹名稱') {
                errorText[1].style.visibility = "visible";
                index = 1;
            } else if (res['msg'] == '請填寫介紹影片') {
                errorText[2].style.visibility = "visible";
                index = 2;
            } else if (res['msg'] == '請填寫課程類型') {
                errorText[3].style.visibility = "visible";
                index = 3;
            } else if (res['msg'] == '請填寫課程介紹') {
                errorText[4].style.visibility = "visible";
                index = 4;
            } else if (res['msg'] == '請填寫課程簡介') {
                errorText[5].style.visibility = "visible";
                index = 5;
            } else if (res['msg'] == '課程時數只能填寫數字') {
                errorText[6].style.visibility = "visible";
                index = 6;
            } else if (res['msg'] == '請選擇貸幣') {
                errorText[7].style.visibility = "visible";
                index = 7;
            } else if (res['msg'] == '價格只能填寫數字') {
                errorText[8].style.visibility = "visible";
                index = 8;
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
                // $('#hint_text').text(res['msg']);
                // $('#hint_window').modal();

                // 顯示訊息並於 3 秒後清除訊息
                let parentElement = $("#setTagForm");
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

                window.location = "course_management/index/type_film_course";
            }
        },
    });
});
$("#edit_film_courses_form").submit(function (event) {
    event.preventDefault(); //阻止自動提交
    var data = document.getElementsByClassName('film_courses-data'); //抓input資料
    var Data = {
        id: location.href.substring(location.href.length - 13),
        name: data[0].value,
        experienceFilmName: data[1].value,
        experienceFilm: data[2].value,
        type: data[3].value,
        introduction: CKEDITOR.instances.editor1.getData(),
        brief_introduction: CKEDITOR.instances.editor2.getData(),
        hours: data[4].value,
        currency: data[5].value,
        price: data[6].value,
    };

    $.ajax({
        url: "../teacher/Film_course/edit_coursesBasicInformation",
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
            } else if (res['msg'] == '請填寫影片介紹名稱') {
                errorText[1].style.visibility = "visible";
                index = 1;
            } else if (res['msg'] == '請填寫介紹影片') {
                errorText[2].style.visibility = "visible";
                index = 2;
            } else if (res['msg'] == '請填寫課程類型') {
                errorText[3].style.visibility = "visible";
                index = 3;
            } else if (res['msg'] == '請填寫課程介紹') {
                errorText[4].style.visibility = "visible";
                index = 4;
            } else if (res['msg'] == '請填寫課程簡介') {
                errorText[5].style.visibility = "visible";
                index = 5;
            } else if (res['msg'] == '課程時數只能填寫數字') {
                errorText[6].style.visibility = "visible";
                index = 6;
            } else if (res['msg'] == '請選擇貨幣') {
                errorText[7].style.visibility = "visible";
                index = 7;
            } else if (res['msg'] == '價格只能填寫數字') {
                errorText[8].style.visibility = "visible";
                index = 8;
            } else {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
            }

            if (index != -1) {
                errorText[index].scrollIntoView(true);
                var viewportH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
                window.scrollBy(0, -viewportH / 2);
            }

            if (res['status']) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
                $('#hint_window').on('hide.bs.modal', function () {
                });
            }
        },
    });
});
var actualMovie_body_count = 0;

//新增一筆影片資訊
function add_film() {
    actualMovie_body_count++;
    var actualMovie_body_content =
        "<div class=\"mtr-3 content\" id=\"film_content" + actualMovie_body_count + "\">" +
        "<i class=\"fa fa-times btn btn-info mb-3 ml-14\" type=\"button\" onclick=\"delete_film('" + actualMovie_body_count + "')\"></i>" +
        "<div class=\"col-sm-6 mb-3\">" +
        "	<div class=\"series_search_title col-sm-12\">單元名稱</div>" +
        "	<input type=\"text\" class=\"series_search_content col-sm-12 ml-1 actualMovie-data\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "        <div class=\"input-title col-sm-12\">課程名稱:</div>" +
        "        <input type=\"text\" class=\"form-control actualMovie-data\" value=\"\">" +
        "                    </div>        " +
        "                    <div class=\"input-group col-sm-6 mb-3\">" +
        "        <div class=\"input-title col-sm-12 dy-inline\">影片網址(複製Youtube影片網址)" +
        "            <button class=\"btn fa fa-info-circle\" id=\"video_info\" onclick=\"video_info_open('../');\" type=\"button\"" +
        "                    data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"體驗影片網址提示\"></button>" +
        "        </div>        " +
        "        <input type=\"text\" class=\"form-control actualMovie-data\" value=\"\">" +
        "                    </div>" +
        "                    </div>";

    $('#actualMovie_body').append(actualMovie_body_content);
}

function delete_film(id) {
    var item = "film_content" + id;
    document.getElementById(item).remove();

    actualMovie_body_count--;
}


//傳送影片資料
$("#addActualMovie_form").submit(function (event) {
    event.preventDefault();
    var data = document.getElementsByClassName('actualMovie-data'); //抓input資料
    var dataArray = {};
    for (var i = 0, a = 0; i < data.length; i += 3, a++) {
        var temp = {
            unitName: data[i].value,
            name: data[i + 1].value,
            film: data[i + 2].value,
        }
        dataArray[a] = temp;
    }
    dataArray['id'] = location.href.substring(location.href.length - 13);

    //判斷資料是否相同
    var dataArray_length = Object.keys(dataArray).length - 1; //因為 id 也被算在其中一個長度，所以扣1
    var error = false;
    if (dataArray_length > 1) {
        for (var i = 0; i < dataArray_length - 1; i++) {
            for (var j = (i + 1); j < dataArray_length; j++) {
                if (dataArray[i]['name'] == dataArray[j]['name']) {
                    $('#hint_text').text("兩單元的課程名稱不能相同!");
                    $('#hint_window').modal();
                    error = true;
                    break;
                } else if (dataArray[i]['film'] == dataArray[j]['film']) {
                    $('#hint_text').text("兩單元的影片網址不能相同!");
                    $('#hint_window').modal();
                    error = true;
                    break;
                }
            }
        }
    }

    if (!error) {
        $.ajax({
            type: "POST",
            url: "../teacher/Film_course/add_actualMovie",
            data: dataArray,
            dataType: 'json',
            success: function (res) {
                let parentElement = $("#updateActualMovieForm");
                parentElement.append("<p id='__showMessage' class='mb-2 ml-3' value='3' style='color: red'>" + res["msg"] + "</p>")
                if (res['status']) {
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

                }
            }
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
            url: "../film_course/update_thumbnail",
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
    });


    /*課程標籤初始資料載入區--------------------------------------------*/
    var url_type = location.href.substring(location.href.lastIndexOf('/') + 1); //取得頁面功能(是否為新增頁面)

    if (url_type != 'add_film_course') { //如果是編輯頁面才執行
        var l_id = location.href.substring(location.href.lastIndexOf('/') + 1).substring(0,13); //取得課程ID

        var convey_data = {
            'status': 0, //影片
            'id': l_id
        };

        $.ajax({
            type: "POST",
            url: "../film_course/getCourseLabel",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                for (var i = 0; i < res.length - 1; i++) {
                    $('.added_tag_area').append('<span class="tag" id="tag_id' + (i + 1) + '" + data-index="' + (i + 1) + '">' + res[i] + '<i class="fa fa-close" onclick="real_del_tag(' + (i + 1) + ')"></i></span>');
                }
            }
        });

        tag_autocomplete("../", "film_course"); //自動完成標籤填詞
    }else{
        tag_autocomplete("", "film_course"); //自動完成標籤填詞
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
        'status': 0, //影片課程
    };
    $('#tag_id' + index).remove(); //畫面呈現的刪除掉

    $.ajax({
        type: "POST",
        url: "../film_course/deleteCourseLabel",
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
        url: "../film_course/addFilmLabel",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            // 顯示訊息並於 3 秒後清除訊息
            let parentElement = $("#setTagForm");
            parentElement.append("<p id='__showMessage' class='mb-2' value='3' style='color: red'>儲存成功</p>")


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