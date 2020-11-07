<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>首頁</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/css/home.css"/>
    <link rel="stylesheet" href="resource/css/share.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <a class="navbar-brand home-header-logo-block" href="student"><img class="header-logo"
                                                                           src="resource/pics/share/logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"> <!--  想學什麼語言? 搜尋  -->
                    <div class="search_area">
                        <input type="text" id="search_text" autocomplete="off" placeholder="探索課程"
                               class="el-input__inner">
                        <i class="fa fa-search" onclick="search_course('')"></i>
                    </div>
                </li>
            </ul>

            <!--            <ul class="navbar-nav mr-auto">-->
            <!--                <li class="nav-item"> <!--  想學什麼語言? 搜尋 (下拉式)  -->
            <!--                    <div class="search dropdown">-->
            <!--                        <div class="quick-search" data-toggle="dropdown">-->
            <!--                            <input type="text" readonly="readonly" autocomplete="off" placeholder="探索課程"-->
            <!--                                   class="el-input__inner">-->
            <!--                            <button><i class="fa fa-search"></i></button>-->
            <!--                        </div>-->
            <!--                        --><? //= $classOption ?>
            <!--                    </div>-->
            <!--                </li>-->
            <!--            </ul>-->

            <ul class="navbar-nav row home-right-item-block ">
                <li class="nav-item dropdown option">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $Language ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                        <a class="dropdown-item" href="#""><?= $Search_Language[0] ?></a>
                        <a class="dropdown-item" href="#"><?= $Search_Language[1] ?></a>
                    </div>
                </li>
                <!--                <li class="nav-item dropdown option">-->
                <!--                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"-->
                <!--                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                <!--                        TWD-->
                <!--                    </a>-->
                <!--                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
                <!--                        <a class="dropdown-item active" href="#">TWD</a>-->
                <!--                        <a class="dropdown-item" href="#">USD</a>-->
                <!--                    </div>-->
                <!--                </li>-->
                <!--目前時間-->
                <li class="nav-item option">
                    <div class="nav-link" id="showbox"></div>
                </li>

                <li class="nav-item option">
                    <a class="nav-link" href="#"><?= $Common_problem ?></a>
                </li>
                <li class="nav-item option" data-toggle="modal"
                    data-target="#signIn_window">
                    <a class="nav-link" href="#"><?= $Sign_in ?></a>
                </li>
                <li class="nav-item option" data-toggle="modal"
                    data-target="#registered_window">
                    <a class="nav-link" href="#"><?= $registered ?></a>
                </li>
            </ul>
        </div>
    </nav>
</header>


<section id="page-hero" class="hero is-fullheight justify-content-center align-items-center bg-attachment bg-cover">
    <div class="hero-container">
        <div class="text-white">
            <div class="hero-title">線上教學平台</div>
            <h1>學你想學的</h1>
            <h2>學習到屬於自己的技能</h2>
        </div>
    </div>
</section>

<div class="container py-5 classification__container">
    <div class="section-title">
        <h2>分類彙整</h2>
        <p>來自不同地區及各行各業的老師，所開的專業課程</p>
    </div>

    <div class="row justify-content-center">
        <div class="btn-group btn-group-toggle" data-toggle="buttons" id="btnClassification">
            <label class="btn btn-secondary active">
                <input type="radio" name="btnClassification__option" id="option1" value="live" onchange="getClassificationData($(this).val())" checked> 直播
            </label>
            <label class="btn btn-secondary">
                <input type="radio" name="btnClassification__option" id="option2" value="film" onchange="getClassificationData($(this).val())"> 影片
            </label>
        </div>
    </div>

    <div id="carouselExampleControls" class="carousel slide my-3" data-ride="carousel">
        <div class="carousel-inner" id="courseCarousels">

        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <i class="fas fa-caret-left" aria-hidden="true"></i>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <i class="fas fa-caret-right" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
        </a>
    </div>

</div>

<footer>
    <!-- 頁尾 start -->
    <div class="max-width-800 about" data-v-4e5639f4="" style="margin-top: 50px">
        <div class="row about">
            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4="">全民學習平台</h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class=""><?= $About_us ?></a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4=""><?= $Blog ?></a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4=""><?= $Media ?></a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4
                        data-v-4e5639f4=""><?= $Career_Opportunities ?></h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class=""><?= $Join_the_XXX_teaching_platform ?></a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4=""><?= $Become_a_teacher ?></a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4=""><?= $Looking_for_a_student ?></a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4=""><?= $Learn ?></h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class=""><?= $Looking_for_a_teacher ?></a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4=""><?= $Looking_for_a_course ?></a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4=""><?= $Student_evaluation ?></a>
                    </li>
                </ul>
            </div>

            <div class="col-md-3 webmap-list" data-v-4e5639f4=""><h4 data-v-4e5639f4=""><?= $Customer_support ?></h4>
                <ul class="webmap-list_body" data-v-4e5639f4="">
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" data-v-4e5639f4="" class=""><?= $Common_problem ?></a>
                    </li>
                    <li class="webmap-list_item" data-v-4e5639f4="">
                        <a href="" target="_blank" data-v-4e5639f4=""><?= $System_update ?></a>
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
                <a href="#"><?= $Sitemap ?></a>・<a href="#"><?= $Terms_of_Service_Privacy_Statement ?></a>&nbsp;&nbsp;&nbsp;
                <a href="#"><i class="fa fa-facebook-square"></i>&nbsp;</a>
                <a href="#"><i class="fa fa-instagram"></i>&nbsp;</a>
                <a href="#"><i class="fa fa-twitter"></i>&nbsp;</a>
            </div>

        </div>
    </footer>
    <!-- 頁尾 end -->

    </main>

    <!-- Scripts -->
    <!--匯入套件-->
    <script src="resource/package/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="resource/package/js/bootstrap.js"></script>

    <!--匯入所需JS-->
    <script src="resource/js/home/account.js"></script>
    <script src="resource/js/home/recommend.js"></script>
    <script src="resource/js/home/home.js"></script>
    <script src="resource/js/share.js"></script>

    <!-- 載入提示視窗 -->
    <script>
        $(document).ready(function () {
            if (location.href.toString().indexOf("?m=") != -1) {
                var sign_error_hint_text = <?php echo json_encode(@$blockingReason->blockingReason)?>;

                if (sign_error_hint_text == null) {
                    $('#hint_text').text("此帳號已被封鎖，如有疑慮請聯絡課服");
                }
                else {
                    $('#hint_text').text(sign_error_hint_text);
                }

                $('#hint_window').modal();

                $('#hint_window').on('hide.bs.modal', function () {
                    window.location = "https://ajcode.tk/teaching_platform_dev/home";
                });
            }

            if(location.pathname.indexOf("/student") >= 0 || location.pathname.indexOf("/home") >= 0 ){
                getClassificationData("live");
            }
        });
    </script>

</body>
</html>