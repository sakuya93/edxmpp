/*影片提示資訊*/
function video_info_open() {
    var content = [
        '<img src="resource/image/share/video_info.png">'
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
    //更改影片網址方法
    var video_url = "https://www.youtube.com/embed/";
    $("#video_url_input").bind("input propertychange", function (event) {

        if ($("#video_url_input").val()) {
            $("#video").attr("src", video_url + ($("#video_url_input").val()));
        }
    });


    //傳送直播課程資料
    $("#live_courses_form").submit(function (event) {
        event.preventDefault(); //阻止自動提交

        var data = document.getElementsByClassName('live_courses-data'); //抓input資料
        var fd = new FormData(); //欲傳送資料

        if (event.target[4].files[0] == undefined) {
            $('#hint_text').text("請選擇課程縮圖後在新增!!");
            $('#hint_window').modal();
            return;
        } else {
            fd.append("actualMovie", data[0].value);
            fd.append("experienceFilm", data[1].value);
            fd.append("type", data[2].value);
            fd.append("thumbnail", event.target[4].files[0]);
            fd.append("introduction", CKEDITOR.instances.editor1.getData());
            fd.append("brief_introduction", CKEDITOR.instances.editor2.getData());
            fd.append("hours", data[3].value);
            fd.append("numberPeople", data[4].value);
            var radio = $("input[name='edit__control']:checked").val();

            fd.append('classMode', radio);

            var tag_array = [];
            $('.added_tag_area .tag').each(function (index, n) {
                tag_array[index] = $('#' + n.id).text();
            });

            fd.append("label", tag_array);
        }

        $.ajax({
            url: "live_courses/add_courses",
            type: "POST",
            data: fd,
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                res = JSON.parse(res);
                var errorText = document.getElementsByClassName('live-courses-data-error');
                for (var i = 0; i < errorText.length; ++i)
                    errorText[i].style.visibility = "hidden";

                if (res['msg'] == '請填寫直播名稱') {
                    errorText[0].style.visibility = "visible";
                    errorText[0].scrollIntoView(true);
                }
                if (res['msg'] == '請填寫體驗影片') {
                    errorText[1].style.visibility = "visible";
                    errorText[1].scrollIntoView(true);
                }
                if (res['msg'] == '請填寫課程類型') {
                    errorText[2].style.visibility = "visible";
                    errorText[2].scrollIntoView(true);
                }
                if (res['msg'] == '請填寫課程介紹') {
                    errorText[3].style.visibility = "visible";
                    errorText[3].scrollIntoView(true);
                }
                if (res['msg'] == '請填寫課程簡介') {
                    errorText[4].style.visibility = "visible";
                    errorText[4].scrollIntoView(true);
                }
                if (res['msg'] == '請填寫課程時數') {
                    errorText[5].style.visibility = "visible";
                    errorText[5].scrollIntoView(true);
                }
                if (res['msg'] == '上課程數只能填寫數字') {
                    errorText[6].style.visibility = "visible";
                    errorText[6].scrollIntoView(true);
                }

                var viewportH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
                window.scrollBy(0, -viewportH / 2);
                if (res['status']) {
                    // $('#hint_text').text(res['msg']);
                    // $('#hint_window').modal();
                    // $('#hint_window').on('hide.bs.modal', function () {
                        window.location = "course_management/index/type_live_course";
                    // });
                }
            },
        });
    });

    tag_autocomplete("","live_courses"); //自動完成標籤填詞
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
        $('.added_tag_area').append('<span class="tag temp_tag" id="tag_id' + tag_length + '" + data-index="' + tag_length + '">' + $('#tag_value').val() + '<i class="fa fa-close" onclick="del_tag(' + tag_length + ')"></i></span>');
        $('#tag_value').val("");
    }
}

/*刪除的不影響後端資料庫*/
function del_tag(index) {
    $('#tag_id' + index).remove();
}