<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>直播課程價格管理</title>
    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../resource/css/share.css">
    <link rel="stylesheet" href="../../resource/css/home.css"/>
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/student/student.css">
    <link rel="stylesheet" href="../../resource/css/teacher/court_discount.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand header-logo-block" href="../../student"><img class="header-logo" src="../../resource/pics/share/logo.png"></a>
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
    <div class="container mbr-3 court_discount_body" id="setLiveSubjectForm">
        <div class="input-title col-sm-12 ft-24">直播課程價格設定</div>

        <div class="mls-15">
            <form id="court_discount_form"
                  onsubmit="court_discount_sendOut('<?= $id ?>',document.getElementsByClassName('court_discount-data'), '<?=$classMode?>'); return false;">
                <div id="court_discount_body">
                    <!--         新增堂數價格資料欄位           -->
                </div>
                <div class="col-sm-6" id="addButton">
                    <i class="fa fa-plus btn btn-info mb-3" type="button" onclick="add_court_discount()">新增一筆優惠價格資料</i>
                </div>
                <div class="col-sm-6">
                    <input class="btn btn-primary mb-3" type="submit" value="儲存">
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="../../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../../resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!--匯入所需JS-->
<script src="../../resource/js/student/student.js"></script>
<script src="../../resource/js/teacher/court_discount.js"></script>
<script src="../../resource/js/share.js"></script>

<script>
    $(document).ready(function () {
        $("#court_discount_form").submit(function (event) {
            event.preventDefault();
        });
    });
    load_court_discount('<?=json_encode($data)?>', '<?=$classMode?>');
</script>

</body>
</html>