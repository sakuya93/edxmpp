<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>電子郵件通行證</title>
    <link rel="stylesheet" href="../../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../../resource/css/window.css">
    <link rel="stylesheet" href="../../resource/css/home.css">
    <link rel="stylesheet" href="../../resource/css/student/course/course_introduction.css"/>
    <link rel="stylesheet" href="../../resource/css/student/student.css">
    <link rel="stylesheet" href="../../resource/css/share.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand header-logo-block" href="../../student"><img class="header-logo" src="../../resource/pics/share/logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="row right-bar">
                <ul class="navbar-nav right-icon-group">
                    <li class="nav-item option"> <!-- 鈴鐺Menu -->
                        <i><a class="nav-link fa fa-bell"></a></i>
                        <div class="menu" id="menu-bell">
                            <p class="menu-item">通知</p>
                            <hr>
                            <div class="menu-item" id="class-request">課時請求
                                <img class="right-arrow" data-bell-right-arrow src="../../resource/pics/share/right-arrow.png">
                            </div>
                            <hr>
                            <div class="menu-item" id="class-question">課程問題
                                <img class="right-arrow" data-bell-right-arrow src="../../resource/pics/share/right-arrow.png">
                            </div>
                        </div>
                        <div class="menu" id="menu-request">
                            <div class="menu-item btn-back"><img class="left-arrow" data-bell-right-arrow
                                                                 src="../../resource/pics/share/left-arrow.png">返回
                            </div>
                            <hr>
                        </div>
                        <div class="menu" id="menu-question">
                            <div class="menu-item btn-back"><img class="left-arrow" data-bell-right-arrow
                                                                 src="../../resource/pics/share/left-arrow.png">返回
                            </div>
                            <hr>
                        </div>
                    </li> <!-- 鈴鐺Menu -->
                    <li class="nav-item option"> <!-- 訊息Menu -->
                        <i><a class="nav-link fa fa-envelope"></a></i>
                        <div class="menu" id="menu-envelope">
                            <p class="menu-item">訊息</p>
                            <hr>
                            <div class="menu-item menu-message row" id="menu-content-person-1">
                                <img src="../../resource/pics/share/user.png">
                                <div class="col-sm-8">
                                    <h2 class="user">ミランダ Miranda</h2>
                                    <p class="outer-message-content">您好</p>
                                    <p class="message-date">08/29 10:10</p>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </li> <!-- 訊息Menu -->
                    <li class="nav-item option"> <!-- 購物車Menu -->
                        <i class='fas'><a class="nav-link fa fa-shopping-cart" href="../../shopping_cart"></a></i>
                    </li> <!-- 購物車Menu -->
                    <li class="nav-item head_sticker option"> <!-- 頭像 -->
                        <a class="nav-link" href="#personal_div" data-toggle="collapse"><img
                                    src="../../resource/image/student/photo/<?= $photoPath ?>"></a>
                    </li> <!-- 頭像 -->
                </ul>
                <ul class="navbar-nav right-item-group col-sm">
                    <li class="nav-item dropdown option">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            繁體中文(台灣)
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                            <a class="dropdown-item" href="#"">繁體中文(台灣)</a>
                            <a class="dropdown-item" href="#">English</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown option">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            TWD
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item active" href="#">TWD</a>
                            <a class="dropdown-item" href="#">USD</a>
                        </div>
                    </li>
                    <!--目前時間-->
                    <li class="nav-item option">
                        <div class="nav-link" id="showbox"></div>
                    </li>
                    <li class="nav-item option">
                        <?= $become_teacher_link ?>
                    </li>
                    <li class="nav-item option">
                        <?= $course_management_link ?>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

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
    <div class="container" style="margin-top: 200px">
        <?=$content?>
    </div>
</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="../../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../../resource/package/js/bootstrap.js"></script>

<!--匯入所需JS-->
<script src="../../resource/js/student/student.js"></script>
<script src="../../resource/js/share.js"></script>

</body>
</html>