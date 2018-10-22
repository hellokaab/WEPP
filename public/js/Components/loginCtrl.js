app.controller("loginCtrl", function($scope) {

    $scope.loginClick = function() {
        // ------------- For Product -------------
        // window.location.href = "http://it.ea.rmuti.ac.th/wepp/sso/?sso&redirect=http://it.ea.rmuti.ac.th/wepp/user-login-user";

        // ------------- For Dev -------------
        // if(checkAdmin() != 500){
        //     window.location.href = url + "makeData.php";
        // } else {
        //     $('#err_message').html('ขออภัย ขณะนี้กำลังปิดปรับปรุงระบบ');
        //     $('#fail_modal').modal('show');
        // }
        window.location.href = url + "makeData.php";

    };

    $scope.ShowDev = function () {
        $('#dev_modal').modal({backdrop: 'static'});
    };

    $scope.AdminLogin = function () {
        $('#admin_login_modal').modal({backdrop: 'static'});
        setTimeout(function () {
            $('[ng-model=adminUsername]').focus();
        }, 200);
        $scope.adminUsername = "";
        $scope.adminPassword = "";
    };

    $scope.okLogin = function () {
        $('#notice_admin_pass').hide();
        if($scope.adminUsername.length > 0 && $scope.adminPassword.length > 0){
            data = {
                username: $scope.adminUsername,
                password: $scope.adminPassword
            };
            $.ajax ({
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                headers: {
                    Accept: "application/json"
                },
                url: url + 'user-login-admin',
                data: data,
                async: false,
                complete: function (xhr) {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            window.location.href = url+"admin-list-teacher";
                            // alert("สำเร็จ");
                        } else if (xhr.status == 209){
                            $('#notice_admin_pass').html('* username หรือ password ไม่ถูกต้อง').show();
                            $('[ng-model=adminUsername]').focus();
                        } else {
                            alert("ผิดพลาด");
                        }
                    }
                }
            });
        } else  {
            $('#notice_admin_pass').html('* กรุณาระบุ username และ password').show();
            $('[ng-model=adminUsername]').focus();
        }
    }
});
