<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>後台-會員管理詳細資訊</title>
    <link rel="stylesheet" href="../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../resource/css/share.css">
    <link rel="stylesheet" href="../resource/css/window.css">
    <link rel="stylesheet" href="../resource/css/admin/member_management_detail.css">
    <link href="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.bootcss.com/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css"
          rel="stylesheet">
</head>
<body>

<header>

</header>

<main>
    <div class="container mbr-3 member_management_body">
        <div class="input-title col-sm-12 ft-24">基本資料</div>

        <div class="mls-15">
            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">會員頭像</div>
                <img class="ml-10 mug_shot" src="../resource/image/student/photo/<?= $photo ?>">
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">姓名</div>
                <input type="text" class="form-control" value="<?= $name ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">生日</div>
                <input type="text" class="form-control" value="<?= $date ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">國籍</div>
                <input type="text" class="form-control" value="<?= $country ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">母語</div>
                <input type="text" class="form-control" value="<?= $motherTongue ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">信箱</div>
                <input type="text" class="form-control" value="<?= $email ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">城市</div>
                <input type="text" class="form-control" value="<?= $city ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">時區</div>
                <input type="text" class="form-control" value="<?= $timezone ?>" readonly>
            </div>
            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">擁有鑽石</div>
                <input type="text" class="form-control" value="<?= $points ?>" readonly>
            </div>
        </div>
    </div> <!-- 基本資料 -->

    <h3 class="ml-3 text-primary">此學生所有購買的課程及付款狀況</h3>
    <table
            id="table"
            data-toolbar="#toolbar"
            data-search="true"
            data-show-refresh="true"
            data-show-toggle="true"
            data-show-fullscreen="false"
            data-show-columns="true"
            data-detail-view="false"
            data-show-export="true"
            data-click-to-select="true"
            data-detail-formatter="detailFormatter"
            data-minimum-count-columns="2"
            data-show-pagination-switch="false"
            data-pagination="true"
            data-id-field="id"
            data-page-size="10"
            data-page-list="[10, 25, 50, 100, all]"
            data-show-footer="false"
            data-url="../member_management_detail_fun/getOwnCourse/<?= $m_id ?>"
            data-response-handler="responseHandler">
    </table>

    <div class="footer">
        <input type='checkbox' class="member_status" id="member_status" <?= $memberStatus ?>>
    </div>
</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

<!--  BOOTSTRAP TABLE  -->
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/extensions/export/bootstrap-table-export.min.js"></script>

<!--匯入所需JS-->
<script src="../resource/js/admin/member_management_detail.js"></script>

<script>
    var m_id = '<?= $m_id ?>';
</script>

</body>
</html>