<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>購物車</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/css/home.css">
    <link rel="stylesheet" href="resource/css/share.css">
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/student/shopping_cart.css"/>
    <link rel="stylesheet" href="resource/css/student/student.css">
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
    <div class="shopping_cart-container">
        <h4 class="title"><b>以下為您報名的課程</b></h4>

        <!--商品-->
        <div class="row">
            <div class="col-sm-5 commodity_title">
                <p class="title_text">商品</p>
            </div>

            <div class="col-sm-7 row news_title">
                <p class="title_text learn_title">學習</p>
                <p class="title_text class_hours_title">時間</p>
                <p class="title_text course_amount_title">課程金額</p>
                <p class="title_text del_commodity_title">刪除</p>
            </div>
        </div>
        <div class="bd-2-s-gray commodity">
            <?= $content ?>
        </div>


        <!--付款方式-->
        <p class="title_text">選擇付款方式</p>
        <div class="payment_method">
            <div class="card"
                 onclick="$('.card i').css('display','none');$('#diamond_payment').css('display','block')">
                <i class="fa fa-check-circle" id="diamond_payment" style="display: block"></i>
                <p>適用所有平台使用者</p>
                <span>鑽石付款</span>
            </div>
            <div class="card"
                 onclick="$('.card i').css('display','none');$('#credit_card_payment').css('display','block')">
                <i class="fa fa-check-circle" id="credit_card_payment"></i>
                <p>VISA</p>
                <span>現金付款</span>
            </div>
        </div>

        <!--購物明細-->
        <p class="title_text">購物明細</p>
        <div class="bd-2-s-gray buy_detail">
            <div class="item">
                <div class="shopping_details_header">
                    <?= $detail ?>
                </div>

                <div class="separation_line"></div> <!--分隔線-->

                <div class="shopping_details_intermediate">
                    <div class="price_title">
                        <span>總價</span>
                    </div>
                    <div class="price_total">
                        <span id="price_total">TWD$ <?= $totalPrice ?></span>
                    </div>
                </div>

                <div class="shopping_details_intermediate">
                    <div class="price_title">
                        <span>鑽石</span>
                    </div>
                    <div class="price_total">
                        <span id="diamond_price_total"><i class="fa fa-diamond" style="font-size:24px"></i>&nbsp;<?=$diamond?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="agreement">
            <label>
                <input type="checkbox" class="checkbox" onchange="$('#agree_btn').attr('disabled',!this.checked)">
                <span>我同意<a href="#" target="_blank">《服務條款與隱私聲明》</a>並同意如要退費僅能退至XXX教學平台作為消費幣使用，不得退回現金。</span>
            </label>
            <br>
            <button type="button" id="agree_btn" class="btn btn-danger agree_btn" onclick='shoppingBuyClass_sendOut()'
                    disabled>同意並支付
            </button>
        </div>

    </div>

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

<!--匯入所需JS-->
<script src="resource/js/student/shopping_cart.js"></script>
<script src="resource/js/student/student.js"></script>
<script src="resource/js/share.js"></script>

</body>
</html>
