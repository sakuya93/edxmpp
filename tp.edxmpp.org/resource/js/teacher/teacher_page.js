// 老師資料載入
function userInfoDetailBuild(data) {
    if (data.length == 0) {
        var content = "<li><b class=\"username\"> 載入資料錯誤 </b></li>";
        $('#user_info_detail').append(content);
    } else {
        var pathname = window.location.pathname;
        var userID = pathname.substring(pathname.indexOf("teacher_page/") + 13, pathname.lastIndexOf("/"));

        var content = "<li><b class=\"username\">" + data['name'] + "</b></li>" +
            "                <li>註冊時間: " + data['registeredData'] + "</li>" +
            "                <li>身分: " + data['identity'] + "</li>" +
            "                <li>會說語言: " + data['speakLanguage'] + "</li>" +
            "                <li>國籍: " + data['country'] + "</li>";
        // "                <li>身分ID: " + userID + "</li>";
        $('#user_info_detail').append(content);
    }
}


//課程載入
function courseListBuild(data) {
    //直播課程載入
    if (data['live'].length == 0) {
        var content = "<div class=\"cb-info d-block\">" +
            "               <span>目前尚無課程</span>" +
            "          </div>";
        $('#live_course_list').append(content);
    } else {
        for (i = 0; i < data['live'].length; i++) {
            var content = "<div class=\"cb-info row\">" +
                "               <div class=\"col-sm-2\" style=\"display: contents\">" +
                "                   <img class=\"course-img\" src=\"../../resource/image/teacher/live/" + data['live'][i]['photo'] + "\">" +
                "               </div>" +
                "               <div class=\"col-sm-4 course_name\">" +
                "                   <span>" + data['live'][i]['name'] + " </span>" +
                "               </div>" +
                "               <div class=\"col-sm-3\">" +
                "                   <span>" + data['live'][i]['type'] + " </span>" +
                "               </div>" +
                "               <div class=\"col-sm-2\">" +
                "                   <span>" + data['live'][i]['hours'] + "h</span>" +
                "               </div>" +
                "               <div class='tools'>" +
                "                   <a class=\"btn btn-info\" href=\"../../Teacher_sales/live/" + data['live'][i]['id'] + "\">詳細</a>" +
                "               </div>" +
                "          </div>";

            $('#live_course_list').append(content);
        }
    }

    //影片課程載入
    if (data['film'].length == 0) {
        var content = "<div class=\"cb-info d-block\">" +
            "               <span>目前尚無課程</span>" +
            "          </div>";
        $('#film_course_list').append(content);
    } else {
        console.log(data);
        for (i = 0; i < data['film'].length; i++) {
            var content = "<div class=\"cb-info row\">" +
                "               <div class=\"col-sm-2\" style=\"display: contents\">" +
                "                   <img class=\"course-img\" src=\"../../resource/image/teacher/film/" + data['film'][i]['photo'] + "\">" +
                "               </div>" +
                "               <div class=\"col-sm-4 course_name\">" +
                "                   <span>" + data['film'][i]['name'] + " </span>" +
                "               </div>" +
                "               <div class=\"col-sm-3\">" +
                "                   <span>" + data['film'][i]['type'] + " </span>" +
                "               </div>" +
                "               <div class=\"col-sm-2\">" +
                "                   <span>" + data['film'][i]['price'] + "元</span>" +
                "               </div>" +
                "               <div class='tools'>" +
                "                   <a class=\"btn btn-info\" href=\"../../film_courses/" + data['film'][i]['id'] + "\">詳細</a>" +
                "               </div>" +
                "          </div>";

            $('#film_course_list').append(content);
        }
    }
}


// 學生評價載入
function commentListBuild(data) {
    //直播課程學生評價
    if (data['live'].length == 0) {
        var content = "<div class=\"cb-info d-block\">" +
            "               <span>目前尚無評價</span>" +
            "          </div>";
        $('#live_comment_list').append(content);
    } else {
        for (i = 0; i < data['live'].length; i++) {
            var content = "<ul class=\"cb-info\">" +
                "              <li class='text-black'>課程名稱: " + data['live'][i]['name'] + "</li>" +
                "              <li>學生評分: " + data['live'][i]['score'] + "</li>" +
                "              <li>學生評語: " + data['live'][i]['comment'] + "</li>" +
                "          </ul>";

            $('#live_comment_list').append(content);
        }
    }

    //影片課程學生評價
    if (data['film'].length == 0) {
        var content = "<div class=\"cb-info d-block\">" +
            "               <span>目前尚無評價</span>" +
            "          </div>";
        $('#film_comment_list').append(content);
    } else {
        for (i = 0; i < data['film'].length; i++) {
            var content = "<ul class=\"cb-info\">" +
                "              <li>課程名稱: " + data['film'][i]['name'] + "</li>" +
                "              <li>學生評分: " + data['film'][i]['score'] + "</li>" +
                "              <li>學生評語: " + data['film'][i]['comment'] + "</li>" +
                "          </ul>";

            $('#film_comment_list').append(content);
        }
    }
}