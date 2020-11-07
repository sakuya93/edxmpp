<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>我購買的課程</title>
    <link rel="stylesheet" href="../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../resource/css/student/student.css">
    <link rel="stylesheet" href="../resource/css/student/my_course.css"/>
    <link rel="stylesheet" href="../resource/css/home.css">
    <link rel="stylesheet" href="../resource/css/window.css">
    <link rel="stylesheet" href="../resource/css/share.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--  CALENDAR  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.print.css"
          media="print">
    <link rel="stylesheet" href="../resource/css/teacher/match_time.css"/>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand header-logo-block" href="../student"><img class="header-logo" src="../resource/pics/share/logo.png"></a>
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

<main>
    <div class="course_management-container">
        <h4 class="title"><b>我購買的課程</b></h4>

        <!--商品-->
        <div class="row buy_record_row mb-10">
            <div class="col-sm-4 TA-center">
                <p class="title_text col-sm-12">課程資訊</p>
            </div>
            <div class="row col-sm-8">
                <p class="title_text col-sm-3">形式</p>
                <p class="title_text col-sm-3">類別</p>
                <p class="title_text col-sm-4">時/堂數</p>
                <p class="title_text col-sm-2">工具</p>
            </div>
        </div>

        <div class="bd-2-s-gray commodity">
            <?= $content ?>
        </div>

        <!--   頁面轉換    -->
        <div class="col-sm-12 page-block p-2">
            <?= $pageContent ?>
        </div>

    </div>


</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../resource/package/js/bootstrap.js"></script>

<!--  CALENDAR  -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.js"></script>

<!--  匯入所需JS  -->
<script src="../resource/js/share.js"></script>
<script src="../resource/js/student/student.js"></script>
<script src="../resource/js/student/my_courses.js"></script>

<!--  初始載入  -->
<script>
    var temp_Events = <?php echo json_encode($Events)?>;
    
    var temp_noEvents = <?php echo json_encode($noEvents)?>; //抓沒有事件的匹配時間


</script>


</body>
</html>