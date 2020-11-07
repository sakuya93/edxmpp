<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>後台管理-會員聯繫管理</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link href="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="resource/css/share.css"/>
    <link rel="stylesheet" href="resource/css/share/live_chat.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/admin/admin.css">
    <link rel="stylesheet" href="resource/css/admin/message_management.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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
                <div class="select mt-10 flex-nowrap">
                    <button class="btn btn-outline-danger designated_contact_btn" onclick="designated_contact()">指定聯絡人
                    </button>
<!--                    <div class="input-group search_area">-->
<!--                        <span class="input-group-btn">-->
<!--						        <button class="btn btn-default" type="button">-->
<!--							        <i class="fa">&#xf002;</i>-->
<!--						        </button>-->
<!--					        </span>-->
<!--                        <input type="text" class="form-control" placeholder="搜尋">-->
<!--                    </div>-->
                </div>
            </div>

            <div class="message_present row">
                <div class="message_present_left col-sm-6">
                    <!-- JS形成 -->
                </div>
                <div class="message_present_right p-0 col-sm-6">
                    <!-- 未選擇聯絡人時才顯示 -->
                    <span class="not_sel_message">請選擇訊息</span>

                    <div class="col-sm-12 d-none message_bar p-0">
                        <div class="chat__header">
                            <div class="chat__header-name">
                                <div class="header-name__text" id="informant_name"></div>
                            </div>
                            <div class="chat__header-close" onclick="close_right_window()">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>

                        <div class="chat__body">
                            <div class="chat__body-inner">
                                <!--JS形成訊息-->
                            </div>
                        </div>

                        <div class="chat__footer">
                            <div class="chat__footer-sendMessage-box d-flex align-items-center">
                                <textarea class="sendMessage__input form-control"
                                          placeholder="請輸入訊息..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Scripts -->
<!--匯入套件-->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>

<!--  BOOTSTRAP TABLE  -->
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/extensions/export/bootstrap-table-export.min.js"></script>


<!--匯入所需JS-->
<script src="resource/js/admin/share/page.js"></script>
<script src="resource/js/share/live_chat.js"></script>
<script src="resource/js/admin/message_management.js"></script>

</body>
</html>