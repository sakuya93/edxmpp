<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>學習歷程</title>
    <link rel="stylesheet" href="../resource/package/css/bootstrap.css"/>
    <link href="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../resource/css/share.css">
    <link rel="stylesheet" href="../resource/css/home.css"/>
    <link rel="stylesheet" href="../resource/css/window.css">
    <link rel="stylesheet" href="../resource/css/student/my_learn_process.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
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

<main>
    <div class="container mlp-block">
        <div class="title"></div>

        <div class="row flex-xl-nowrap">

            <!--    TABLE    -->
            <main class="col-sm-12" style="margin: 0 auto;">
                <div id="toolbar">

                    <div class="select mt-10 row col-sm-12 flex-nowrap">
                        <h3 class="col-sm-6">Language:</h3>
                        <select class="form-control col-sm-6" id="locale">
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

                    <div class="dropdown tool-btn">
                        <button class="btn btn-info dropdown-toggle fa fa-file-o tools-btn-mobile" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            選擇類型
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" id="type_live_course" onclick="window.location='type_live_course'">直播</a>
                            <a class="dropdown-item" id="type_film_course" onclick="window.location='type_film_course'">影片</a>
                        </div>
                    </div>

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
                        data-url="../my_learn_process-getComment"
                        data-response-handler="responseHandler">
                </table>
            </main>
        </div>
    </div>
</main>

<!----------------------------------------------------- Scripts --------------------------------------------------->
<!--匯入套件-->
<script src="../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!--  BOOTSTRAP TABLE  -->
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/extensions/export/bootstrap-table-export.min.js"></script>

<!--匯入所需JS-->
<script src="../resource/js/student/student.js"></script>
<script src="../resource/js/share.js"></script>
<script src="../resource/js/student/my_learn_process.js"></script>

<script>
    // 工作經驗載入
    chooseField(<?=json_encode($type)?>);
</script>

</body>
</html>