<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>成為老師</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/css/share.css">
    <link rel="stylesheet" href="resource/css/home.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/student/student.css">
    <link rel="stylesheet" href="resource/css/student/modify_member_information.css">
    <link rel="stylesheet" href="resource/css/student/become_teacher.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand header-logo-block" href="student"><img class="header-logo" src="resource/pics/share/logo.png"></a>
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

    <!--    <div class="message-box menu" id="page-1"> <!--  訊息list  -->
    <!--        <div class="top-bar search">-->
    <!--            <img src="resource/pics/home/magnifying-glass.png">-->
    <!--            <input type="text" placeholder="搜尋">-->
    <!--            <i class="fa fa-remove"></i>-->
    <!--        </div>-->
    <!--        <hr>-->
    <!--        <div class="menu-item menu-message row" id="list-content-person-1">-->
    <!--            <img src="resource/pics/share/user.png">-->
    <!--            <div class="col-sm-8">-->
    <!--                <h2 class="user">ミランダ Miranda</h2>-->
    <!--                <p class="outer-message-content">您好</p>-->
    <!--                <p class="message-date">08/29 10:10</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <hr>-->
    <!--    </div>-->

    <div class="message-box menu" id="page-2"> <!-- 聊天 -->
        <div class="top-bar col-sm">
            <b id="chat_object">傳送對象姓名</b>
            <i class="fa fa-close" id="message-box-close"></i>
        </div>

        <div class="message_area">
            <!--            對方-->
            <!--            <div class="menu-item menu-message row content-person">-->
            <!--                <div class="message-block">-->
            <!--                    <p class="message-content">您好</p>-->
            <!--                    <p class="message-date">2020/2/5 下午2:29:04</p>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            自己-->
            <!--            <div class="menu-item menu-message row user">-->
            <!--                <div class="message-block">-->
            <!--                    <p class="message-content">我想請問課程教學方式</p>-->
            <!--                    <p class="message-date">2020/2/5 下午2:29:04</p>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>

        <div class="send-message">
            <textarea class="input_message" id="input_message" rows="3" cols="32" placeholder="輸入訊息..."></textarea>
        </div>
    </div>

</header>

<main>
    <div class="container mbr-3 mtr-10 become_teacher_body">

        <div class="input-title col-sm-12 ft-24">基本資料</div>

        <div class="mls-15">
            <form id="basic_information_form"
                  onsubmit="basic_information_sendOut(document.getElementsByClassName('basic_information-data')); return false;">
                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">姓名<span class="error-text basic-data-error">姓名不可為空</span></div>
                    <input type="text" class="form-control basic_information-data" value="<?= $simple_data->name ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">國籍<span class="error-text basic-data-error">國籍不可為空</span></div>
                    <select class="custom-select basic_information-data" id="country">
                        <option value="" selected disabled>請選擇國籍</option>
                        <option value="台灣">台灣</option>
                        <option value="越南">越南</option>
                        <option value="馬來西亞">馬來西亞</option>
                    </select>
                </div>

                <div class="input-group col-sm-6 mb-3 language_spoken">
                    <div class="input-title col-sm-12">會說語言<span class="error-text basic-data-error">會說語言不可為空</span></div>
                    <select class="custom-select selectpicker form-control" id="speakLanguage" multiple>
                        <option value="中文">中文</option>
                        <option value="英文">英文</option>
                        <option value="日文">日文</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <input class="btn btn-primary mb-3" type="submit" value="儲存">
                </div>
            </form>


        </div>
    </div> <!-- 基本資料 -->

    <div class="container mbr-3 become_teacher_body">
        <div class="input-title col-sm-12 ft-24">老師介紹</div>

        <div class="mls-15">
            <form id="teacher_introduction_form"
                  onsubmit="teacher_introduction_sendOut(document.getElementsByClassName('teacher_introduction-data')); return false;">
                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">極短描述<span class="error-text teacher-introduction-data-error">極短描述不可為空</span></div>
                    <input type="text" class="form-control teacher_introduction-data"
                           value="<?= $simple_data->very_short_des ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">簡短介紹<span class="error-text teacher-introduction-data-error">簡短介紹不可為空</span></div>
                    <input type="text" class="form-control teacher_introduction-data"
                           value="<?= $simple_data->short_des ?>">
                </div>

                <div class="input-group col-sm-6 mb-3">
                    <div class="input-title col-sm-12">詳細介紹<span class="error-text teacher-introduction-data-error">詳細介紹不可為空</span></div>
                    <input type="text" class="form-control teacher_introduction-data" value="<?= $simple_data->des ?>">
                </div>

                <div class="col-sm-6">
                    <input class="btn btn-primary mb-3" type="submit" value="儲存">
                </div>
            </form>


        </div>
    </div> <!-- 老師介紹 -->

    <div class="container mbr-3 become_teacher_body">
        <div class="input-title col-sm-12 ft-24">工作經驗</div>

        <div class="mls-15">

            <form id="work_experience_form"
                  onsubmit="work_experience_sendOut(document.getElementsByClassName('work_experience-data')); return false;">
                <div id="work_experience_body">
                    <!--         新增工作經驗資料欄位           -->
                </div>
                <div class="col-sm-6">
                    <i class="fa fa-plus btn btn-info mb-3" type="button" onclick="add_work_experience()">新增一筆工作經驗</i>
                </div>
                <div class="col-sm-6">
                    <input class="btn btn-primary mb-3" type="submit" value="送出資料">
                </div>
            </form>


        </div>
    </div> <!-- 工作經驗 -->

    <div class="container mbr-3 become_teacher_body">
        <div class="input-title col-sm-12 ft-24">學歷背景</div>
        <div class="mls-15">

            <div id="modify_education_body">
                <!--     修改學歷背景資料欄位      -->
            </div>

            <form id="education_background_form" enctype="multipart/form-data" method="post">
                <div id="education_body">
                    <!--     新增學歷背景資料欄位      -->
                </div>
                <div class="col-sm-6">
                    <i class="fa fa-plus btn btn-info mb-3" type="button" onclick="add_education()">新增學歷背景</i>
                </div>
                <div class="col-sm-6">
                    <input class="btn btn-primary mb-3" type="submit" value="送出資料">
                </div>
            </form>


        </div>
    </div> <!-- 學歷背景 -->

    <div class="container mbr-3 become_teacher_body">
        <div class="input-title col-sm-12 ft-24">教學證照</div>

        <div class="mls-15">
            <div id="modify_teaching_license_body">
                <!--     修改教學證照資料欄位      -->
            </div>

            <form id="teaching_license_form" enctype="multipart/form-data" method="post">
                <div id="teaching_license_body">
                    <!--         新增教學證照資料欄位           -->
                </div>
                <div class="col-sm-6">
                    <i class="fa fa-plus btn btn-info mb-3" type="button" onclick="add_teaching_license()">新增教學證照</i>
                </div>
                <div class="col-sm-6">
                    <input class="btn btn-primary mb-3" type="submit" value="送出資料">
                </div>
            </form>


        </div>
    </div> <!-- 教學證照 -->


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
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!--匯入所需JS-->
<script src="resource/js/student/student.js"></script>
<script src="resource/js/student/become_teacher.js"></script>
<script src="resource/js/share.js"></script>
<script>
    $(document).ready(function () {
        $("#basic_information_form, #teacher_introduction_form, #work_experience_form, #education_background_form").submit(function (event) {             //阻止自動提交
            event.preventDefault();
        });
    });

    //初始資料載入
    $('#country').val('<?=$simple_data->country?>');

    //會說語言
    var speakLanguage = '<?=$simple_data->speak_language?>'.split(",");

    if (speakLanguage[0] != "") {
        var select = document.getElementById('speakLanguage');

        for (var i = 0; i < select.length; i++) {
            for (var j = 0; j < speakLanguage.length; j++) {
                if (select.options[i].text == speakLanguage[j]) {
                    select.options[i].selected = true;
                }
            }
        }
    }

    // 工作經驗載入
    load_work_experience(<?=json_encode($complex_data)?>);

    // 學習背景載入
    load_education(<?=json_encode($edu_data)?>);

    // 教學證照載入
    load_teaching_license(<?=json_encode($tl_data)?>);
</script>

</body>
</html>