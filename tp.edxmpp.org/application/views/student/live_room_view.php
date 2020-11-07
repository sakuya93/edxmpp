<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>username - TPLive!</title>
    <!--  Package Start -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--  Package End -->

    <!--  View Start  -->
    <link rel="stylesheet" href="../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../resource/css/share.css">
    <link rel="stylesheet" href="../resource/css/home.css"/>
    <link rel="stylesheet" href="../resource/css/student/live_room.css">
    <!--  View Other  -->
</head>
<body>

<!--<header>-->
<!--    <nav class="navbar navbar-expand-lg navbar-light header-shadow">-->
<!--        <a class="navbar-brand header-logo-block" href="../student"><img class="header-logo"-->
<!--                                                                         src="../resource/pics/share/logo.png"></a>-->
<!--        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"-->
<!--                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">-->
<!--            <span class="navbar-toggler-icon"></span>-->
<!--        </button>-->
<!---->
<!--        <div class="collapse navbar-collapse" id="navbarSupportedContent">-->
<!--<ul class="navbar-nav mr-auto">-->
<!--    <li class="nav-item"> <!--  想學什麼語言? 搜尋  -->
<!--        <div class="search_area">-->
<!--            <input type="text" id="search_text" autocomplete="off" placeholder="探索課程"-->
<!--                   class="el-input__inner">-->
<!--            <i class="fa fa-search" onclick="search_course('../')"></i>-->
<!--        </div>-->
<!--    </li>-->
<!--</ul>-->
<!---->
<!--            <div class="row right-bar">-->
<!--                <ul class="navbar-nav right-icon-group">-->
<!--                    <li class="nav-item option"> <!-- 鈴鐺Menu -->
<!--                        <i><a class="nav-link fa fa-bell"></a></i>-->
<!--                        <div class="menu" id="menu-bell">-->
<!--                            <p class="menu-item">通知</p>-->
<!--                            <hr>-->
<!--                            <div class="menu-item" id="class-request">課時請求-->
<!--                                <img class="right-arrow" data-bell-right-arrow-->
<!--                                     src="../resource/pics/share/right-arrow.png">-->
<!--                            </div>-->
<!--                            <hr>-->
<!--                            <div class="menu-item" id="class-question">課程問題-->
<!--                                <img class="right-arrow" data-bell-right-arrow-->
<!--                                     src="../resource/pics/share/right-arrow.png">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="menu" id="menu-request">-->
<!--                            <div class="menu-item btn-back"><img class="left-arrow" data-bell-right-arrow-->
<!--                                                                 src="../resource/pics/share/left-arrow.png">返回-->
<!--                            </div>-->
<!--                            <hr>-->
<!--                        </div>-->
<!--                        <div class="menu" id="menu-question">-->
<!--                            <div class="menu-item btn-back"><img class="left-arrow" data-bell-right-arrow-->
<!--                                                                 src="../resource/pics/share/left-arrow.png">返回-->
<!--                            </div>-->
<!--                            <hr>-->
<!--                        </div>-->
<!--                    </li> <!-- 鈴鐺Menu -->
<!--                    <li class="nav-item option"> <!-- 訊息Menu -->
<!--                        <i><a class="nav-link fa fa-envelope"></a></i>-->
<!--                        <div class="menu" id="menu-envelope">-->
<!--                            <p class="menu-item">訊息</p>-->
<!--                            <hr>-->
<!--                            <div class="menu-item menu-message row" id="menu-content-person-1">-->
<!--                                <img src="../resource/pics/share/user.png">-->
<!--                                <div class="col-sm-8">-->
<!--                                    <h2 class="user">ミランダ Miranda</h2>-->
<!--                                    <p class="outer-message-content">您好</p>-->
<!--                                    <p class="message-date">08/29 10:10</p>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <hr>-->
<!--                        </div>-->
<!--                    </li> <!-- 訊息Menu -->
<!--                    <li class="nav-item option"> <!-- 購物車Menu -->
<!--                        <i class='fas'><a class="nav-link fa fa-shopping-cart" href="../shopping_cart"></a></i>-->
<!--                    </li> <!-- 購物車Menu -->
<!--                    <li class="nav-item head_sticker option"> <!-- 頭像 -->
<!--                        <a class="nav-link" href="#personal_div" data-toggle="collapse"><img-->
<!--                                src="../resource/image/student/photo/--><? //= $photo_path ?><!--"></a>-->
<!--                    </li> <!-- 頭像 -->
<!--                </ul>-->
<!--                <ul class="navbar-nav right-item-group col-sm">-->
<!--                    <li class="nav-item dropdown option">-->
<!--                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"-->
<!--                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                            繁體中文(台灣)-->
<!--                        </a>-->
<!--                        <div class="dropdown-menu" aria-labelledby="navbarDropdown1">-->
<!--                            <a class="dropdown-item" href="#"">繁體中文(台灣)</a>-->
<!--                            <a class="dropdown-item" href="#">English</a>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    <li class="nav-item dropdown option">-->
<!--                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"-->
<!--                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                            TWD-->
<!--                        </a>-->
<!--                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--                            <a class="dropdown-item active" href="#">TWD</a>-->
<!--                            <a class="dropdown-item" href="#">USD</a>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    <!--目前時間-->
<!--                    <li class="nav-item option">-->
<!--                        <div class="nav-link" id="showbox"></div>-->
<!--                    </li>-->
<!--                    <li class="nav-item option">-->
<!--                        --><? //= $become_teacher_link ?>
<!--                    </li>-->
<!--                    <li class="nav-item option">-->
<!--                        --><? //= $course_management_link ?>
<!--                    </li>-->
<!---->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
<!--    </nav>-->
<!---->
<!--    <!--        右側帳號資訊欄         -->
<!--    --><? //= $RightInformationColumn ?>
<!---->
<!--    <div class="message-box menu" id="page-1"> <!--  訊息list  -->
<!--        <div class="top-bar search">-->
<!--            <img src="../resource/pics/home/magnifying-glass.png">-->
<!--            <input type="text" placeholder="搜尋">-->
<!--            <i class="fa fa-remove"></i>-->
<!--        </div>-->
<!--        <hr>-->
<!--        <div class="menu-item menu-message row" id="list-content-person-1">-->
<!--            <img src="../resource/pics/share/user.png">-->
<!--            <div class="col-sm-8">-->
<!--                <h2 class="user">ミランダ Miranda</h2>-->
<!--                <p class="outer-message-content">您好</p>-->
<!--                <p class="message-date">08/29 10:10</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <hr>-->
<!--    </div>-->
<!---->
<!--    <div class="message-box menu" id="page-2"> <!-- 聊天 -->
<!--        <div class="top-bar col-sm">-->
<!--            <i class="fa fa-mail-reply"></i>-->
<!--            <i class="fa fa-remove"></i>-->
<!--        </div>-->
<!--        <div class="menu-item menu-message row content-person">-->
<!--            <img src="../resource/pics/share/user.png">-->
<!--            <div class="message-block">-->
<!--                <p class="message-content">您好</p>-->
<!--                <p class="message-date">08/29 10:10</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="menu-item menu-message row user">-->
<!--            <div class="message-block">-->
<!--                <p class="message-content">我想請問課程教學方式asasasasasasasasczxcxzczxcxz</p>-->
<!--                <p class="message-date">08/29 10:12</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="send-message">-->
<!--            <input type="text" placeholder="輸入訊息..." style="width:200px">-->
<!--            <i class="fa fa-location-arrow"></i>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--</header>-->

<main>

    <div class="live container row">
        <!--	嵌入直播	-->
        <div class="col-sm-6">
            <h3>直播房間</h3>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/f7X4HsXcts8" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
        
        <div class="col-sm-2"></div>

        <div class="col-sm-4">
            <h3>聊天室</h3>
            <!--主要修改v=後面那串碼(可由直播影片ID那複製);後面則是呈現這聊天室的網域名稱-->
            <iframe src="https://www.youtube.com/live_chat?v=f7X4HsXcts8&embed_domain=ajcode.tk" width="300"
                    height="600"></iframe>
        </div>

    </div>

</main>


<!--            Script            -->
<!--  Package  -->
<script src="../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../resource/package/js/bootstrap.js"></script>


<!--  View  -->
<script src="../resource/js/student/live_room.js"></script>

</body>
</html>
