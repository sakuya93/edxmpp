<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>我的課程評價</title>
    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../resource/css/teacher/my_course_evaluation.css"/>
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/home.css"/>
    <link rel="stylesheet" href="../../resource/css/share.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
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


<main>
    <div class="my_course_evaluation-container">
        <!--    明細after ver    -->
        <div>
            <div class="dropdown select_course_btn">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    選取課程
                </button>
                <div class="dropdown-menu">
                    <!--                    <a class="dropdown-item" href="#">課程1</a>-->
                    <!--                    <a class="dropdown-item" href="#">課程2</a>-->
                    <!--                    <a class="dropdown-item" href="#">課程3</a>-->
                    <?= $course_option ?>
                </div>
            </div>

            <div class="row justify-content-center course_header_block">
                <!-- 欄位 -->
                <div class="col-md-auto course_header_title"><?= $header_title ?></div>
            </div>
            <?= $content ?>
        </div>
</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="../../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../../resource/package/js/bootstrap.js"></script>

<!--匯入所需JS-->
<script src="../../resource/js/share.js"></script>
<script src="../../resource/js/teacher/my_course_evaluation.js"></script>

</body>
</html>