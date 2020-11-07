<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>直播課程</title>
    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../resource/css/home.css">
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/student/course/teacher_sales.css"/>
    <link rel="stylesheet" href="../../resource/css/student/student.css">
    <link rel="stylesheet" href="../../resource/css/share.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--  CALENDAR  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.print.css"
          media="print">
    <link rel="stylesheet" href="../../resource/css/teacher/match_time.css"/>
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
    <div class="container teacher_content row">
        <!--麵包屑-->
        <nav class="breadcrumb col-sm-12">
            <ul>
                <li><a href="../../student" class="breadcrumb-link nuxt-link-active">首頁</a></li>
                <span>&nbsp;/&nbsp;</span>
                <li><span><?= $type ?></span></li>
                <span>&nbsp;/&nbsp;</span>
                <li><span><?= $actualMovie ?></span></li>
            </ul>
        </nav>

        <!--老師介紹區-->
        <div class="teacher_introduction col-sm-6">
            <div class="row">
                <a href="https://ajcode.tk/teaching_platform_dev/teacher_page/<?=$id2?>/1" style="display: contents;">
                <img class="sticker col-sm-5"
                     src="../../resource/image/student/photo/<?= $photo ?>" >
                </a>
                <div class="teacher_news col-sm-6">
                    <b><span class="name"><?= $actualMovie ?></span></b><br>
                    <a href="https://ajcode.tk/teaching_platform_dev/teacher_page/<?=$id2?>/1"><span class="course_introduction"><?= $name ?></span><br></a>
                    <span class="completion">? 堂英文課完成</span>
                </div>

            </div>
            <br>
            <hr>
            <br>
            <span class="subject">自我介紹</span>
            <div class="Introduction_Area">
                <b>會說語言:</b>&nbsp;&nbsp;<span><?= $speakLanguage ?></span>
                <br>
                <b>個人介紹:</b>
                <div class="Introduction">
                    <span style="word-wrap: break-word;"><?= $des ?></span>
                </div>
            </div>
            <hr>
            <br>
            <span class="subject">課程介紹</span>
            <div class="Introduction_Area">
                <div class="Introduction">
                    <span style="word-wrap: break-word;"><?= $introduction ?></span>
                </div>
            </div>

            <hr>
            <br>
            <span class="subject">課程簡介</span>
            <div class="question_Area">
                <div class="question">
                    <span style="word-wrap: break-word;"><?= $brief_introduction ?></span>
                </div>
            </div>
        </div>

        <!--課程購買區-->
        <div class="course_purchase col-sm-6">
            <div class="video-wrap">
                <iframe class="col-sm" height="200" src="https://www.youtube.com/embed/<?= $experienceFilm ?>"
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>

            <div class="btns">
                <button type="button" class="btn btn-danger col-sm-12" onclick="open_calendar()">查看老師時間排程</button>
                <button type="button" class="btn btn-outline-primary col-sm-12" onclick="<?= $contact_window ?>">聯繫老師
                </button>
                <button type="button" class="btn btn-outline-danger col-sm-12" onclick="askForReportUser()">檢舉老師
                </button>
                <button class="btn btn-outline-success col-sm-12" type="button"
                        onclick="experience_course('<?= $id ?>','<?= $id2 ?>')">體驗課程申請
                </button>
            </div>

            <div class="contact_teacher">
                <div class="row">
                    <i class="fa fa-heart col-sm-3" id="favorite" onclick="favorite('<?= $id ?>')"></i>
                    <i class="fa fa-facebook-square col-sm-3"></i>
                    <i class="fa fa-twitter col-sm-3"></i>
                    <i class="fa fa-envelope-o col-sm-3"></i>
                </div>
            </div>

<!--            <div class="course_offer_news">-->
<!--                <span>超過 5 堂課以上有額外的優惠，可在下方看到價格或是購物車選擇！</span>-->
<!--            </div>-->

            <div class="class_purchase">
                <h5>課堂數購買</h5>
                <?= $preferential_content ?>
            </div>

            <!--顯示更多優惠按鈕-->
            <div class="more_offers">
                <?= $more_offers_content ?>
            </div>
        </div>

        <div class="container message_discuss_area">
            <h2 class="title">留言區</h2>
            <div class="user_area" id="user_message_discuss_0">
                <img class="avatar"
                     src="../../<?= $messagePhoto ?>"
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
                <!-- 一整個留言者-->
                <!--                <div class="discuss_area row">-->
                <!--                    <div class="avatar_area">-->
                <!--                        <img class="avatar"-->
                <!--                             src="https://yt3.ggpht.com/a/AGF-l79FlOJvK-4YwhpMVh9NBJ7ayIGP1KapYumKgQ=s48-c-k-c0xffffffff-no-rj-mo"-->
                <!--                             alt="">-->
                <!--                    </div>-->
                <!---->
                <!--                    <div class="message_information_area col-sm-10">-->
                <!--                        <p id="commenter_1">Ivan Seow 3 個月前</p>-->
                <!--                        <p class="message_text" id="message_area_1">The best i ever heared</p>-->
                <!--                        <p class="score_area" id="score_area_1">-->
                <!--                            <!--                        <i class="fa fa-thumbs-o-up">1</i>-->
                <!--                            <!--                        <i class="fa fa-thumbs-o-down">1</i>-->
                <!--                            <span onclick="add_reply_area(1)">回覆</span>-->
                <!--                        </p>-->
                <!---->
                <!--                        <!--回覆區-->
                <!--                        <div class="user_area reply_input_area" id="user_message_discuss_1">-->
                <!--                            <img class="avatar"-->
                <!--                                 src="//lh3.googleusercontent.com/-1zNWKY5FhFo/AAAAAAAAAAI/AAAAAAAAAAA/ACHi3rfcD_SZP6F1STXsXIZl9Lgc8ehcKg/s88/photo.jpg"-->
                <!--                                 alt="">-->
                <!--                            <textarea rows="1" class="message_input_area public_reply_1" placeholder="輸入公開回覆..."></textarea>-->
                <!--                            <button type="button" class="btn btn-outline-primary message_btn">回覆</button>-->
                <!--                            <button type="button" class="btn btn-outline-primary cancel_message_btn"-->
                <!--                                    onclick="Cancel_reply(1)">取消-->
                <!--                            </button>-->
                <!--                        </div>-->
                <!---->
                <!--                        <p class="reply_area">-->
                <!--                        <p class="more_reply" id="more_reply_1" onclick="more_reply(1,1)">查看 1 則回覆</p>-->
                <!---->
                <!--                        <div class="more_message_discuss_area" id="more_message_discuss_area_1">-->
                <!--                            <!--一個回覆者-->
                <!--                            <div class="avatar_area">-->
                <!--                                <img class="avatar"-->
                <!--                                     src="https://yt3.ggpht.com/a/AGF-l7-AT7a8ljcpEuzPA2mUQaBrCHPoHZVN4ll7ng=s48-c-k-c0xffffffff-no-rj-mo"-->
                <!--                                     alt="">-->
                <!--                            </div>-->
                <!---->
                <!--                            <div class="message_information_area col-sm-10">-->
                <!--                                <p id="more_commenter_1">teas 3 個月前</p>-->
                <!--                                <p class="message_text" id="more_message_area_1">cool!</p>-->
                <!--                                <p class="score_area" id="more_score_area_1">-->
                <!--                                    <!--                                <i class="fa fa-thumbs-o-up">1</i>-->
                <!--                                    <!--                                <i class="fa fa-thumbs-o-down">2</i>-->
                <!--                                    <!--                                <span>回覆</span>-->
                <!--                                </p>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                        </p>-->
                <!--                    </div>-->
                <!--                </div>-->

            </div>

        </div>
        <!--        更多資料載入圖示區-->
        <div class="container more_loading_icon"><p>載入更多留言中請稍後...</p></div>
    </div>
</main>

<footer>
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
    <div class="py-4 bg-dark text-white-50">
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
    </div>
    <!-- 頁尾 end-->
</footer>

<!-- Scripts -->
<!--匯入套件-->
<script src="../../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../../resource/package/js/bootstrap.js"></script>
<!--  CALENDAR  -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.js"></script>


<!--  匯入所需JS  -->
<script src="../../resource/js/home/account.js"></script>
<script src="../../resource/js/student/student.js"></script>
<script src="../../resource/js/share.js"></script>
<script src="../../resource/js/student/teacher_sales.js"></script>

<!--  初始載入  -->
<script>
    var Events = <?php echo json_encode($Events)?>;
    var live_id = <?php echo json_encode($id)?>;
    var user_photo = <?php echo json_encode($messagePhoto) ?>;
    var user_name = <?php echo json_encode($messageName)?>;
    var identity = <?php echo json_encode($identity)?>;
    var isLogin = "<?php echo empty($isLogin) ? false : $isLogin ?>";
    var teacherName = "<?php echo $name ?>";
    var teacherPhoto = "<?php echo $photo ?>";
    var favorite_status = "<?php echo $favorite ?>";
</script>


</body>
</html>