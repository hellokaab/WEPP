<?php
    session_start();
    $user = array(
        "personalId" => "0425361073012",
        "prename" => "นาย",
        "cn" => "Sukjai",
        "firstNameThai" => "สุขใจ",
        "sn" => "Sabeydee",
        "lastNameThai" => "สบายดี",
        "studentId" => "",
        "faculty" => "คณะวิศวกรรมศาสตร์และสถาปัตยกรรมศาสตร์",
        "program" => "สาขาวิชาวิศวกรรมคอมพิวเตอร์",
        "mail" => "pongpan.kho@rmuti.ac.th",
        "gidNumber" => "2500"
    );

//    $user = array(
//        "personalId" => "0425361073013",
//        "prename" => "นาย",
//        "cn" => "Sodchuen",
//        "firstNameThai" => "สดชื่นนนน",
//        "sn" => "Ruenrom",
//        "lastNameThai" => "รื่นรมย์",
//        "studentId" => "",
//        "faculty" => "คณะวิศวกรรมศาสตร์และสถาปัตยกรรมศาสตร์",
//        "program" => "สาขาวิชาวิศวกรรมคอมพิวเตอร์",
//        "mail" => "pongpan.kho@rmuti.ac.th",
//        "gidNumber" => "2500"
//    );

//    $user = array(
//        "personalId" => "0425361073014",
//        "prename" => "นาย",
//        "cn" => "Sawatdee",
//        "firstNameThai" => "สวัสดี",
//        "sn" => "Meesuk",
//        "lastNameThai" => "มีสุข",
//        "studentId" => "",
//        "faculty" => "คณะวิศวกรรมศาสตร์และสถาปัตยกรรมศาสตร์",
//        "program" => "สาขาวิชาวิศวกรรมคอมพิวเตอร์",
//        "mail" => "pongpan.kho@rmuti.ac.th",
//        "gidNumber" => "2500"
//    );

//    $user = array(
//        "personalId" => "1103701635240",
//        "prename" => "นาย",
//        "cn" => "Pongpan",
//        "firstNameThai" => "พงศ์พันธ์",
//        "sn" => "Poonkhunthod",
//        "lastNameThai" => "ปูนขุนทด",
//        "studentId" => "561733022010-0",
//        "faculty" => "คณะวิศวกรรมศาสตร์และสถาปัตยกรรมศาสตร์",
//        "program" => "สาขาวิชาวิศวกรรมคอมพิวเตอร์",
//        "mail" => "pongpan.kho@rmuti.ac.th",
//        "gidNumber" => "4500"
//    );

//    $user = array(
//        "personalId" => "1234567890123",
//        "prename" => "นาย",
//        "cn" => "Thanakorn",
//        "firstNameThai" => "ธนกร",
//        "sn" => "Chuensabai",
//        "lastNameThai" => "ชื่นสบาย",
//        "studentId" => "561733022033-2",
//        "faculty" => "คณะวิศวกรรมศาสตร์และสถาปัตยกรรมศาสตร์",
//        "program" => "สาขาวิชาวิศวกรรมคอมพิวเตอร์",
//        "mail" => "pongpan.kho@rmuti.ac.th",
//        "gidNumber" => "4500"
//    );

    $_SESSION['ssoUserData'] = $user;
    header( "location: http://localhost/WEPP2/public/user-login-user" );
    exit(0);
?>