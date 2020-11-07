<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        var element = new Image();
        Object.defineProperty(element, 'id', {
            get: function () {
                window.location = "https://ajcode.tk/teaching_platform_dev/student";
            }
        });

        // 每一秒檢測開發者工具是否打開
        setInterval(function () {
            console.log(element);
            // console.debug(element);
        }, 1000)
    </script>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>募資課程</title>
    <link rel="stylesheet" href="../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../resource/css/share.css">
    <link rel="stylesheet" href="../resource/css/home.css"/>
    <link rel="stylesheet" href="../resource/css/window.css">
    <link rel="stylesheet" href="../resource/css/student/student.css">
    <link rel="stylesheet" href="../resource/css/student/course/fundraising_course.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/hot-sneaks/jquery-ui.css"/>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand header-logo-block" href="../student"><img class="header-logo"
                                                                         src="../resource/pics/share/logo.png"></a>
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

<top class="container">
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="inner_text row">
                <div class="col-sm-2"></div>
                <div class="col-sm-12 internal">
                    <nav class="breadcrumb">
                        <ul>
                            <li><a href="#" class="breadcrumb-link nuxt-link-active">所有課程</a></li>
                            <span>&nbsp;>&nbsp;</span>
                            <li><a href="#" class="breadcrumb-link nuxt-link-active" id="course_type">募資課程</a></li>
                            <span>&nbsp;>&nbsp;</span>
                            <li><a href="#" class="breadcrumb-link nuxt-link-active"
                                   id="course_name"><?= $fundraisingCourse[0]->courseName ?></a></li>
                        </ul>
                    </nav>

                    <div class="title">
                        <span id="film_name"><?= $fundraisingCourse[0]->courseName ?>
                            (<?= $fundraisingCourse[0]->type == 0 ? '直播課程' : '影片課程' ?>)</span>
                    </div>

                    <div id="data_area" style="display: none">
                        <span id="t_id"><?= $fundraisingCourse[0]->id1 ?></span>
                        <span id="fc_id"><?= $fundraisingCourse[0]->id2 ?></span>
                    </div>

                    <video class="course_video" controls="" id="video_player" width="650" height="420"
                           oncontextmenu="return false"
                           controlslist="nodownload">
                        <source src="" id="video_player_src" type="video/mp4">
                    </video>

                    <div class="course_bar">
                        <div class="fundraising_course_information">
                            <span id="fundraising_course_information_text">
                                <?php
                                if ($fundraisingCourse[0]->status == 0) echo '未開始募資';
                                else if ($fundraisingCourse[0]->status == 1) echo '募資進行中';
                                else if ($fundraisingCourse[0]->status == 2) echo '募資成功';
                                else if ($fundraisingCourse[0]->status == 3) echo '募資失敗';
                                ?>
                            </span>
                        </div>

                        <div class="fundraising_course_time">
                            <p>募資倒數時間</p>
                            <p id="remaining_time">
                                <span id="day"></span>
                                <span id="hour"></span>
                                <span id="minute"></span>
                                <span id="second"></span>
                            </p>
                        </div>

                        <div class="price_area row">
                            <div class="market_price col-sm-6">
                                <p>正式價格</p>
                                <span><del><?= $fundraisingCourse['c'] ?>
                                        $<?= $fundraisingCourse[0]->normal_price ?></del></span>
                            </div>
                            <div class="fundraising_price col-sm-6">
                                <p>募資價格</p>
                                <span><?= $fundraisingCourse['c'] ?>
                                    $<?= $fundraisingCourse[0]->fundraising_price ?></span>
                                <p>鑽石價格</p>
                                <span><?= $fundraisingCourse['point'] ?></span>
                            </div>
                        </div>

                        <div class="btn_area row">
                            <?php
                            if ($checkInterest == 0) {
                                echo "<button type=\"button\" class=\"btn btn-success interested_btn col-sm-10\"
                                          onclick=\"interested()\">我有興趣
                                          </button>";
                            } else if ($checkInterest == 1) {
                                echo "<button type=\"button\" class=\"btn btn-danger interested_btn not_interested_btn col-sm-10\">
                                          已參加
                                          </button>";
                            } else if ($checkInterest == 2) {
                                echo "<button type=\"button\" class=\"btn btn-danger interested_btn not_interested_btn col-sm-10\">
                                          我的課程
                                          </button>";
                            } else if ($checkInterest == 3) {
                                echo "<button type=\"button\" class=\"btn btn-danger interested_btn not_interested_btn col-sm-10\">
                                          未登入
                                          </button>";
                            }
                            ?>
                        </div>

                        <div class="fundraising_goals">
                            <span class="currently">目前<?= $fundraisingCourse[0]->expectedNumber - $fundraisingCourse[0]->remainingNumber ?>
                                人</span>
                            <span class="goals">目標<?= $fundraisingCourse[0]->expectedNumber ?>人</span>
                            <div id="progress_bar"></div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-info contact_teacher_btn" onclick="<?= $contact_window ?>">聯繫老師
                </button>
            </div>
        </div>
    </section>
</top>

<main>

    <div class="course_information container">
        <!--    老師資訊-->

        <div class="teacher_information row justify-content-between">
            <div class="col-sm-6 user_info">
                <div class="col-sm-12">
                    <p class="information_title">老師資訊</p>
                </div>
                <div class="col-sm-12 d-flex mt-3">
                    <div class="col-sm-4">
                        <a href="https://ajcode.tk/teaching_platform_dev/teacher_page/<?= $fundraisingCourse[0]->id1 ?>/1">
                            <img class="card-img"
                                 src="../resource/image/student/photo/<?= $fundraisingCourse[0]->image ?>">
                        </a>
                    </div>
                    <div class="col-sm-8">
                        <p class="teacher_name">
                            <a href="https://ajcode.tk/teaching_platform_dev/teacher_page/<?= $fundraisingCourse[0]->id1 ?>/1">
                                <b><?= isset($fundraisingCourse[0]->teacherName) ? $fundraisingCourse[0]->teacherName : "無資料" ?></b>
                            </a>
                        </p>
                        <p><b>會說語言: </b><?= isset($fundraisingCourse[0]->speakLanguage) ? $fundraisingCourse[0]->speakLanguage : "無資料" ?></p>
                        <p><b>國家: </b><?= isset($fundraisingCourse[0]->country) ? $fundraisingCourse[0]->country : "無資料" ?></p>
                        <p><b>年齡: </b><?= isset($fundraisingCourse[0]->age) ? $fundraisingCourse[0]->age : "無資料" ?></p>
                        <p><b>性別: </b><?= isset($fundraisingCourse[0]->sex) ? $fundraisingCourse[0]->sex : "無資料" ?></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 course_info">
                <div class="col-sm-12">
                    <p class="information_title">課程資訊</p>
                </div>
                <div class="teacher_Introduction col-sm-12 mt-3">
                    <?= isset($fundraisingCourse[0]->introduction) ? $fundraisingCourse[0]->introduction : "無資料" ?>
                </div>
            </div>
        </div>

    </div>

    <div class="container message_discuss_area">
        <h2 class="title">留言區</h2>
        <div class="user_area" id="user_message_discuss_0">
            <img class="avatar"
                 src="../<?= $messagePhoto ?>"
                 alt="">
            <textarea rows="1" class="message_input_area" id="message_input_area_0"
                      placeholder="輸入公開留言..."></textarea>
            <button type="button" class="btn btn-outline-primary message_btn" id="message_btn_0">留言</button>
            <button type="button" class="btn btn-outline-primary cancel_message_btn"
                    onclick="Cancel_message(0)">取消
            </button>
        </div>

        <div id="new_message_area">

        </div>

        <div id="old_message_area">

        </div>

    </div>
    <!--        更多資料載入圖示區-->
    <div class="container more_loading_icon"><p>載入更多留言中請稍後...</p></div>

    <!-- 頁尾 start-->
    <div class="max-width-800 about" data-v-4e5639f4="">
        <div class="row">
            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">XXX教學平台</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">關於我們</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">部落格</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">媒體</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">工作機會</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">加入 XXX教學平台</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">成為教師</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">找學生</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">學習</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">找教師</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">找課程</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">學生評價</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">客戶支援</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class="">常見問題</a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4="">系統更新</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <footer class="py-4 bg-dark text-white-50">
        <div class="container text-center">
            <div class="footer_left">
                <small>© XXX, Inc.</small>
            </div>

            <div class="footer_right">
                <a href="#">網站地圖</a>・<a href="#">服務條款隱私聲明</a>&nbsp;&nbsp;&nbsp;
                <a href="#"><i class="fa fa-facebook-square"></i>&nbsp;</a>
                <a href="#"><i class="fa fa-instagram"></i>&nbsp;</a>
                <a href="#"><i class="fa fa-twitter"></i>&nbsp;</a>
            </div>

        </div>
    </footer>
    <!-- 頁尾 end-->
</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<!--匯入所需JS-->
<script src="../resource/js/home/account.js"></script>
<script src="../resource/js/student/student.js"></script>
<script src="../resource/js/student/fundraising_course.js"></script>
<script src="../resource/js/share.js"></script>

<script>
    //購買狀態
    var fundraisingCourse = <?=json_encode($fundraisingCourse)?>;

    $(document).ready(function () {

        //監聽
        setInterval(function () {
            if ($('#video_player').attr("controlslist") != "nodownload") {  //防止使用者手動修改下載參數
                $('#video_player').attr("controlslist", "nodownload");
            }

            if ($('#video_player_src').attr("src") != "...") {  //隱藏網址
                $('#video_player_src').attr("src", "...");
            }
        }, 100);

        // 影片課程初始載入
        load_FilmCourse(fundraisingCourse[0]['film_url']);
    });

    var fc_id = <?php echo json_encode($id)?>;
    var user_photo = <?php echo json_encode($messagePhoto) ?>;
    var user_name = <?php echo json_encode($messageName)?>;
    var identity = <?php echo json_encode($identity)?>;
</script>

</body>
</html>