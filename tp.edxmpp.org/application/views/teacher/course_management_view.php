<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>課程管理頁面</title>

    <!-- FullCalendar v3.8.1 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.print.css" rel="stylesheet"
          media="print">

    <!--  Bootstrap 4  -->
    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>

    <!--  Bootstrap table  -->
    <link href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css" rel="stylesheet">

    <!--  View  -->
    <link rel="stylesheet" href="../../resource/css/student/student.css">
    <link rel="stylesheet" href="../../resource/css/teacher/course_management.css"/>
    <link rel="stylesheet" href="../../resource/css/teacher/match_time.css"/>
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/home.css"/>
    <link rel="stylesheet" href="../../resource/css/share.css">

    <!-- FontAwesome v4.7.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<!--loading視窗載入-->
<div class='loading'>
    <span id="loading_text"></span>
</div>
<!--loading視窗結束-->

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
    <div class="course_management-container">
        <div class="TA-center">
            <h4 class="title"><b><?= $page_title ?></b></h4>
        </div>
        <div class="row col-sm" id="btn_area">
            <div class="dropdown ml-10">
                <button class="btn btn-danger dropdown-toggle fa fa-file-o mt-10 tools-btn-mobile" type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    選擇類型
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" id="type_live_course">直播</a>
                    <a class="dropdown-item" id="type_film_course">影片</a>
                    <a class="dropdown-item" id="type_fundraising_course">募資</a>
                </div>
            </div>

            <div class="dropdown ml-10">
                <button class="btn btn-primary dropdown-toggle fa fa-plus mt-10 tools-btn-mobile" type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    新增課程
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="../../live_courses">直播課程</a>
                    <a class="dropdown-item" href="../../add_film_course">影片課程</a>
                    <a class="dropdown-item" href="../../add_fundraising_course">募資課程</a>
                </div>
            </div>

            <?= $release_button ?>

            <div class="ml-10">
                <?= $my_evaluation_button ?>
            </div>

            <div class="ml-10">
                <?= $give_evaluation_button ?>
            </div>

            <div class="ml-10">
                <?= $application_experience_button ?>
            </div>
        </div>

        <!--商品-->
        <div class="title_group row" id="list_field">
            <?= $field ?>
        </div>
        <div class="bd-2-s-gray commodity">
            <?= $content ?>
        </div>
    </div>

</main>

<section id="studentList__section">
    <!-- 上課名單Modal -->
    <div class="modal fade" id="studentList__window" tabindex="-1" role="dialog" aria-labelledby="studentList__window"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentList__label">上課名單</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="studentList_body">
                    <label class="ft-18">教室創建狀態: 尚未創建</label>
                    <section>
                        <div class="select">
                            <select class="form-control" id="locale">
                                <option value="af-ZA">af-ZA</option>
                                <option value="ar-SA">ar-SA</option>
                                <option value="ca-ES">ca-ES</option>
                                <option value="cs-CZ">cs-CZ</option>
                                <option value="da-DK">da-DK</option>
                                <option value="de-DE">de-DE</option>
                                <option value="el-GR">el-GR</option>
                                <option value="en-US">en-US</option>
                                <option value="es-AR">es-AR</option>
                                <option value="es-CL">es-CL</option>
                                <option value="es-CR">es-CR</option>
                                <option value="es-ES">es-ES</option>
                                <option value="es-MX">es-MX</option>
                                <option value="es-NI">es-NI</option>
                                <option value="es-SP">es-SP</option>
                                <option value="et-EE">et-EE</option>
                                <option value="eu-EU">eu-EU</option>
                                <option value="fa-IR">fa-IR</option>
                                <option value="fi-FI">fi-FI</option>
                                <option value="fr-BE">fr-BE</option>
                                <option value="fr-FR">fr-FR</option>
                                <option value="he-IL">he-IL</option>
                                <option value="hr-HR">hr-HR</option>
                                <option value="hu-HU">hu-HU</option>
                                <option value="id-ID">id-ID</option>
                                <option value="it-IT">it-IT</option>
                                <option value="ja-JP">ja-JP</option>
                                <option value="ka-GE">ka-GE</option>
                                <option value="ko-KR">ko-KR</option>
                                <option value="ms-MY">ms-MY</option>
                                <option value="nb-NO">nb-NO</option>
                                <option value="nl-NL">nl-NL</option>
                                <option value="pl-PL">pl-PL</option>
                                <option value="pt-BR">pt-BR</option>
                                <option value="pt-PT">pt-PT</option>
                                <option value="ro-RO">ro-RO</option>
                                <option value="ru-RU">ru-RU</option>
                                <option value="sk-SK">sk-SK</option>
                                <option value="sv-SE">sv-SE</option>
                                <option value="th-TH">th-TH</option>
                                <option value="tr-TR">tr-TR</option>
                                <option value="uk-UA">uk-UA</option>
                                <option value="ur-PK">ur-PK</option>
                                <option value="uz-Latn-UZ">uz-Latn-UZ</option>
                                <option value="vi-VN">vi-VN</option>
                                <option value="zh-CN">zh-CN</option>
                                <option value="zh-TW" selected>zh-TW</option>
                            </select>
                        </div>

                        <div id="toolbar">
                            <button class="btn btn-primary" id="SignIn" onclick="signIn()">登入MS帳號</button>
                            <button class="btn btn-danger" id="btn_createClassRoom" onclick="">創建Teams上課教室</button>
                        </div>

                        <table
                                id="table"
                                data-toolbar="#toolbar"
                                data-search="true"
                                data-show-refresh="true"
                                data-show-toggle="true"
                                data-show-fullscreen="true"
                                data-show-columns="true"
                                data-detail-view="false"
                                data-show-export="true"
                                data-click-to-select="true"
                                data-detail-formatter="detailFormatter"
                                data-minimum-count-columns="2"
                                data-show-pagination-switch="true"
                                data-pagination="true"
                                data-id-field="id"
                                data-page-size="10"
                                data-page-list="[10, 25, 50, 100, all]"
                                data-show-footer="false"
                                data-response-handler="responseHandler">
                        </table>

                    </section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- Scripts -->
<!--匯入套件-->
<script src="../../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../../resource/package/js/bootstrap.js"></script>

<!-- Moment.js v2.20.0 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.0/moment.min.js"></script>

<!-- FullCalendar v3.8.1 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.1/fullcalendar.min.js"></script>


<!--  Bootstrap table  -->
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>

<!--  編輯器  -->
<script src="https://cdn.ckeditor.com/4.13.1/full/ckeditor.js"></script>


<!-- MSAL JS-->
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@microsoft/microsoft-graph-client/lib/graph-js-sdk.js"></script>
<!-- polyfilling promise -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/es6-promise/dist/es6-promise.auto.min.js"></script>
<!-- polyfilling fetch -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/whatwg-fetch/dist/fetch.umd.min.js"></script>
<!-- depending on your browser you might wanna include babel polyfill -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@babel/polyfill@7.4.4/dist/polyfill.min.js"></script>
<script type="text/javascript" src="https://alcdn.msftauth.net/lib/1.2.0/js/msal.js"></script>
<!-- MSAL JS-->

<!-- 匯入所需JS -->
<script src="../../resource/js/teacher/course_management.js"></script>
<script src="../../resource/js/share.js"></script>
</body>
</html>