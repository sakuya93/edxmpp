<!DOCTYPE html>
<html lang="en">
<head>
    <script data-ad-client="ca-pub-7065586176039240" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>廣告頁面</title>

    <!--  Required Start  -->
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/home.css"/>
    <link rel="stylesheet" href="resource/css/share.css"/>
    <link rel="stylesheet" href="resource/css/student/student.css">
    <!--  Required End  -->

    <!--  View Start -->
    <link rel="stylesheet" href="resource/css/student/ad_page.css">
    <!--  View End -->

    <!--  Package Start  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--  Package End  -->
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

<main style="margin-top: 128px">
    <section class="container-fluid mb-5">
    <!--
        廣告 sample
        <div class="card text-info mx-auto my-4" style="max-width: 18rem;">
                        <span class="card-header"></span>
                        <div class="p-2 bg-dark d-flex text-white justify-content-center align-items-center m-2" style="height: 250px">
                            <p class="h1">廣告1</p>
                        </div>
        </div>
    -->

        <div class="row">
<!--            <div class="col-md-3">-->
<!--                <div class="card text-info mx-auto my-4" style="max-width: 18rem;">-->
<!--                    <span class="card-header"></span>-->
<!--                    <img src="resource/image/ads/1-md5-87a6c69a9617e29e614f6d31ed8ca596.jpg" class="card-img p-2" alt="ad">-->
<!--                </div>-->
<!---->
<!--                <div class="card text-info mx-auto my-4" style="max-width: 18rem;">-->
<!--                    <span class="card-header"></span>-->
<!--                    <img src="resource/image/ads/6148698567ceb7c3fa073293106fed28.jpg" class="card-img p-2" alt="ad">-->
<!--                </div>-->
<!--            </div>-->

            <div class="row m-4 col-sm">
                <div class="d-flex justify-content-center align-items-center bg-secondary col-sm-12">
                    <div class="d-flex justify-content-center align-items-center text-success" id="countdown__finished"></div>
                    <div class="d-flex justify-content-center align-items-center text-white" id="countdown_box">
                        <p class="h2 mr-3">影片載入中</p>
                        <p class="h3" id="countdown">3</p>
                        <span class="ouro ouro3">
                            <span class="left">
                                <span class="anim"></span>
                            </span>
                            <span class="right">
                                <span class="anim"></span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-info mx-auto my-4" style="max-width: 18rem;">
                    <span class="card-header"></span>
                    <img src="resource/image/ads/bedfae509c9fc4f9c1cf1cf03d24d54c.jpg" class="card-img p-2" alt="ad">
                </div>

<!--                <div class="card text-info mx-auto my-4" style="max-width: 18rem;">-->
<!--                    <span class="card-header"></span>-->
<!--                    <img src="resource/image/ads/f2f024666c771c6a325662a1aed27d36.jpg" alt="amazon ads">-->
<!--                </div>-->
            </div>

        </div>

    </section>
</main>


<!-- Scripts -->
<!--匯入套件-->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>

<!--匯入所需JS-->
<script src="resource/js/home/account.js"></script>
<script src="resource/js/student/student.js"></script>
<script src="resource/js/share.js"></script>

<!--View-->
<script src="resource/js/student/ad_page.js"></script>


</body>
</html>