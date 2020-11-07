<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>儲值中心</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/css/home.css"/>
    <link rel="stylesheet" href="resource/css/share.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/student/stored_value.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
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

<main>
    <div class="container stored_value_area">
        <h3 class="stored_value_title">
            <i class="fa fa-credit-card" style="font-size:23px;"></i>
            選擇儲值項目
        </h3>
        <!--儲值選項-->
        <div class="stored_value_option">
            <div class="option option_sel" onclick="sel_stored_value_option(this)" id="visa_option">
                <img src="https://cdngarenanow-a.akamaihd.net/webmain/static/payment_center/tw/menu/creditcard_pc_tw.png"
                     title="VISA">
            </div>
        </div>

        <!--顯示所選擇的儲值項目簡介-->
        <div class="select_option_information_area">
            <span id="option_information_text">使用VISA或MASTER 3D驗證信用卡，享受最便利又安全的儲值體驗。</span>
            <h3 id="select_option_text"><i class="fa fa-file-text" style="font-size:20px;"></i>&nbsp;信用卡儲值</h3>
        </div>

        <!--幣值選項-->
        <div class="currency_value_area row">
            <div class="currency_value_select_area col-sm-12">
                <div class="currency_value_area_title row">
                    <span class="currency_value_area_price col-sm-6">價格</span>
                    <span class="currency_value_area_worth col-sm-6">幣值</span>
                </div>
                <div class="currency_value_area_option row">
                    <!--一整個幣值選項-->
                    <div class="custom-control custom-radio col-sm-12 d-flex">
                        <input type="radio" class="custom-control-input currency_item" id="currency_1" name="currency_item"
                               value="100">
                        <label class="custom-control-label col-sm-6" for="currency_1">TWD$ 100</label>
                        <label class="col-sm-6" for="currency_1">鑽石 x 300</label>
                    </div>

                    <!--一整個幣值選項-->
                    <div class="custom-control custom-radio col-sm-12 d-flex">
                        <input type="radio" class="custom-control-input currency_item" id="currency_2" name="currency_item"
                               value="250">
                        <label class="custom-control-label col-sm-6" for="currency_2">TWD$ 250</label>
                        <label class="col-sm-6" for="currency_2">鑽石 x 750</label>
                    </div>

                    <!--一整個幣值選項-->
                    <div class="custom-control custom-radio col-sm-12 d-flex">
                        <input type="radio" class="custom-control-input currency_item" id="currency_3" name="currency_item"
                               value="500">
                        <label class="custom-control-label col-sm-6" for="currency_3">TWD$ 500</label>
                        <label class="col-sm-6" for="currency_3">鑽石 x 1500</label>
                    </div>

                    <!--一整個幣值選項-->
                    <div class="custom-control custom-radio col-sm-12 d-flex">
                        <input type="radio" class="custom-control-input currency_item" id="currency_4" name="currency_item"
                               value="1000">
                        <label class="custom-control-label col-sm-6" for="currency_4">TWD$ 1000</label>
                        <label class="col-sm-6" for="currency_4">鑽石 x 3000</label>
                    </div>

                    <!--一整個幣值選項-->
                    <div class="custom-control custom-radio col-sm-12 d-flex">
                        <input type="radio" class="custom-control-input currency_item" id="currency_5" name="currency_item"
                               value="2500">
                        <label class="custom-control-label col-sm-6" for="currency_5">TWD$ 2500</label>
                        <label class="col-sm-6" for="currency_5">鑽石 x 7500</label>
                    </div>
                </div>
            </div>
            <!--同意合約部分-->
            <div class="agreement">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck"
                           onchange="$('#agree_btn').attr('disabled',!this.checked)">
                    <label class="custom-control-label" for="customCheck">我同意<a href="#" target="_blank">《會員系統服務合約
                            、 個人資料隱私權保護政策》</a>未滿20歲之消費者，應由法定代理人閱讀並同意上述合約後，方得使用本儲值服務。</label>
                </div>
                <button type="button" id="agree_btn" class="btn btn-danger agree_btn" onclick="sendStoredValue()"
                        disabled>同意合約並確認進行儲值
                </button>
            </div>
        </div>
    </div>

</main>

<footer>
    <!-- 頁尾 start-->
    <div class="max-width-800 about" data-v-4e5639f4="">
        <div class="row about">
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
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>

<!--匯入所需JS-->
<script src="resource/js/student/stored_value.js"></script>
<script src="resource/js/share.js"></script>

</body>
</html>