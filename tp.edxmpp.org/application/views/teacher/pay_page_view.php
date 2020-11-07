<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>工資管理</title>


    <!--  Package start  -->
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="resource/package/css/flag-icon.css"/>

    <!--  Package end  -->

    <!--  View start  -->
    <link rel="stylesheet" href="resource/css/share.css">
    <link rel="stylesheet" href="resource/css/home.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/teacher/pay_page.css">
    <!--  View end  -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-light header-shadow">
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

<div class="pay__container container">
    <div class="pay_information_area">
        <img class="message_reminder_icon" src="resource/pics/teacher/pay_page/message_reminder_icon.png" alt="">
        <div class="dialog_box">
            <h3 class="title">工資訊息</h3>
            <span class="bot"></span>
            <span class="top"></span>
            老師您好，本平台領取工資必須在100美金以上，並在每個月自動匯款。<br>
            ※另外提醒老師，本平台將會依照每堂課程酌收 <?=$drawInto?>% 的管理費 ! <br>【以下工資為扣除管理費後的金額】 ※
        </div>
    </div>

    <p class="have_pay">我目前的工資為&nbsp;<span id="have_pay_text"><?=$salary?></span>&nbsp;美元 (100 美元即可填寫下方資訊後領取)</p>

    <!--    換行線-->
    <div class="line_break"></div>

    <div class="remittance_information_area">
        <span class="title">匯款資料</span>

        <div class="mt-3 data_area">
            <span>銀行/郵局名稱: </span>
            <input type="text" class="form-control" id="remittance_name" placeholder="鳳山分行" autocomplete="off" required value="<?=@$salaryData->name?>">
            <span>銀行/郵局代號: </span>
            <input type="text" class="form-control" id="remittance_code" placeholder="800" autocomplete="off" required value="<?=@$salaryData->code?>">
            <span>銀行/郵局帳號: </span>
            <input type="text" class="form-control" id="remittance_account" placeholder="0112051-2234521"
                   autocomplete="off" required value="<?=@$salaryData->account?>">
            <span>銀行/郵局戶名: </span>
            <input type="text" class="form-control" id="remittance_account_name" placeholder="王小民" autocomplete="off"
                   required value="<?=@$salaryData->accountName?>">

            <button type="button" class="btn btn-danger" onclick="sendRemittanceData()">
                更新匯款資料
            </button>
        </div>
    </div>
</div>
<!--<div class="pay__container container">-->
<!--    <div class="pay__box">-->
<!--        <span class="text-right">-->
<!--            <p class="h2">$100</p>-->
<!--        </span>-->
<!--        <div class="progress" id="payProgress">-->
<!--            <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>-->
<!--        </div>-->
<!---->
<!--        <div class="progress__data d-flex flex-wrap justify-content-between align-items-center px-5 my-3">-->
<!--            <div class="progress__data-percent col-sm mb-1">-->
<!--                <div class="percent__value">-->
<!--                    <p class="h5" id="percentValue">0%</p>-->
<!--                </div>-->
<!--                <div class="percent__title">-->
<!--                    <p class="h5">進度</p>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="progress__data-money col-sm">-->
<!--                <div class="money__value" >-->
<!--                    <p class="h5" id="moneyValue">$100</p>-->
<!--                </div>-->
<!--                <div class="money__title">-->
<!--                    <p class="h5">金額</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="progress__data-hours col-sm">-->
<!--                <div class="hours__value" >-->
<!--                    <p class="h5" id="hoursValue">2h</p>-->
<!--                </div>-->
<!--                <div class="hours__title">-->
<!--                    <p class="h5">教課時數</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="progress__data-sold col-sm">-->
<!--                <div class="sold__value">-->
<!--                    <p class="h5" id="soldValue">24</p>-->
<!--                </div>-->
<!--                <div class="sold__title">-->
<!--                    <p class="h5">售出影片課程</p>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <span class="h6">※收入達到100美後才可領取</span>-->
<!--    </div>-->
<!--</div>-->


<!-- Scripts -->
<!-- Package -->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>

<!-- View -->
<script src="resource/js/share.js"></script>
<script src="resource/js/teacher/pay_page.js"></script>


</body>
</html>