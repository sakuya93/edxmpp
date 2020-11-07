$(document).ready(function () {
    getCourseFavorite(1);
});

function getCourseFavorite(status) { //取得收藏課程
    var convey_data = {
        status: status,
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "course_favorite/getCourseFavorite",
        data: convey_data,
        success: function (res) {
            console.log(res);
            $("#favorite__card-group").empty();

            if(res.length > 0) {
                $(".favorite__container-empty").css("display", "none");
                $("footer").css("position", "absolute");
                for (var i = 0; i < res.length; i++) {
                    if (res[i]['briefIntroduction'] == null) {
                        res[i]['briefIntroduction'] = "目前無簡介"
                    }
                    var card_content = '' +
                        '            <div class="card">' +
                        '                <span class="card-header bg-transparent">' +
                        '                    <figure><img src="'+res[i]['path'] + res[i]["image"] + '" class="card-img-top"></figure>' +
                        '                    <span class="heart btn_favorite" onclick="delCourseFavorite(\'' + res[i]["id"] + '\')">' +
                        '                        <i class="fa fa-heart" id="' + res[i]["id"] + '"></i>' +
                        '                    </span>' +
                        '                </span>' +
                        '                <span class="card-body">' +
                        '                    <h3 class="card-title">' +
                        '                        <b>' + res[i]["teacherName"] + '</b>' +
                        '                        <h4>' + res[i]["courseName"] + '</h4>' +
                        '                    </h3>' +
                        '                    <p class="card-text">' + res[i]["briefIntroduction"] + '</p>' +
                        '                    <h4 class="text-mute">' + res[i]["type"] + '</h4>' +
                        '                </span>' +
                        '                <span class="card-footer bg-transparent d-flex">' +
                        '                    <a type="button" href="Teacher_sales/live/' + res[i]["id"] + '?c=TWD" class="btn btn-outline-info btn_goCourse">前往課程</a>' +
                        '                </span>' +
                        '            </div>'
                    '';
                    $("#favorite__card-group").append(card_content);
                }
            }else{
                $(".favorite__container-empty").css("display", "flex");
            }
        }
    });
}

function openHintWindow(id) {
    // Collection_course/favorite
    $("#hint_text").html(
        "<p>確定要移除課程收藏嗎?</p>"
    );
    $("#hint_window").modal("show");

    $("#confirm").attr("onclick", "delCourseFavorite('" + id + "')");
    $("#confirm").css("display", "block");
}

function delCourseFavorite(id) {
    var convey_data = {
        id: id,
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        data: convey_data,
        url: "course_favorite/favorite",
        data: convey_data,
        success: function (res) {
            // console.log(res);
            // $("#confirm").attr("onclick", "");
            // $("#confirm").css("display", "none");
            // $("#hint_window").modal("hide");
            // window.location.reload();
            $("#" + id).css("color", "gray")
        }
    });
}