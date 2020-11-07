<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>任務頁面</title>

    <!--  Package start  -->
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/package/css/flag-icon.css"/>

    <!--  Package end  -->

    <!--  View start  -->
    <link rel="stylesheet" href="resource/css/share.css">
    <link rel="stylesheet" href="resource/css/home.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/student/daily_tasks.css">
    <!--  View end  -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light header-shadow">
        <a class="navbar-brand header-logo-block" href="student"><img class="header-logo"
                                                                      src="resource/pics/share/logo.png"></a>
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

<div class="daily_tasks__container container">
    <h3 class="title">每日任務</h3>

    <!--一個任務欄-->
    <div class="mission_board row">
        <span id="status_1" style="display: none"><?= $task1 ?></span>
        <span class="mission_board_title col-sm-12">任務1</span>
        <div class="task_area">
            <span class="task_text">登入簽到</span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" id="Progress_1" aria-valuemin="0" aria-valuemax="1"></div>
            </div>
            <span class="schedule_text" id="schedule_text_1">0/1</span>
        </div>

        <div class="reward_area">
            <div>
                <i class="fa fa-usd gold_style"></i>
                <span>X <span id="reward_value_1">100</span> </span>
            </div>

            <button type="button" class="btn btn-warning" id="task_btn_1" onclick="task_fun('簽到',1)">簽到</button>
        </div>
    </div>

    <h3 class="title">任務一欄</h3>
    <!--一個任務欄-->
    <div class="mission_board row">
        <span id="status_2" style="display: none"><?= $task2 ?></span>
        <span class="mission_board_title col-sm-12">任務1</span>
        <div class="task_area">
            <span class="task_text">FB社群分享</span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" id="Progress_2" aria-valuemin="0" aria-valuemax="1"></div>
            </div>
            <span class="schedule_text" id="schedule_text_2">0/1</span>
        </div>

        <div class="reward_area">
            <div>
                <i class="fa fa-usd gold_style"></i>
                <span>X <span id="reward_value_2">200</span> </span>
            </div>

            <button type="button" class="btn btn-warning" id="task_btn_2" onclick="task_fun('FB分享',2)">分享</button>
        </div>
    </div>

    <!--一個任務欄-->
    <div class="mission_board row">
        <span id="status_3" style="display: none"><?= $task3 ?></span>
        <span class="mission_board_title col-sm-12">任務2</span>
        <div class="task_area">
            <span class="task_text">LINE社群分享</span>
            <div class="progress">
                <div class="progress-bar" role="progressbar" id="Progress_3" aria-valuemin="0" aria-valuemax="1"></div>
            </div>
            <span class="schedule_text" id="schedule_text_3">0/1</span>
        </div>

        <div class="reward_area">
            <div>
                <i class="fa fa-usd gold_style"></i>
                <span>X <span id="reward_value_3">200</span> </span>
            </div>

            <button type="button" class="btn btn-warning" id="task_btn_3" onclick="task_fun('LINE分享',3)">分享</button>
        </div>
    </div>
</div>
<!-- Scripts -->
<!-- Package -->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>

<!-- View -->
<script src="resource/js/share.js"></script>
<script src="resource/js/student/daily_tasks.js"></script>

<script>
    var task1 = <?php echo json_encode($task1)?>; //簽到
    var task2 = <?php echo json_encode($task2)?>; //FB分享
    var task3 = <?php echo json_encode($task3)?>; //IG分享
</script>


</body>
</html>