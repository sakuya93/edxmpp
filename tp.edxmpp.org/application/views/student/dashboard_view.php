<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>儀錶板</title>
    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../resource/css/share.css">
    <link rel="stylesheet" href="../../resource/css/home.css"/>
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/student/student.css">
    <link rel="stylesheet" href="../../resource/css/student/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.print.css"
          media="print">
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


<section class="py-5 main container-fluid col-sm-10">
    <div class="row">
        <!--    個人資訊     -->
        <div class="box BD-card col-lg-3">
            <div class="cb-header row d-block">
                <span>個人資訊</span>
            </div>
            <img class="BD-card-img" src="../../resource/image/student/photo/<?= $memberData[0]->photo ?>"
                 alt="Card image">
            <span class="card-body" >
                <ul id="userInfo">
                    <li><b class="username"><?= $memberData[0]->name ?></b></li>
                    <li>註冊時間: <?= $memberData[0]->registeredDate ?></li>
                    <li>身分: <?= $memberData[0]->identity ?></li>
                    <li>會說語言: <?= $memberData[0]->speakLanguage ?></li>
                    <li>國籍: <?= $memberData[0]->country ?></li>
                </ul>
                <ul>
                <li><a class="btn btn-outline-info my-2" id="teacher_page"
                       href="../../teacher_page/<?= isset($_SESSION['Tid']) ? $_SESSION['Tid'] : "#" ?>/1">老師頁面</a></li>
<!--                <li><a class="btn btn-outline-danger" id="teacher_page" onclick="askForReportUser()">檢舉此人</a></li>-->
                </ul>
            </span>

            <!--近一個月的活動紀錄-->
            <!--            <div class="activity_record">-->
            <!--                <hr>-->
            <!--                <h2 class="activity_record_title">本月活動紀錄</h2>-->
            <!--                <div id="calendar" class="calendar_body"></div>-->
            <!--            </div>-->

        </div>

        <!--    直播課程評語     -->
        <div class="cb col-lg-5 ml-2" id="comment">
        </div>

        <!--    擁有的影片課程    -->
        <div class="cb col-lg-4 ml-2">
            <div class="box col-sm-12" id="film_course">
                <div class="cb-header">擁有的影片課程</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.js"></script>

<!--匯入所需JS-->
<script src="../../resource/js/student/student.js"></script>
<script src="../../resource/js/student/dashboard.js"></script>
<script src="../../resource/js/share.js"></script>

<script>
    var classData =<?=json_encode($classData)?>;
    commentBuild(classData);
    var filmData =<?=json_encode($filmData)?>;
    filmBuild(filmData);
    var userName = "<?= $memberData[0]->name?>" ;
    var isLogin = <?= $isLogin ?>
</script>
</body>
</html>
