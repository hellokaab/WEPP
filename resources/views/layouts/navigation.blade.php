<!-- Navigation -->
<script xmlns:php="http://www.w3.org/1999/html">


    $(document).ready(function () {
        var x = screen.width;
        if (parseInt(x) >= 768) {
            document.getElementById("projectName").innerHTML = "ระบบใบงานและการสอบเขียนโปรแกรมคอมพิวเตอร์ภาคปฏิบัติ";
            $("#for-full-screen").css('display','block');
            $("#nav_admin_logout,#nav_tea_list,#nav_std_list,#nav_home,#nav_user_logout").css('display','none');
            $("#nav_group,#nav_profile,#nav_exam,#nav_examing,#nav_sheet,#nav_sheeting").css('display','none');
            $("#nav_manual_teacher,#nav_manual_student,#nav_manual_other").css('display','none');
        } else {
            document.getElementById("projectName").innerHTML = "WEPP";
            $("#for-full-screen").css('display','none');
            $("#nav_admin_logout,#nav_tea_list,#nav_std_list,#nav_home,#nav_user_logout").css('display','block');
            $("#nav_group,#nav_profile,#nav_exam,#nav_examing,#nav_sheet,#nav_sheeting").css('display','block');
            $("#nav_manual_teacher,#nav_manual_student,#nav_manual_other").css('display','block');
        }
        document.getElementById('nameUser').innerHTML = name;
    });

    $(window).resize(function () {
        var x = screen.width;
        if (parseInt(x) >= 768) {
            document.getElementById("projectName").innerHTML = "ระบบใบงานและการสอบเขียนโปรแกรมคอมพิวเตอร์ภาคปฏิบัติ";
            $("#for-full-screen").css('display','block');
            $("#nav_admin_logout,#nav_tea_list,#nav_std_list,#nav_home,#nav_user_logout").css('display','none');
            $("#nav_group,#nav_profile,#nav_exam,#nav_examing,#nav_sheet,#nav_sheeting").css('display','none');
            $("#nav_manual_teacher,#nav_manual_student,#nav_manual_other").css('display','none');
        } else {
            document.getElementById("projectName").innerHTML = "WEPP";
            $("#for-full-screen").css('display','none');
            $("#nav_admin_logout,#nav_tea_list,#nav_std_list,#nav_home,#nav_user_logout").css('display','block');
            $("#nav_group,#nav_profile,#nav_exam,#nav_examing,#nav_sheet,#nav_sheeting").css('display','block');
            $("#nav_manual_teacher,#nav_manual_student,#nav_manual_other").css('display','block');
        }
        document.getElementById('nameUser').innerHTML = name;
    });

    app.controller("navBarCtrl", function($scope) {
        $scope.user = user;

        $scope.adminLogOut = function () {
            $.ajax ({
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                headers: {
                    Accept: "application/json"
                },
                url: url + 'user-logout-admin',
                async: false,
                complete: function (xhr) {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            window.location.href = url;
                        }else {
                            alert('ผิดพลาด');
                        }
                    }
                }
            });
        }

        $scope.userLogOut = function () {
//            ------------- For Product -------------
//            window.location.href = "http://it.ea.rmuti.ac.th/wepp/sso/?slo&redirect=http://it.ea.rmuti.ac.th/wepp";

//            ------------- For Dev -------------
            $.ajax ({
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                headers: {
                    Accept: "application/json"
                },
                url: url + 'user-logout-user',
                async: false,
                complete: function (xhr) {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            window.location.href = url;
                        }else {
                            alert('ผิดพลาด');
                        }
                    }
                }
            });
        }
    });
</script>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" ng-controller="navBarCtrl">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header page-scroll">
        <button type="button" class="navbar-toggle" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand page-scroll" href="#page-top" id="projectName"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right" style="margin-right: auto">
            <li class="hidden">
                <a href="#page-top"></a>
            </li>
            <li id="nav_home" ng-show="user.user_type === 't' || user.user_type === 's' || user.user_type === 'o'" style="display: none">
                <a  href="{{ url('/home')}}"><i class="fa2 fa-home fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;หน้าหลัก</a>
            </li>
            <li id="nav_profile" ng-show="user.user_type != 'a'" style="display: none">
                <a href="{{ url('/profile')}}"><i class="fa2 fa-address-card fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;ข้อมูลส่วนตัว</a>
            </li>
            <li id="nav_exam" ng-show="user.user_type === 't'" style="display: none">
                <a href="" data-target="#nav_memu_exam" data-toggle="collapse" role="presentation" class="collapsed">
                    <i class="fa2 fa-database fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;คลังข้อสอบ
                    <i id="nav_exam_chevron" class="fa2 fa-chevron-left" style="float: right"></i>
                </a>
            </li>
            <div class="collapse" id="nav_memu_exam">
                <ul class="nav navbar-nav navbar-right" style="margin-right: auto">
                    <li role="presentation">
                        <a href="{{ url('/teacher-exam-my')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;ข้อสอบของฉัน</a>
                    </li>
                    <li role="presentation">
                        <a href="{{ url('/teacher-exam-share')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;ข้อสอบที่แบ่งปันกับฉัน</a>
                    </li>
                </ul>
            </div>
            <li id="nav_sheet" ng-show="user.user_type === 't'" style="display: none">
                <a href="" data-target="#nav_memu_sheet" data-toggle="collapse" role="presentation" class="collapsed">
                    <i class="fa2 fa-archive fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;คลังใบงาน
                    <i id="nav_sheet_chevron" class="fa2 fa-chevron-left" style="float: right"></i>
                </a>
            </li>
            <div class="collapse" id="nav_memu_sheet">
                <ul class="nav navbar-nav navbar-right" style="margin-right: auto">
                    <li role="presentation">
                        <a href="{{ url('/teacher-sheet-my')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;ใบของฉัน</a>
                    </li>
                    <li role="presentation">
                        <a href="{{ url('/teacher-sheet-share')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;ใบงานที่แบ่งปันกับฉัน</a>
                    </li>
                </ul>
            </div>
            <li id="nav_group" ng-show="user.user_type === 't' || user.user_type === 's' || user.user_type === 'o'" style="display: none">
                <a href="" data-target="#nav_memu_group" data-toggle="collapse" role="presentation" class="collapsed">
                    <i class="fa2 fa-users fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;กลุ่มเรียน
                    <i id="nav_group_chevron" class="fa2 fa-chevron-left" style="float: right"></i>
                </a>
            </li>
            <div class="collapse" id="nav_memu_group">
                <ul class="nav navbar-nav navbar-right" style="margin-right: auto">
                    <li role="presentation" ng-show="user.user_type === 't'">
                        <a href="{{ url('/teacher-group-my')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;กลุ่มเรียนของฉัน</a>
                    </li>
                    <li role="presentation" ng-show="user.user_type === 't'">
                        <a href="{{ url('/teacher-group-all')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;กลุ่มเรียนทั้งหมด</a>
                    </li>
                    <li role="presentation" ng-show="user.user_type === 't'">
                        <a href="{{ url('/teacher-group-join')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;กลุ่มเรียนที่ฉันเข้าร่วม</a>
                    </li>
                    <li role="presentation" ng-show="user.user_type === 's' || user.user_type === 'o'">
                        <a href="{{ url('/student-group-all')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;กลุ่มเรียนทั้งหมด</a>
                    </li>
                    <li role="presentation" ng-show="user.user_type === 's' || user.user_type === 'o'">
                        <a href="{{ url('/student-group-my')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;กลุ่มเรียนของฉัน</a>
                    </li>
                </ul>
            </div>
            <li id="nav_examing" ng-show="user.user_type === 't'" style="display: none">
                <a href="" data-target="#nav_memu_examing" data-toggle="collapse" role="presentation" class="collapsed">
                    <i class="fa2 fa-cog fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;จัดการการสอบ
                    <i id="nav_examing_chevron" class="fa2 fa-chevron-left" style="float: right"></i>
                </a>
            </li>
            <div class="collapse" id="nav_memu_examing">
                <ul class="nav navbar-nav navbar-right" style="margin-right: auto">
                    <li role="presentation">
                        <a href="{{ url('/teacher-examing-add')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;เปิดสอบ</a>
                    </li>
                    <li role="presentation">
                        <a href="{{ url('/teacher-examing-history')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;ประวัติการเปิดสอบ</a>
                    </li>
                </ul>
            </div>
            <li id="nav_sheeting" ng-show="user.user_type === 't'" style="display: none">
                <a href="" data-target="#nav_memu_sheeting" data-toggle="collapse" role="presentation" class="collapsed">
                    <i class="fa2 fa-cogs fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;จัดการการสั่งงาน
                    <i id="nav_sheeting_chevron" class="fa2 fa-chevron-left" style="float: right"></i>
                </a>
            </li>
            <div class="collapse" id="nav_memu_sheeting">
                <ul class="nav navbar-nav navbar-right" style="margin-right: auto">
                    <li role="presentation">
                        <a href="{{ url('/teacher-sheeting-add')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;สั่งงาน</a>
                    </li>
                    <li role="presentation">
                        <a href="{{ url('/teacher-sheeting-history')}}">&emsp;&emsp;&nbsp;&nbsp;&nbsp;ประวัติการสั่งงาน</a>
                    </li>
                </ul>
            </div>
            <li id="nav_tea_list" ng-show="user.user_type === 'a'" style="display: none">
                <a href="{{ url('/admin-list-teacher') }}"><i class="fa2 fa-users fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;รายชื่ออาจารย์ในระบบ</a>
            </li>
            <li id="nav_tea_list" ng-show="user.user_type === 'a'" style="display: none">
                <a href="{{ url('/admin-list-personnel') }}"><i class="fa2 fa-users fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;รายชื่อเจ้าหน้าที่ในระบบ</a>
            </li>
            <li id="nav_std_list" ng-show="user.user_type === 'a'" style="display: none">
                <a href="{{ url('/admin-list-student') }}"><i class="fa2 fa-users fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;รายชื่อนักศึกษาในระบบ</a>
            </li>
            <li id="nav_manual_teacher" ng-show="user.user_type === 't'" style="display: none">
                <a href="{{ url('/user-manual-teacher') }}"><i class="fa2 fa-book fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;คู่มือการใช้งาน</a>
            </li>
            <li id="nav_manual_student" ng-show="user.user_type === 's'" style="display: none">
                <a href="{{ url('/user-manual-student') }}"><i class="fa2 fa-book fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;คู่มือการใช้งาน</a>
            </li>
            <li id="nav_manual_other" ng-show="user.user_type === 'o'" style="display: none">
                <a href="{{ url('/user-manual-other') }}"><i class="fa2 fa-book fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;คู่มือการใช้งาน</a>
            </li>
            <li id="nav_admin_logout" ng-show="user.user_type === 'a'" style="display: none">
                <a href="#" ng-click="adminLogOut()"><i class="fa2 fa-sign-out fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;ออกจากระบบ</a>
            </li>
            <li id="nav_user_logout" ng-show="user.user_type === 't' || user.user_type === 's' || user.user_type === 'o'" style="display: none">
                <a href="#" ng-click="userLogOut()"><i class="fa2 fa-sign-out fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;ออกจากระบบ</a>
            </li>
            <li role="presentation" class="dropdown" id="for-full-screen" style="display: none">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"
                                                                 aria-hidden="true"></i>&nbsp;&nbsp;<label id="nameUser">testt
                    </label>&nbsp;&nbsp;<span
                            class="caret"></span>
                </a>
                <ul class="dropdown-menu" style="padding-top: 15px;padding-bottom: 15px">
                    <li ng-show="user.user_type != 'a'">
                        <a href="{{ url('/profile') }}" style="padding-top: 5px;padding-bottom: 5px;text-align: left">
                            <i class="fa fa-address-card fa-lg"
                               aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;ข้อมูลส่วนตัว
                        </a>
                    </li>
                    <li ng-show="user.user_type != 'a'">
                        <a href="#" style="padding-top: 5px;padding-bottom: 5px;text-align: left" ng-click="userLogOut()">
                            <i class="fa fa-sign-out fa-lg"
                               aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;ออกจากระบบ
                        </a>
                    </li>
                    <li ng-show="user.user_type === 'a'">
                        <a href="#" style="padding-top: 5px;padding-bottom: 5px;text-align: left" ng-click="adminLogOut()">
                            <i class="fa fa-sign-out fa-lg"
                               aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;ออกจากระบบ
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <script>
        $("#nav_exam").on('click',function () {
            if($("#nav_memu_exam")[0].className == "collapse"){
                $("#nav_exam_chevron").removeAttr('class');
                $("#nav_exam_chevron").attr('class','fa2 fa-chevron-down');
            } else {
                $("#nav_exam_chevron").removeAttr('class');
                $("#nav_exam_chevron").attr('class','fa2 fa-chevron-left');
            }
        });

        $("#nav_sheet").on('click',function () {
            if($("#nav_memu_sheet")[0].className == "collapse"){
                $("#nav_sheet_chevron").removeAttr('class');
                $("#nav_sheet_chevron").attr('class','fa2 fa-chevron-down');
            } else {
                $("#nav_sheet_chevron").removeAttr('class');
                $("#nav_sheet_chevron").attr('class','fa2 fa-chevron-left');
            }
        });

        $("#nav_group").on('click',function () {
            if($("#nav_memu_group")[0].className == "collapse"){
                $("#nav_group_chevron").removeAttr('class');
                $("#nav_group_chevron").attr('class','fa2 fa-chevron-down');
            } else {
                $("#nav_group_chevron").removeAttr('class');
                $("#nav_group_chevron").attr('class','fa2 fa-chevron-left');
            }
        });

        $("#nav_examing").on('click',function () {
            if($("#nav_memu_examing")[0].className == "collapse"){
                $("#nav_examing_chevron").removeAttr('class');
                $("#nav_examing_chevron").attr('class','fa2 fa-chevron-down');
            } else {
                $("#nav_examing_chevron").removeAttr('class');
                $("#nav_examing_chevron").attr('class','fa2 fa-chevron-left');
            }
        });

        $("#nav_sheeting").on('click',function () {
            if($("#nav_memu_sheeting")[0].className == "collapse"){
                $("#nav_sheeting_chevron").removeAttr('class');
                $("#nav_sheeting_chevron").attr('class','fa2 fa-chevron-down');
            } else {
                $("#nav_sheeting_chevron").removeAttr('class');
                $("#nav_sheeting_chevron").attr('class','fa2 fa-chevron-left');
            }
        });
    </script>
</nav>
