<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>後台登入</title>
    <link rel="stylesheet" href="resource/package/css/bootstrap.css"/>
    <link href="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="resource/css/share.css"/>
    <link rel="stylesheet" href="resource/css/window.css">
    <link rel="stylesheet" href="resource/css/admin/signIn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>


<div class="container h-100">
    <div class="d-flex justify-content-center h-100">
        <div class="user_card">
            <div class="d-flex justify-content-center">
                <div class="brand_logo_container">
                    <img src="resource/pics/admin/adminLogo.png" class="brand_logo" alt="Logo">
                </div>
            </div>
            <div class="d-flex justify-content-center form_container">
                <form id="signIn_form">
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" name="" class="form-control input_user signIn-data" value="" placeholder="username">
                    </div>
                    <div class="input-group mb-2">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                        </div>
                        <input type="password" name="" class="form-control input_pass signIn-data" value="" placeholder="password">
                    </div>
<!--                    <div class="form-group">-->
<!--                        <div class="custom-control custom-checkbox">-->
<!--                            <input type="checkbox" class="custom-control-input" id="customControlInline">-->
<!--                            <label class="custom-control-label" for="customControlInline">Remember me</label>-->
<!--                        </div>-->
<!--                    </div>-->
                </form>
            </div>
            <div class="d-flex justify-content-center mt-3 login_container">
                <button type="submit" name="button" class="btn login_btn" onclick="signIn_sendOut(document.getElementsByClassName('signIn-data')); return false;">Login</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<!--匯入套件-->
<script src="resource/package/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="resource/package/js/bootstrap.js"></script>

<!--  BOOTSTRAP TABLE  -->
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.15.4/dist/extensions/export/bootstrap-table-export.min.js"></script>


<!--匯入所需JS-->
<script src="resource/js/admin/signIn.js"></script>

</body>
</html>