<script>
    $(document).ready(function () {
        var x = screen.width;
        if (parseInt(x) >= 768) {
            $("#sidebar-wrapper,#menu-toggle").css('display','block');
        } else {
            $("#sidebar-wrapper,#menu-toggle").css('display','none');
            $("#wrapper").removeAttr('class','toggled');
            $("#page-content-wrapper").removeAttr('style');
        }

        var today = new Date();
//        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var options = { year: 'numeric', month: 'long', day: 'numeric' };

        $("#a_today").html("วันที่ "+today.toLocaleDateString('th-TH', options));
    });

    $(window).resize(function () {
        var x = screen.width;
        if (parseInt(x) >= 768) {
            $("#sidebar-wrapper,#menu-toggle").css('display','block');
        } else {
            $("#sidebar-wrapper,#menu-toggle").css('display','none');
            $("#wrapper").removeAttr('class','toggled');
            $("#page-content-wrapper").removeAttr('style');
        }
    });

    $(function() {
        var nowDateTime=new Date("<?=date("m/d/Y H:i:s")?>");
        var d=nowDateTime.getTime();
        var mkHour,mkMinute,mkSecond;
        setInterval(function(){
            d=parseInt(d)+1000;
            var nowDateTime=new Date(d);
            mkHour=new String(nowDateTime.getHours());
            if(mkHour.length==1){
                mkHour="0"+mkHour;
            }
            mkMinute=new String(nowDateTime.getMinutes());
            if(mkMinute.length==1){
                mkMinute="0"+mkMinute;
            }
            mkSecond=new String(nowDateTime.getSeconds());
            if(mkSecond.length==1){
                mkSecond="0"+mkSecond;
            }
            var runDateTime=mkHour+":"+mkMinute+":"+mkSecond;
            $("#css_time_run").html(runDateTime);
        },1000);

    });
    app.controller("sideNavCtrl", function($scope) {
        $scope.user = user;
    });


</script>
<div id="wrapper" class="toggled" ng-controller="sideNavCtrl">
    <!-- Sidebar -->
    <div id="sidebar-wrapper" style="display: none">
        <ul class="sidebar-nav">
            <li class="sidebar-brand" style="margin-left: 20px">
                <a href="#" id="a_today" >
                    วันที่ 10 มิถุนายน 2560
                </a>
            </li>
            <li class="sidebar-brand2" >
                <a href="#" style="line-height:0%;margin-left: 60px;font-size: 18px">
                    <div id="css_time_run">
                        <?=date("H:i:s")?>
                    </div>
                </a>
            </li>
            <li1 ng-show="user.user_type === 't' || user.user_type === 's' || user.user_type === 'o'">
                <a id="side_home" href="{{ url('/home') }}"><i class="fa2 fa-home fa-lg" aria-hidden="true" style="color: #db2828"></i>&nbsp;&nbsp;หน้าหลัก</a>
            </li1>
            <li2 ng-show="user.user_type === 't'">
                <a data-target="#demo_exam" data-toggle="collapse" role="presentation" id="side_exam_store" href="" class="collapsed">
                    <i class="fa2 fa-database fa-lg" aria-hidden="true" style="color: #f2711c"></i>&nbsp;&nbsp;คลังข้อสอบ<i id="exam_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 118px--}}
                </a>
            </li2>
            <div class="collapse" id="demo_exam">
                <ul class="list-unstyled main-menu" id="_menu_exam" z="user-managed=">
                    <li2 role="presentation">
                        <a id="side_my_exam" href="{{ url('/teacher-exam-my')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;ข้อสอบของฉัน</a>
                        <a id="side_shared_exam" href="{{ url('/teacher-exam-share')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;ข้อสอบที่แบ่งปันกับฉัน</a>
                    </li2>
                </ul>
            </div>
            <li3 ng-show="user.user_type === 't'">
                <a data-target="#demo_sheet" data-toggle="collapse" role="presentation" id="side_sheet_store" href="" class="collapsed">
                    <i class="fa2 fa-archive fa-lg" aria-hidden="true" style="color: #fbbd08"></i>&nbsp;&nbsp;คลังใบงาน <i id="sheet_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 117px--}}
                </a>
            </li3>
            <div class="collapse" id="demo_sheet">
                <ul class="list-unstyled main-menu" id="_menu_sheet" z="user-managed=">
                    <li3 role="presentation">
                        <a id="side_my_sheet" href="{{ url('/teacher-sheet-my')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;ใบงานของฉัน</a>
                        <a id="side_shared_sheet" href="{{ url('/teacher-sheet-share') }}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;ใบงานที่แบ่งปันกับฉัน</a>
                    </li3>
                </ul>
            </div>
            <li4 ng-show="user.user_type === 't'">
                <a data-target="#demo_group" data-toggle="collapse" role="presentation" id="side_group" href="" class="collapsed">
                    <i class="fa2 fa-users fa-lg" aria-hidden="true" style="color: #21ba45"></i>&nbsp;&nbsp;กลุ่มเรียน<i id="group_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 128px--}}
                </a>
            </li4>
            <div class="collapse" id="demo_group">
                <ul class="list-unstyled main-menu" id="_menu_group" z="user-managed=">
                    <li4 role="presentation">
                        <a id="side_my_group" href="{{ url('/teacher-group-my') }}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;กลุ่มเรียนของฉัน</a>
                        <a id="side_all_group" href="{{ url('/teacher-group-all') }}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;กลุ่มเรียนทั้งหมด</a>
                        <a id="side_join_group" href="{{ url('/teacher-group-join') }}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;กลุ่มเรียนที่ฉันเข้าร่วม</a>
                    </li4>
                </ul>
            </div>
            <li5 ng-show="user.user_type === 't'">
                <a data-target="#demo_examing" data-toggle="collapse" role="presentation" id="side_examing" href="" class="collapsed">
                    <i class="fa2 fa-cog fa-lg" aria-hidden="true" style="color: #2185d0"></i>&nbsp;&nbsp;จัดการการสอบ<i id="examing_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 100px--}}
                </a>
            </li5>
            <div class="collapse" id="demo_examing">
                <ul class="list-unstyled main-menu" id="_menu_examing" z="user-managed=">
                    <li5 role="presentation">
                        <a id="side_open_examing" href="{{ url('/teacher-examing-add')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;เปิดสอบ</a>
                        <a id="side_history_examing" href="{{ url('/teacher-examing-history')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;ประวัติการเปิดสอบ</a>
                    </li5>
                </ul>
            </div>
            <li6 ng-show="user.user_type === 't'">
                <a data-target="#demo_sheeting" data-toggle="collapse" role="presentation" id="side_sheeting" href="" class="collapsed">
                    <i class="fa2 fa-cogs fa-lg" aria-hidden="true" style="color: #6435c9"></i>&nbsp;&nbsp;จัดการการสั่งงาน<i id="sheeting_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 85px--}}
                </a>
            </li6>
            <div class="collapse" id="demo_sheeting">
                <ul class="list-unstyled main-menu" id="_menu_sheeting" z="user-managed=">
                    <li6 role="presentation">
                        <a id="side_open_sheeting" href="{{ url('/teacher-sheeting-add')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;สั่งงาน</a>
                        <a id="side_history_sheeting" href="{{ url('/teacher-sheeting-history')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;ประวัติการสั่งงาน</a>
                    </li6>
                </ul>
            </div>
            <li3 ng-show="user.user_type === 's' || user.user_type === 'o'">
                <a data-target="#demo_std_group" data-toggle="collapse" role="presentation" id="side_std_group" href="" class="collapsed">
                    <i class="fa2 fa-users fa-lg" aria-hidden="true" style="color: #fbbd08"></i>&nbsp;&nbsp;กลุ่มเรียน<i id="std_group_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 129px--}}
                </a>
            </li3>
            <div class="collapse" id="demo_std_group">
                <ul class="list-unstyled main-menu" id="_menu_std_group" z="user-managed=">
                    <li3 role="presentation">
                        <a id="side_std_allGroup" href="{{ url('/student-group-all')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;กลุ่มเรียนทั้งหมด</a>
                        <a id="side_std_myGroup" href="{{ url('/student-group-my')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;กลุ่มเรียนของฉัน</a>
                    </li3>
                </ul>
            </div>
            <li2 ng-show="user.user_type === 'a'">
                <a id="side_teaList" href="{{ url('/admin-list-teacher') }}"><i class="fa2 fa-users fa-lg" aria-hidden="true" style="color: #f2711c"></i>&nbsp;&nbsp;รายชื่ออาจารย์ในระบบ</a>
            </li2>
            <li3 ng-show="user.user_type === 'a'">
                <a id="side_pnsList" href="{{ url('/admin-list-personnel') }}"><i class="fa2 fa-users fa-lg" aria-hidden="true" style="color: #fbbd08"></i>&nbsp;&nbsp;รายชื่อเจ้าหน้าที่ในระบบ</a>
            </li3>
            <li4 ng-show="user.user_type === 'a'">
                <a id="side_stdList" href="{{ url('/admin-list-student') }}"><i class="fa2 fa-users fa-lg" aria-hidden="true" style="color: #21ba45"></i>&nbsp;&nbsp;รายชื่อนักศึกษาในระบบ</a>
            </li4>
            <li7 ng-show="user.user_type === 't'">
                <a data-target="#demo_manual_teacher" data-toggle="collapse" role="presentation" id="side_manual_teacher" href="" class="collapsed">
                    <i class="fa2 fa-book fa-lg" aria-hidden="true" style="color: #653e17"></i>&nbsp;&nbsp;คู่มือการใช้งาน<i id="manual_teacher_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 85px--}}
                </a>
            </li7>
            <div class="collapse" id="demo_manual_teacher">
                <ul class="list-unstyled main-menu" id="_menu_manual_teacher" z="user-managed=">
                    <li7 role="presentation">
                        <a id="side_manual_teacher" href="{{ url('/user-manual-teacher')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;คู่มือสำหรับอาจารย์</a>
                    </li7>
                </ul>
            </div>
            <li5 ng-show="user.user_type === 's' || user.user_type === 'o'">
                <a data-target="#demo_manual_student" data-toggle="collapse" role="presentation" id="side_manual_student" href="" class="collapsed">
                    <i class="fa2 fa-book fa-lg" aria-hidden="true" style="color: #2185d0"></i>&nbsp;&nbsp;คู่มือการใช้งาน<i id="manual_student_chevron" class="fa2 fa-chevron-left" style="float: right;padding-top: 13px;padding-right: 10px"></i> {{--padding-left: 85px--}}
                </a>
            </li5>
            <div class="collapse" id="demo_manual_student">
                <ul class="list-unstyled main-menu" id="_menu_manual_student" z="user-managed=">
                    <li5 role="presentation">
                        <a ng-show="user.user_type === 'o'" id="side_manual_other" href="{{ url('/user-manual-other')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;คู่มือสำหรับเจ้าหน้าที่</a>
                        <a ng-show="user.user_type === 's'" id="side_manual_student" href="{{ url('/user-manual-student')}}">&nbsp;&#09;&nbsp;&#09;&nbsp;&#09;&nbsp;คู่มือสำหรับนักศึกษา</a>
                    </li5>
                </ul>
            </div>
            {{--<li4 ng-show="user.user_type === 's'">--}}
                {{--<a target="_blank" rel="noopener noreferrer" href="https://goo.gl/forms/UyNHJuP53cm6cvvb2"><i class="fa2 fa-comments fa-lg" aria-hidden="true" style="color: #21ba45"></i>&nbsp;&nbsp;แบบประเมินความพึงพอใจ</a>--}}
            {{--</li4>--}}
            {{--<li4 ng-show="user.user_type === 't'">--}}
                {{--<a target="_blank" rel="noopener noreferrer" href="https://goo.gl/forms/asckiNmphYOJjGmh2"><i class="fa2 fa-comments fa-lg" aria-hidden="true" style="color: #21ba45"></i>&nbsp;&nbsp;แบบประเมินความพึงพอใจ</a>--}}
            {{--</li4>--}}
        </ul>
    </div>

    <div class="mini-submenu" href="#menu-toggle" id="menu-toggle">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" style="margin-left: 20px">
        <div class="container-fluid">
            <div class="row">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->
</div>
<script>
    $("#side_exam_store").on('click',function () {
        if($("#side_exam_store")[0].className == "collapsed"){
            $("#exam_chevron").removeAttr('class');
            $("#exam_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_exam_store").attr('class','active');
        } else {
            $("#side_exam_store").removeAttr('class');
            $("#exam_chevron").removeAttr('class');
            $("#exam_chevron").attr('class','fa2 fa-chevron-left');

        }
    });

    $("#side_sheet_store").on('click',function () {
        if($("#side_sheet_store")[0].className == "collapsed"){
            $("#sheet_chevron").removeAttr('class');
            $("#sheet_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_sheet_store").attr('class','active');
        } else {
            $("#side_sheet_store").removeAttr('class');
            $("#sheet_chevron").removeAttr('class');
            $("#sheet_chevron").attr('class','fa2 fa-chevron-left');

        }
    });

    $("#side_group").on('click',function () {
        if($("#side_group")[0].className == "collapsed"){
            $("#group_chevron").removeAttr('class');
            $("#group_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_group").attr('class','active');
        } else {
            $("#side_group").removeAttr('class');
            $("#group_chevron").removeAttr('class');
            $("#group_chevron").attr('class','fa2 fa-chevron-left');

        }
    });

    $("#side_examing").on('click',function () {
        if($("#side_examing")[0].className == "collapsed"){
            $("#examing_chevron").removeAttr('class');
            $("#examing_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_examing").attr('class','active');
        } else {
            $("#side_examing").removeAttr('class');
            $("#examing_chevron").removeAttr('class');
            $("#examing_chevron").attr('class','fa2 fa-chevron-left');

        }
    });

    $("#side_sheeting").on('click',function () {
        if($("#side_sheeting")[0].className == "collapsed"){
            $("#sheeting_chevron").removeAttr('class');
            $("#sheeting_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_sheeting").attr('class','active');
        } else {
            $("#side_sheeting").removeAttr('class');
            $("#sheeting_chevron").removeAttr('class');
            $("#sheeting_chevron").attr('class','fa2 fa-chevron-left');

        }
    });

    $("#side_std_group").on('click',function () {
        if($("#side_std_group")[0].className == "collapsed"){
            $("#std_group_chevron").removeAttr('class');
            $("#std_group_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_std_group").attr('class','active');
        } else {
            $("#side_std_group").removeAttr('class');
            $("#std_group_chevron").removeAttr('class');
            $("#std_group_chevron").attr('class','fa2 fa-chevron-left');

        }
    });

    $("#side_manual_teacher").on('click',function () {
        if($("#side_manual_teacher")[0].className == "collapsed"){
            $("#manual_teacher_chevron").removeAttr('class');
            $("#manual_teacher_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_manual_teacher").attr('class','active');
        } else {
            $("#side_manual_teacher").removeAttr('class');
            $("#manual_teacher_chevron").removeAttr('class');
            $("#manual_teacher_chevron").attr('class','fa2 fa-chevron-left');

        }
    });

    $("#side_manual_student").on('click',function () {
        if($("#side_manual_student")[0].className == "collapsed"){
            $("#manual_student_chevron").removeAttr('class');
            $("#manual_student_chevron").attr('class','fa2 fa-chevron-down');
            $("#side_manual_student").attr('class','active');
        } else {
            $("#side_manual_student").removeAttr('class');
            $("#manual_student_chevron").removeAttr('class');
            $("#manual_student_chevron").attr('class','fa2 fa-chevron-left');

        }
    });
</script>