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
    <title>รายละเอียดใบงาน  - Software for worksheet and examination programming practice</title>
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
<script src="js/Components/detailSheetCtrl.js"></script>
<script>
    var sheetID = {{$sheetID}};
</script>
<div ng-controller="detailSheetCtrl" style="display: none" id="detail_sheet_div">
    <br>
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title" style="color: #fff">รายละเอียดใบงาน</h3>
            </div>
            <div class="panel-body">
                <h4 class="text-center" id="sheetName"></h4>
                <br>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <br>
                            <b>วัตถุประสงค์</b>
                            <div id="objective_part">
                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                      id="objective" rows="5" disabled></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <b>ทฤษฎีที่เกี่ยวข้อง</b>
                            <div id="theory_part">
                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                      id="theory" rows="5" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    <b>การทดลอง</b>
                    <div id="sheet_trial"></div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <br>
                            <b>Input</b>
                            <div id="input_part">
                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                      id="sheetInput" rows="10" disabled></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <b>Output</b>
                            <div id="output_part">
                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                      id="sheetOutput" rows="10" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="list-group">
                                        <a class="list-group-item">
                                            <span class="badge badge-default" id="fullScore">100</span>
                                            คะแนนเต็มของใบงาน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <div class="row">
                                <div class="col-md-12"><b>หมายเหตุ  </b></div>
                                <div class="col-md-6"><span id="notation"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <h4 style="padding-left: 15px" ng-show="quizzes.length > 0">คำถามท้ายการทดลอง</h4>
                <div class="col-md-6" ng-repeat="q in quizzes" style="margin-top: 20px">
                    <div class="row">
                        <div class="col-md-12">
                            <b>คำถามข้อที่ <%$index+1%></b>
                            <div>
                                            <textarea class="form-control code_textarea" style="background-color: #fff"
                                                      id="quiz_1" rows="3" disabled><%q.quiz_data%></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <b>คำตอบ </b>
                            <div>
                                <input type="text" class="form-control" style="background-color: #fff"
                                       id="answer_1" value="<%q.quiz_ans%>" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <b>คะแนน </b>
                            <div>
                                <input type="text" class="form-control" style="background-color: #fff"
                                       id="score_1" value="<%q.quiz_score%>" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#detail_sheet_div').css('display', 'block');
    });
</script>
</body>
</html>