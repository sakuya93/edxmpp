// 基本資料
function basic_information_sendOut(data) {
    console.log(data);

    //取得會說語言的字串 開始
    var speakLanguage_value = "";
    var speakLanguage_Element = document.getElementById('speakLanguage');
    for (var i = 0; i < speakLanguage_Element.options.length; i++) {
        if (speakLanguage_Element.options[i].selected) {
            speakLanguage_value += speakLanguage_Element.options[i].value + ",";
        }
    }
    speakLanguage_value = speakLanguage_value.substr(0, speakLanguage_value.length - 1);
    //取得會說語言的字串 結束

    var convey_data = {
        name: data[0].value,
        country: data[1].value,
        speak_language: speakLanguage_value,
    };

    console.log(convey_data);

    $.ajax({
        type: "POST",
        url: "become_teacher/basic_information",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var errorText = document.getElementsByClassName('basic-data-error');
            for(var i = 0; i < errorText.length ;++i)
                errorText[i].style.visibility = "hidden";

            if(res['msg'] == '姓名不可為空')
                errorText[0].style.visibility = "visible";
            if(res['msg'] == '國籍不可為空')
                errorText[1].style.visibility = "visible";
            if(res['msg'] == '會說語言不可為空')
                errorText[2].style.visibility = "visible";

            if (res['status']) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
                $('#hint_window').on('hidden.bs.modal', function () {
                })
            }else{
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
            }
        }
    });
}

// 基本資料結束

//老師介紹
function teacher_introduction_sendOut(data) {
    var convey_data = {
        very_short_description: data[0].value,
        short_description: data[1].value,
        description: data[2].value,
    };

    $.ajax({
        type: "POST",
        url: "become_teacher/teacher_introduction",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            var errorText = document.getElementsByClassName('teacher-introduction-data-error');
            for(var i = 0; i < errorText.length ;++i)
                errorText[i].style.visibility = "hidden";

            if(res['msg'] == '極短描述不可為空')
                errorText[0].style.visibility = "visible";
            if(res['msg'] == '簡短介紹不可為空')
                errorText[1].style.visibility = "visible";
            if(res['msg'] == '詳細介紹不可為空')
                errorText[2].style.visibility = "visible";
            if (res['status']) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();
                $('#hint_window').on('hidden.bs.modal', function () {

                })
            }
        }
    });
}

//老師介紹結束

// 工作經驗
var work_experience_count = 0; //第幾筆工作經驗

var work_experience_delete_data = {}; //欲刪除的資料

function add_work_experience() { //新增一筆工作經驗
    work_experience_count++;
    var work_experience_content =
        "<div class=\"mtr-3 content\" id=\"work_experience_content" + work_experience_count + "\">" +
        "<i class=\"fa fa-times btn btn-info mb-3 ml-14\" type=\"button\" onclick=\"delete_work_experience('" + work_experience_count + "')\"></i>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "<div class=\"input-title col-sm-12\">開始日期</div>" +
        "<input type=\"date\" class=\"form-control work_experience-data\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "    <div class=\"input-title col-sm-12\">結束日期</div>" +
        "    <input type=\"date\" class=\"form-control work_experience-data\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "    <div class=\"input-title col-sm-12\">單位名稱</div>" +
        "    <input type=\"text\" class=\"form-control work_experience-data\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "    <div class=\"input-title col-sm-12\">服務內容</div>" +
        "    <input type=\"text\" class=\"form-control work_experience-data\">" +
        "</div>" +
        "</div>"

    $('#work_experience_body').append(work_experience_content);
}

function load_work_experience(complex_data) { //載入工作經驗
    if (complex_data != null)
        if (complex_data.length != 0) {
            work_experience_count = complex_data.length;
            var work_experience_content = "";

            for (i = 0, j = 1; j <= work_experience_count; i++, j++) {
                var start_date = complex_data[i]['start_date'];
                var end_date = complex_data[i]['end_date'];
                var company_name = complex_data[i]['company_name'];
                var service_content = complex_data[i]['service_content'];

                work_experience_content +=
                    "<div class=\"mtr-3 content\" id=\"work_experience_content" + j + "\">" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "<div class=\"input-title col-sm-12\">開始日期</div>" +
                    "<input type=\"date\" class=\"form-control work_experience-data\" value=\"" + start_date + "\">" +
                    "</div>" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "    <div class=\"input-title col-sm-12\">結束日期</div>" +
                    "    <input type=\"date\" class=\"form-control work_experience-data\" value=\"" + end_date + "\">" +
                    "</div>" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "    <div class=\"input-title col-sm-12\">單位名稱</div>" +
                    "    <input type=\"text\" class=\"form-control work_experience-data\" value=\"" + company_name + "\">" +
                    "</div>" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "    <div class=\"input-title col-sm-12\">服務內容</div>" +
                    "    <input type=\"text\" class=\"form-control work_experience-data\" value=\"" + service_content + "\">" +
                    "</div>" +
                    "<button type=\"button\" class=\"btn btn-success mb-3 ml-14\" onclick=\"work_experience_edit('" + complex_data[i]['id'] + "'," + j + ")\">修改</button>" +
                    "<button type=\"button\" class=\"btn btn-danger mb-3 ml-14\" onclick=\"work_experience_delete('" + complex_data[i]['id'] + "')\">刪除</button>" +
                    "</div>";
            }
            $('#work_experience_body').append(work_experience_content);
        }
}

//修改工作經驗單筆資料
function work_experience_edit(ed_id, id) {
    var data = document.getElementsByClassName('work_experience-data'); //抓input資料
    var data_num = id * 4 - 3; //因為4筆資料為一組
    data_num--; //

    var convey_data = {
        start_date: data[data_num].value,
        end_date: data[data_num + 1].value,
        company_name: data[data_num + 2].value,
        service_content: data[data_num + 3].value,
        id: ed_id
    };

    $.ajax({
        type: "POST",
        url: "become_teacher/modifyWorkExperience",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        }
    });
}

//刪除工作經驗單筆資料
function work_experience_delete(id) {
    var convey_data = {id: id};

    $('#confirm').css('display', 'block');
    $('#hint_text').text("你確定要刪除嗎?");
    $('#hint_window').modal();

    $('#confirm').bind('click', function () {
        $.ajax({
            type: "POST",
            url: "become_teacher/deleteWorkExperience",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                $('#confirm').css('display', 'none');
                $('#hint_window').on('hidden.bs.modal', function () {
                    window.history.go(0);
                });
            }
        });
    });

    $('#close').bind('click', function () {
        $('#confirm').css('display', 'none');
    });
}

//刪除元素
function delete_work_experience(id) {
    var item = "work_experience_content" + id;
    document.getElementById(item).remove();

    var start_date = complex_data[id]['star_date'];
    var end_date = complex_data[id]['end_date'];
    var company_name = complex_data[id]['company_name'];
    var service_content = complex_data[id]['service_content'];

    work_experience_count--;
}

//送出工作經驗資料並儲存
function work_experience_sendOut(data) {
    var convey_data = {};

    for (i = 0, a = 0; i < work_experience_count; i++, a += 4) {
        var temp = {
            start_date: data[a].value,
            end_date: data[a + 1].value,
            company_name: data[a + 2].value,
            service_content: data[a + 3].value,
        }
        convey_data[i] = temp;
    }

    $.ajax({
        type: "POST",
        url: "become_teacher/work_experience",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
            if (res['status'] == "修改成功") {
                $('#hint_window').on('hidden.bs.modal', function () {

                })
            }
        }
    });
}

//工作經驗結束

var modify_education_count = 0;

//學歷背景
function load_education(edu_data) { //載入學習背景
    if (edu_data != null)
        if (edu_data.length != 0) {
            modify_education_count = edu_data.length;

            var education_content = "";

            for (i = 0, j = 1; j <= modify_education_count; i++, j++) {
                var start_date = edu_data[i]['start_date'];
                var end_date = edu_data[i]['end_date'];
                var school_name = edu_data[i]['school_name'];
                var department_name = edu_data[i]['department_name'];
                var certified_documents = edu_data[i]['certified_documents'];
                if (certified_documents == null) certified_documents = "";
                var ed_id = edu_data[i]['id'];

                education_content +=
                    "<div class=\"mtr-3 content\" id=\"modify_education_content" + j + "\">" +
                    "<div class=\"input-group col-sm-6\">" +
                    "<div class=\"input-title col-sm-12\">開始日期</div>" +
                    "<input type=\"date\" class=\"form-control modify_education_background-data\" value=\"" + start_date + "\">" +
                    "</div>" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "    <div class=\"input-title col-sm-12\">結束日期</div>" +
                    "    <input type=\"date\" class=\"form-control modify_education_background-data\" value=\"" + end_date + "\">" +
                    "</div>" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "    <div class=\"input-title col-sm-12\">學校名稱</div>" +
                    "    <input type=\"text\" class=\"form-control modify_education_background-data\" value=\"" + school_name + "\">" +
                    "</div>" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "    <div class=\"input-title col-sm-12\">科系名稱</div>" +
                    "    <input type=\"text\" class=\"form-control modify_education_background-data\" value=\"" + department_name + "\">" +
                    "</div>" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "    <div class=\"input-title col-sm-12\">證明文件<br>" +
                    "   <img src=\"resource/image/student/education_prove/" + certified_documents + "?value=" + Math.random() + "\" alt=\"\" class=\"\" id=\"img" + j + "\" width=\"160\" height=\"160\"></div>" +
                    "<form id=\"upload_image_form\" action=\"become_teacher/edit_education_background_image/" + ed_id + "\" enctype=\"multipart/form-data\" method=\"post\">" +
                    "<input type=\"file\" id=\"certified_documents\" name=\"certified_documents\" onchange=\"handle('img',this.files," + j + ");$('#upload_image_btn" + j + "').attr('disabled', false);\" class=\"form-control\" accept=\"image/png, image/jpeg\">" +
                    "<br><button type=\"submit\" class=\"btn btn-primary\" id='upload_image_btn" + j + "' disabled>確認更新證明文件</button>" +
                    "</form>" +
                    "</div>" +
                    "<button type=\"button\" class=\"btn btn-success mb-3 ml-14\" onclick=\"education_edit('" + ed_id + "'," + j + ")\">修改</button>" +
                    "<button type=\"button\" class=\"btn btn-danger mb-3 ml-14\" onclick=\"education_delete('" + ed_id + "')\">刪除</button>" +
                    "</div>"
            }
            $('#modify_education_body').append(education_content);
        }
}

var education_count = 0;

function add_education() {
    education_count++;

    var education_content =
        "<div class=\"mtr-3 content\" id=\"education_content" + education_count + "\">" +
        "<i class=\"fa fa-times btn btn-info mb-3 ml-14\" type=\"button\" onclick=\"delete_education('" + education_count + "')\"></i>" +
        "<div class=\"input-group col-sm-6\">" +
        "<div class=\"input-title col-sm-12\">開始日期</div>" +
        "<input type=\"date\" class=\"form-control education_background-data\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "    <div class=\"input-title col-sm-12\">結束日期</div>" +
        "    <input type=\"date\" class=\"form-control education_background-data\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "    <div class=\"input-title col-sm-12\">學校名稱</div>" +
        "    <input type=\"text\" class=\"form-control education_background-data\">" +
        "</div>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "    <div class=\"input-title col-sm-12\">科系名稱</div>" +
        "    <input type=\"text\" class=\"form-control education_background-data\">" +
        "</div>" +
        "   <input type=\"text\" class=\"form-control education_background-data\" value=\"\" style=\"display: none\">" + //傳值用
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "    <div class=\"input-title col-sm-12\">證明文件<br>" +
        "   <img src=\"resource/image/student/education_prove/\" alt=\"\" class=\"\" id=\"img" + (education_count + modify_education_count) + "\" width=\"160\" height=\"160\"></div>" +
        "    <input type=\"file\" onchange=\"handle('img',this.files," + (education_count + modify_education_count) + ")\" class=\"form-control\" accept=\"image/png, image/jpeg, .pdf\">" +
        "</div>"

    $('#education_body').append(education_content);
    //圖片測試 name="certified_document" id="certified_document1"
}

function delete_education(id) {
    var item = "education_content" + id;
    document.getElementById(item).remove();
    education_count--;
}

//修改學歷背景
function education_edit(ed_id, id) {
    var data = document.getElementsByClassName('modify_education_background-data'); //抓input資料
    var data_num = id * 4 - 3; //因為4筆資料為一組
    data_num--; //因為資料是從第0筆開始

    var convey_data = {
        start_date: data[data_num].value,
        end_date: data[data_num + 1].value,
        school_name: data[data_num + 2].value,
        department_name: data[data_num + 3].value,
        id: ed_id
    };

    $.ajax({
        type: "POST",
        url: "become_teacher/edit_education_background_data",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        }
    });
}

//刪除學歷背景
function education_delete(id) {
    var convey_data = {id: id};

    $('#confirm').css('display', 'block');
    $('#hint_text').text("你確定要刪除嗎?");
    $('#hint_window').modal();

    $('#confirm').bind('click', function () {
        $.ajax({
            type: "POST",
            url: "become_teacher/delete_education_background",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                $('#confirm').css('display', 'none');
                $('#hint_window').on('hidden.bs.modal', function () {
                    window.history.go(0);
                });
            }
        });
    });

    $('#close').bind('click', function () {
        $('#confirm').css('display', 'none');
    });

}

//學歷背景 結束

//教學證照
var modify_teaching_license_count = 0;

function load_teaching_license(tl_data) {
    if (tl_data != null)
        if (tl_data.length != 0) {
            modify_teaching_license_count = tl_data.length;
            var teaching_license_content = "";

            for (i = 0, j = 1; j <= modify_teaching_license_count; i++, j++) {
                var name = tl_data[i]['name'];
                var file = tl_data[i]['file'];
                var tl_id = tl_data[i]['id'];

                teaching_license_content +=
                    "<div class=\"mtr-3 content\" id=\"modify_teaching_license_content" + j + "\">" +
                    "<div class=\"input-group col-sm-6 mb-3\">" +
                    "<div class=\"input-title col-sm-12\">證明文件(非必填: 若無教學證照必須有相關教學工作經驗才可申請)" +
                    "</div>" +
                    "<input type=\"text\" class=\"form-control modify_teaching_license-data\" placeholder=\"輸入證照名稱\" value=\"" + name + "\">" +
                    "<form id=\"upload_tlPhoto_form\" class=\"col-sm-12\" action=\"become_teacher/edit_teaching_license_image/" + tl_id + "\" enctype=\"multipart/form-data\" method=\"post\">" +
                    "<input type=\"file\" class=\"form-control no-border\" name='teaching_license' onchange=\"handle('tl',this.files," + j + ");$('#upload_tlPhoto_btn" + j + "').attr('disabled', false);\" accept=\"image/png, image/jpeg\">" +
                    "<button type=\"submit\" class=\"btn btn-primary\" style='margin: 10px 0 0 13px' id='upload_tlPhoto_btn" + j + "' disabled>確認更新證明文件</button>" +
                    "</form>" +
                    "<div style='margin-top: 10px;width: 500px'>" +
                    "<img src=\"resource/image/student/teaching_license/" + file + "?value=" + Math.random() + "\" id=\"tl" + j + "\" width=\"160\" height=\"160\">" +
                    "</div>" +
                    "</div>" +
                    "<button type=\"button\" class=\"btn btn-success mb-3 ml-14\" onclick=\"teaching_license_edit('" + tl_id + "'," + j + ")\">修改</button>" +
                    "<button type=\"button\" class=\"btn btn-danger mb-3 ml-14\" onclick=\"teaching_license_delete('" + tl_id + "')\">刪除</button>" +
                    "</div>";
            }
            $('#modify_teaching_license_body').append(teaching_license_content);
        }
}

var teaching_license_count = 0;

function add_teaching_license() {
    teaching_license_count++;

    var teaching_license_content =
        "<div class=\"mtr-3 content\" id=\"teaching_license_content" + teaching_license_count + "\">" +
        "<i class=\"fa fa-times btn btn-info mb-3 ml-14\" type=\"button\" onclick=\"delete_teaching_license('" + teaching_license_count + "')\"></i>" +
        "<div class=\"input-group col-sm-6 mb-3\">" +
        "<div class=\"input-title col-sm-12\">證明文件(非必填: 若無教學證照必須有相關教學工作經驗才可申請)" +
        "</div>" +
        "<input type=\"text\" class=\"form-control teaching_license-data\" placeholder=\"輸入證照名稱\">" +
        "<input type=\"file\" class=\"form-control no-border teaching_license-data\" name='teaching_license' onchange=\"handle('tl',this.files," + (modify_teaching_license_count + teaching_license_count) + ")\" accept=\"image/png, image/jpeg\">" +
        "<div style='margin-top: 10px;width: 500px'>" +
        "<img src=\"\" id=\"tl" + (modify_teaching_license_count + teaching_license_count) + "\" width=\"160\" height=\"160\">" +
        "</div>" +
        "</div>" +
        "</div>"

    $('#teaching_license_body').append(teaching_license_content);
}

//修改教學證照
function teaching_license_edit(tl_id, id) {
    var data = document.getElementsByClassName('modify_teaching_license-data'); //抓input資料

    var data_num = id;
    data_num--; //因為資料是從第0筆開始

    var convey_data = {
        fileName: data[data_num].value,
        id: tl_id
    };

    $.ajax({
        type: "POST",
        url: "become_teacher/edit_teaching_license_data",
        data: convey_data,
        dataType: 'json',
        success: function (res) {
            $('#hint_text').text(res['msg']);
            $('#hint_window').modal();
        }
    });
}

//刪除教學證照
function teaching_license_delete(id) {
    var convey_data = {id: id};
    $('#confirm').css('display', 'block');
    $('#hint_text').text("你確定要刪除嗎?");
    $('#hint_window').modal();

    $('#confirm').bind('click', function () {
        $.ajax({
            type: "POST",
            url: "become_teacher/delete_teaching_license",
            data: convey_data,
            dataType: 'json',
            success: function (res) {
                $('#hint_text').text(res['msg']);
                $('#hint_window').modal();

                $('#confirm').css('display', 'none');
                $('#hint_window').on('hidden.bs.modal', function () {
                    window.history.go(0);
                });
            }
        });
    });

    $('#close').bind('click', function () {
        $('#confirm').css('display', 'none');
    });
}

function delete_teaching_license(id) {
    console.log(id);
    var item = "teaching_license_content" + id;
    document.getElementById(item).remove();
    teaching_license_count--;
}

//提交教學證照資料
$("#teaching_license_form").submit(function (event) {
    event.preventDefault(); //阻止自動提交

    var data = document.getElementsByClassName('teaching_license-data'); //抓input資料

    var fd = new FormData();

    for (var i = 0, a = 0; i < teaching_license_count; i++, a += 2) {
        if (event.target[a + 1].files[0] == undefined) {
            $('#hint_text').text("請檢察資料，至少要選取一張可瀏覽的證明文件!");
            $('#hint_window').modal();
            return;
        }
        //圖片檔案處理
        fd.append("fileName" + i, data[a].value);
        fd.append("file" + i, event.target[a + 1].files[0]);

    }

    fd.append("teaching_license_count", teaching_license_count);

    $.ajax({
        url: "become_teacher/teaching_license",
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
            if (res['status']) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    window.history.go(0);
                });
            }
        },
    });
});

//提交學歷背景資料
$("#education_background_form").submit(function (event) {
    event.preventDefault(); //阻止自動提交

    var data = document.getElementsByClassName('education_background-data'); //抓input資料

    var fd = new FormData();

    for (var i = 0, a = 0; i < education_count; i++, a += 5) {
        var img = document.getElementById('img' + (i + 1)).src;
        var img_name = img.substring(img.lastIndexOf('/') + 1);
        var ed_id = data[a + 4].value;

        if (ed_id == "" && event.target[a + 5 + i].files[0] == undefined) {
            $('#hint_text').text("請檢察資料，至少要選取一張可瀏覽的證明文件!");
            $('#hint_window').modal();
            return;
        }

        //圖片檔案處理 有問題
        fd.append("start_date" + i, data[a].value);
        fd.append("end_date" + i, data[a + 1].value);
        fd.append("school_name" + i, data[a + 2].value);
        fd.append("department_name" + i, data[a + 3].value);
        fd.append("id" + i, ed_id);
        fd.append("img_name" + i, img_name);
        fd.append("file" + i, event.target[a + 5 + i].files[0]);
    }

    fd.append("education_count", education_count);

    $.ajax({
        url: "become_teacher/education_background",
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
            if (res['status']) {
                $('#hint_window').on('hidden.bs.modal', function () {
                    window.history.go(0);
                });
            }
        },
    });
});

/*切換證明文件照片*/
function handle(img_id, file, id) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('#' + img_id + id).attr('src', e.target.result);
    }
    reader.readAsDataURL(file[0]);

}