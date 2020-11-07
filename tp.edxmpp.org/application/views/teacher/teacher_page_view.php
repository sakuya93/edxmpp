<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title><?= $teacherData->name ?> 的老師簡介</title>
    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../resource/package/css/flag-icon.css"/>
    <link rel="stylesheet" href="../../resource/css/share.css">
    <link rel="stylesheet" href="../../resource/css/home.css"/>
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/student/student.css">
    <link rel="stylesheet" href="../../resource/css/student/dashboard.css">
    <link rel="stylesheet" href="../../resource/css/teacher/teacher_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
</head>
<body class="bg-attachment">

<header>
    <nav class="navbar navbar-expand-lg navbar-light header-shadow">
        <a class="navbar-brand header-logo-block" href="../../student"><img class="header-logo"
                                                                            src="../../resource/pics/share/logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <?= $headerRightBar ?>
        </div>
    </nav>

    <!--    導覽列右側MENU    -->
    <?= $headerRightIconMenu ?>

    <!--        右側帳號資訊欄         -->
    <?= $RightInformationColumn ?>


    <div class="message-box menu" id="page-2"> <!-- 聊天 -->
        <div class="top-bar col-sm">
            <b id="chat_object">傳送對象姓名</b>
            <i class="fa fa-close" id="message-box-close"></i>
        </div>

        <div class="message_area">
        </div>

        <div class="send-message">
            <textarea class="input_message" id="input_message" rows="3" cols="32" placeholder="輸入訊息..."></textarea>
        </div>
    </div>

</header>

<section class="py-5 main container-fluid col-sm-11">
    <div class="row">
        <!--    個人資訊     -->
        <div class="box BD-card col-lg-3">
            <div class="cb-header row d-block">
                <span>個人資訊</span>
            </div>
            <img class="BD-card-img" src="../../resource/image/student/photo/<?= $teacherData->photo ?>"
                 alt="Card image">
            <span class="card-body">
            <ul id="user_info_detail"></ul>
            </span>
        </div>

        <!--    課程瀏覽     -->
        <div class="cb col-lg-6">
            <div class="box col-sm-12" id="live_course_list">
                <div class="cb-header">直播課程瀏覽</div>
            </div>
            <div class="box col-sm-12" id="film_course_list">
                <div class="cb-header">影片課程瀏覽</div>
            </div>
        </div>

        <!--    學生評價    -->
        <div class="comment cb col-lg-3">
            <div class="box col-sm-12" id="live_comment_list">
                <div class="cb-header">直播課程學生評語</div>
            </div>
            <div class="box col-sm-12" id="film_comment_list">
                <div class="cb-header">影片課程學生評語</div>
            </div>
        </div>

    </div>
</section>


<!-- Scripts -->
<!--匯入套件-->
<script src="../../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../../resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!--匯入所需JS-->
<script src="../../resource/js/student/student.js"></script>
<script src="../../resource/js/teacher/teacher_page.js"></script>
<script src="../../resource/js/share.js"></script>

<script>
    //載入老師資料
    var teacherData = <?=json_encode($teacherData)?>;
    userInfoDetailBuild(teacherData);
    // console.log(teacherData);

    //載入老師所開課程
    var courseData =<?=json_encode($courseData)?>;
    courseListBuild(courseData);
    // console.log(courseData);

    //載入學生評價
    var courseEvaluation =<?=json_encode($courseEvaluation)?>;
    commentListBuild(courseEvaluation);
    // console.log(courseEvaluation);
</script>

</body>
</html>