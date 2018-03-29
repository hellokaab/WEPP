<!DOCTYPE html>
<html lang="en" ng-app = "myApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>รายละเอียดข้อสอบ  - Software for worksheet and examination programming practice</title>
    <link rel="icon" href="{!! asset('img/rmuti.ico') !!}"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="waitMe/waitMe.css " rel="stylesheet" type="text/css">
    <link href="LineControl-Editor/editor.css " rel="stylesheet" type="text/css">
    <link href="css/myCustom.css" rel="stylesheet" type="text/css">
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/angular.min.js"></script>
    <script src="app/app.js"></script>
    <script src="waitMe/waitMe.js"></script>
    <script src="js/ajaxCtrl.js"></script>
    <script src="js/dirPagination.js"></script>
    <script src="LineControl-Editor/editor.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script>
        var url = '{{ URL::asset('') }}';
    </script>
</head>
<body id="page-top" class="index" >
<script src="js/Components/detailExamCtrl.js"></script>
<script>
    var examID = {{$examID}};
</script>
<div ng-controller="detailExamCtrl" style="display: none" id="detail_exam_div">
    <br>
    <div class="col-md-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title" style="color: #fff">รายละเอียดข้อสอบ</h3>
            </div>
            <div class="panel-body">
                <h4 class="text-center" id="examName"></h4>
                <br>
                <div class="col-md-3"><b>Time limit:</b> <span id="examTimeLimit"></span> Seconds</div>
                <div class="col-md-3"><b>Memory limit:</b> <span id="examMemLimit"></span> KB</div>
                <div class="col-md-12">
                    <br>
                    <b>โจทย์:</b>
                    <div id="exam_content"></div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <br>
                            <b>อินพุท:</b>
                            <div id="input_part">
                                <textarea class="form-control code_textarea" style="background-color: #fff"
                                          id="examInput" rows="10" disabled></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <b>เอาท์พุท:</b>
                            <div id="output_part">
                                <textarea class="form-control code_textarea" style="background-color: #fff"
                                          id="examOutput" rows="10" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <b>เกณฑ์การให้คะแนน:</b>
                                    <div class="list-group">
                                        <a class="list-group-item">
                                            <span class="badge badge-default" id="fullScore">100</span>
                                            - คะแนนเต็ม
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <b>เกณฑ์การหักคะแนน:</b>
                                    <div class="list-group">
                                        <a class="list-group-item">
                                            <span class="badge badge-default" id="cutWrongAnswer">10</span>
                                            - คำตอบผิดพลาด
                                        </a>
                                        <a class="list-group-item">
                                            <span class="badge badge-default" id="cutComplieError">10</span>
                                            - รูปแบบโค้ดไม่ถูกต้อง
                                        </a>
                                        <a class="list-group-item">
                                            <span class="badge badge-default" id="cutOverMem">10</span>
                                            - หน่วยความจำเกิน
                                        </a>
                                        <a class="list-group-item">
                                            <span class="badge badge-default" id="cutOverTime">10</span>
                                            - เวลาประมวณผลเกิน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <br>
                            <b>คีย์เวิร์ด:</b>
                            <br>
                            <br>
                            <ul id="list_keyword">
                                <li ng-repeat="k in keywords"><%k.keyword_data%></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#detail_exam_div').css('display', 'block');
    });
</script>
</body>
</html>