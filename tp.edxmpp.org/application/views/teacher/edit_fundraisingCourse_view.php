<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>編輯-募資課程</title>
    <link rel="stylesheet" href="../resource/package/css/bootstrap.css"/>

    <!-- Autocomplete -->
    <link rel="stylesheet" href="../resource/package/css/jquery.autocomplete.css"/>

    <link rel="stylesheet" href="../resource/css/share.css">
    <link rel="stylesheet" href="../resource/css/home.css"/>
    <link rel="stylesheet" href="../resource/css/window.css">
    <link rel="stylesheet" href="../resource/css/student/student.css">
    <link rel="stylesheet" href="../resource/css/teacher/fundraising_course.css">
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
    <div class="container mbr-3 mtr-10 fundraising_course_body" id="setFundraisingCourseForm">

        <div class="input-title col-sm-12 ft-24 mbr-3">募資課程資訊</div>

        <div class="mls-15">
            <form id="update_thumbnail_form" enctype="multipart/form-data" method="post">
                <div class="input-group col-sm-6 mb-3" id="setThumbnailForm">
                    <div class="input-title col-sm-12">課程縮圖<br> <img
                                src="../resource/image/teacher/fundraisingCourse/<?= $Information[0]->fc_image ?>?value=<?= uniqid()?>" width="160"
                                height="160" id="thumbnail"></div>
                    <input type="file"
                           onchange="handle(this.files, 'thumbnail'), $('#upload_image_btn').attr('disabled', false);"
                           class="form-control"
                           accept="image/png, image/jpeg, .pdf">
                    <button type="submit" class="btn btn-primary" id="upload_image_btn" disabled="">更新課程縮圖</button>
                </div>
            </form>

            <form id="edit_fundraising_course_form" enctype="multipart/form-data" method="post">
                <span id="fc_id" style="display: none"><?= $Information[0]->fc_id ?></span>
                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">課程名稱<span class="error-text basic-data-error">課程名稱不可為空</span>
                    </div>
                    <input type="text" class="form-control fundraising_course-data"
                           value="<?= $Information[0]->fc_courseName ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12 dy-inline">課程影片網址(請輸入watch?v=後面的網址即可)
                        <button class="btn fa fa-info-circle" id="video_info" onclick="video_info_open('');"
                                type="button"
                                data-toggle="tooltip" data-placement="bottom" title="課程影片網址提示"></button>
                        <br>
                        <span class="error-text basic-data-error">課程影片網址不可為空</span>
                    </div>
                    <input type="text" class="form-control fundraising_course-data" id="video_url_input"
                           value="<?= $Information[0]->fc_filmUrl ?>">
                    <iframe width="560" height="315" src="" id="video" class="mt-10" frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">上課類型<span class="error-text basic-data-error">請選擇上課類型</span>
                    </div>
                    <select class="custom-select fundraising_course-data" id="type">
                        <option value="" selected="" disabled="">請選擇類型</option>
                        <option value="0">直播</option>
                        <option value="1">影片</option>
                    </select>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">課程介紹<span class="error-text basic-data-error">課程介紹不可為空</span></div>
                    <textarea name="editor1" id="editor1" rows="10" class="form-control" cols="80"></textarea>
                </div>
                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">課程簡介<span class="error-text basic-data-error">課程介紹不可為空</span></div>
                    <textarea name="editor2" id="editor2" rows="10" class="form-control" cols="80"></textarea>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">貸幣選擇<span class="error-text basic-data-error">請選擇貸幣</span></div>
                    <select class="custom-select fundraising_course-data" id="currency_type">
                        <option value="TWD" selected="">台幣</option>
                        <option value="VND">越南盾</option>
                        <option value="MYR">馬來幣</option>
                    </select>
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">課程時數<span class="error-text basic-data-error">課程時數不可為空</span>
                    </div>
                    <input type="number" class="form-control fundraising_course-data"
                           value="<?= $Information[0]->fc_hours ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">原始價格<span class="error-text basic-data-error">原始價格不可為空</span>
                    </div>
                    <input type="number" class="form-control fundraising_course-data"
                           value="<?= $Information[0]->fc_normalPrice ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">募資價格<span class="error-text basic-data-error">募資價格不可為空</span>
                    </div>
                    <input type="number" class="form-control fundraising_course-data"
                           value="<?= $Information[0]->fc_fundraisingPrice ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">預計人數<span class="error-text basic-data-error">預計人數不可為空</span>
                    </div>
                    <input type="number" class="form-control fundraising_course-data"
                           value="<?= $Information[0]->fc_expectedNumber ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">募資結束時間<span class="error-text basic-data-error">募資結束時間不可為空</span>
                    </div>
                    <input type="datetime-local" id="end_time" class="form-control fundraising_course-data"
                           value="<?= str_replace(" ", "T", $Information[0]->fc_endTime) ?>">
                </div>
                <div id="editorData1" style="display: none"><?=$Information[0]->fc_courseIntroduction?></div>
                <div id="editorData2" style="display: none"><?=$Information[0]->fc_briefIntroduction?></div>
                <div class="col-sm-6">
                    <input class="btn btn-primary mb-3" type="submit" value="編輯">
                </div>
            </form>
        </div>
    </div> <!-- 募資課程資訊 -->

    <div class="container mbr-3 mtr-10 course_tag_body">
        <div class="col-sm-12 ft-24 mbr-3 mt-3" id="setTagForm">
            <h4>課程標籤
                <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right"
                   title="課程標籤有助於讓學生搜尋到此課程的關鍵字，因此越詳細越好(包括專業術語、學生群的語系翻譯等等)"></i>
            </h4>
            <div class="tag_area">
                <input type="text" class="form-control" id="tag_value" autocomplete="off">
                <button type="button" class="btn btn-success mt-2" onclick="add_course_tag()">新增</button>
            </div>
            <div class="added_tag_area">
                <!-- 用來顯示已新增的標籤區塊-->
            </div>
            <button type="button" class="btn btn-primary save_tag mt-3" onclick="save_tag()">儲存所有標籤</button>
        </div>

    </div> <!-- 課程標籤 -->

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
</body>
<!-- Scripts -->
<!--匯入套件-->
<script src="../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>

<!-- Autocomplete -->
<script src="../resource/package/js/jquery.autocomplete.js"></script>
<script src="../resource/js/share/tag_autocomplete.js"></script>

<!--匯入所需JS-->
<script src="../resource/js/student/student.js"></script>
<script src="../resource/js/teacher/fundraising_course.js"></script>
<script src="../resource/js/share.js"></script>
<script>
    $(document).ready(function () {
        //文字編輯框
        CKEDITOR.replace("editor1");
        CKEDITOR.replace("editor2");
        CKEDITOR.instances.editor1.setData(document.getElementById('editorData1').innerHTML);
        CKEDITOR.instances.editor2.setData(document.getElementById('editorData2').innerHTML);
        $(document).off('focusin.modal');

        $('[data-toggle="tooltip"]').tooltip(); //課程標籤提示文字框
        // $("#edit_fundraising_course_form").submit(function (event) {             //阻止自動提交
        //     event.preventDefault();
        // });
        $("#type").val("<?= $Information[0]->fc_type ?>"); //課程類型初始載入
        $('#currency_type').val("<?= $Information[0]->fc_currency ?>"); //貸幣選擇初始載入

        /*課程標籤初始資料載入區--------------------------------------------*/
        var fc_label = "<?= $Information[0]->fc_label ?>".split("、");
        for (var i = 0; i < fc_label.length - 1; i++) {
            $('.added_tag_area').append('<span class="tag" id="tag_id' + (i + 1) + '" data-index="' + (i + 1) + '">' + fc_label[i] + '<i class="fa fa-close" onclick="real_del_tag(' + (i + 1) + ')"></i></span>');
        }
    });
</script>
</html>