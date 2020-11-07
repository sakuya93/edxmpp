<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>後台管理-貸幣轉換管理</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link href="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="resource/css/share.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/admin/admin.css">
    <link rel="stylesheet" href="resource/css/admin/currency_conversion.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link href="https://cdn.bootcss.com/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css"
          rel="stylesheet">
</head>
<body>


<div class="container-fluid">
    <div class="row flex-xl-nowrap">
        <!--    SIDEBAR    -->
        <div class="col-12 col-md-3 col-xl-2 bd-sidebar">
            <div class="list-group">
                <button class="btn btn-secondary" onclick="$('.bd-sidebar').toggle()">
                    <i class="fa fa-list mr-1"></i>
                    側邊攔
                </button>
                <?= $sideBarContent?>
            </div>
        </div>

        <!--    TABLE    -->
        <main class="col-12 col-md-10 col-xl-10 py-md-3 pl-md-5 bd-content">
            <div class="select mt-10 row col-sm-12 flex-nowrap">
                <button class="btn btn-info" onclick="$('.bd-sidebar').toggle()">
                    <i class="fa fa-list mr-1"></i>
                    側邊攔
                </button>
            </div>
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

                <div class="select mt-10 row col-sm-12 flex-nowrap" style="width: 480px">
                    <h3 class="col-sm-6">通知對象:</h3>
                    <select class="form-control col-sm-6" id="sel_notice_object" onchange="sel_notice_object()">
                        <option value="0" selected>全體</option>
                        <option value="1">學生</option>
                        <option value="2">老師</option>
                        <option value="3">特定會員</option>
                        <option value="4">特定老師</option>
                        <option value="5">特定影片課程</option>
                        <option value="6">特定直播課程</option>
                    </select>
                </div>

                <div class="btn_area">
                    <span id="auto_updateTitle">自動更新: </span>
                    <input type='checkbox' <?= $startStatus ?> class="auto_update_status" id="auto_update_status">

                    <span id="manual_updateTitle">手動更新: </span>
                    <button type="button" class="btn btn-primary" onclick="update_exchange_rate()">更新匯率</button>

                    <span id="last_update_timeTitle">最後更新時間: </span>
                    <span id="last_update_timeText"><?= @$date ?></span>

                    <span class="hint_text">開啟自動更新後請重新開啟整個瀏覽器</span>
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
                    data-page-size="50"
                    data-page-list="[50, 100, all]"
                    data-show-footer="false"
                    data-url="currency_conversion/getCurrencyConversion"
                    data-response-handler="responseHandler">
            </table>
        </main>
    </div>
</div>

<!-- Scripts -->
<!--匯入套件-->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

<!--  BOOTSTRAP TABLE  -->
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/extensions/export/bootstrap-table-export.min.js"></script>


<!--匯入所需JS-->
<script src="resource/js/admin/share/page.js"></script>
<script src="resource/js/admin/currency_conversion.js"></script>

</body>
</html>