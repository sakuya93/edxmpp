<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>後台-老師審核詳細資訊</title>
    <link rel="stylesheet" href="../resource/package/css/bootstrap.css"/>
    <link rel="stylesheet" href="../resource/css/share.css">
    <link rel="stylesheet" href="../resource/css/window.css">
    <link rel="stylesheet" href="../resource/css/admin/teacherCheck_detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<header>

</header>

<main>
    <div class="container mbr-3 teacherCheck_body">
        <div class="input-title col-sm-12 ft-24">基本資料</div>

        <div class="mls-15">
            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">教師頭像</div>
                <img class="ml-10 mug_shot" src="../resource/image/student/photo/<?= $photo ?>">
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">姓名</div>
                <input type="text" class="form-control" value="<?= $name ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">國籍</div>
                <input type="text" class="form-control" value="<?= $country ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">會說語言</div>
                <input type="text" class="form-control" value="<?= $speakLanguage ?>" readonly>
            </div>
        </div>
    </div> <!-- 基本資料 -->

    <div class="container mbr-3 teacherCheck_body">
        <div class="input-title col-sm-12 ft-24">老師介紹</div>
        <div class="mls-15">
            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">極短描述</div>
                <input type="text" class="form-control" value="<?= $veryShort_des ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">簡短介紹</div>
                <input type="text" class="form-control" value="<?= $short_des ?>" readonly>
            </div>

            <div class="input-group col-sm-6 mb-3">
                <div class="input-title col-sm-12">詳細介紹</div>
                <input type="text" class="form-control" value="<?= $des ?>" readonly>
            </div>
        </div>
    </div> <!-- 老師介紹 -->

    <div class="container mbr-3 teacherCheck_body">
        <div class="input-title col-sm-12 ft-24">工作經驗</div>
        <?= $work_content ?>
    </div> <!-- 工作經驗 -->

    <div class="container mbr-3 teacherCheck_body">
        <div class="input-title col-sm-12 ft-24">學歷背景</div>

        <?= $education_content ?>
    </div> <!-- 學歷背景 -->

    <div class="container mbr-3 teacherCheck_body">
        <div class="input-title col-sm-12 ft-24">教學證照</div>

        <div class="mls-15">
            <div class="input-group col-sm-12 mb-3">
                <div class="input-title col-sm-12">證明文件</div>

                <?= $teaching_content ?>
            </div>
        </div>
    </div> <!-- 教學證照 -->

    <div class="footer">
        <button type="button" class="btn btn-warning" onclick="setDesignatedAdministrator('<?=$id?>')">指定為前台管理員</button>
        <button type="button" class="btn btn-primary" onclick="checkPass('<?=$id?>')">批准老師身分</button>
        <button type="button" class="btn btn-secondary" onclick="logoutTeacherIdentity('<?=$id?>')">註銷老師身分</button>
        <button type="button" class="btn btn-danger" onclick="banBecomeTeacher('<?=$id?>')">禁止成為老師</button>
        <button type="button" class="btn btn-success" onclick="cancelBanBecomeTeacher('<?=$id?>')">解除黑名單</button>
    </div>
</main>

<!-- Scripts -->
<!--匯入套件-->
<script src="../resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="../resource/package/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!--匯入所需JS-->
<script src="../resource/js/admin/teacherCheck_detail.js"></script>


</body>
</html>