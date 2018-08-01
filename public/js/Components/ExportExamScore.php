<?php
$group_id = $_GET['group_id'];
$con = @mysqli_connect('localhost', 'root', '', 'wepp2');
if (!$con) {
    echo "Error: " . mysqli_connect_error();
    exit();
}
mysqli_set_charset($con, "utf8");

$sql = "SELECT groups.group_name FROM groups WHERE groups.id = '$group_id'";
$group = mysqli_fetch_array(mysqli_query($con, $sql),MYSQLI_ASSOC);

$sql = "SELECT examScore.examing_id,examings.examing_name,examScore.sum_exam_score
        FROM examings
        INNER JOIN (
            SELECT exam_examings.examing_id,SUM(exams.full_score) AS sum_exam_score
            FROM exam_examings
            INNER JOIN exams
            ON exam_examings.exam_id = exams.id
            GROUP BY exam_examings.examing_id) AS examScore
        ON examings.id = examScore.examing_id
        WHERE examings.group_id = '$group_id'
        ORDER BY examings.id";
$listExaming = mysqli_query($con, $sql);

$sql = "SELECT COUNT(a.examing_name) AS count_examing FROM (SELECT examings.examing_name FROM examings WHERE examings.group_id = '$group_id' ) AS a";
$countExaming = mysqli_fetch_array(mysqli_query($con, $sql),MYSQLI_ASSOC);

$countExam = $countExaming['count_examing'];
$allExamingID = array();
$sumScoreExaming = array();
?>
<?php
//header("Content-Type: application/vnd.ms-excel");
//header('Content-Disposition: attachment; filename="คะแนนใบงานกลุ่มเรียน ' . $group['group_name'] . '.xls"'); #ชื่อไฟล์
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<HTML>
    <HEAD>
        <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    </HEAD>
    <BODY>
        <TABLE  x:str BORDER="1">
            <tr>
                <th colspan="3">
                    <?= 'กลุ่มเรียน ' . $group['group_name'] ?>
                </th>
                <th colspan="<?= $countExam ?>">
                    การสอบ
                </th>
            </tr>
            <tr>
                <th rowspan="2">ลำดับ</th>
                <th rowspan="2">รหัสนักศึกษา</th>
                <th rowspan="2">ชื่อ - นามสกุล</th>
                <?php while($examing = mysqli_fetch_array($listExaming,MYSQLI_ASSOC)){ ?>
                <th>
                    <?=  $examing["examing_name"]?>
                </th>
                    <?php
                    array_push($allExamingID,$examing["examing_id"]);
                    array_push($sumScoreExaming,is_null($examing["sum_exam_score"]) ? "0" : $examing["sum_exam_score"]);
                } ?>
            </tr>
            <tr>
                <?php
                for ($index = 0; $index < $countExam; $index++) {
                    ?>
                    <th>
                        คะแนนรวมข้อสอบ <br>(<?= $sumScoreExaming[$index] ?> คะแนน)
                    </th>
                    <?php
                }
                ?>
            </tr>
            <?php
            $sql = "SELECT join_groups.user_id,users.stu_id,users.prefix,users.fname_th,users.lname_th
                    FROM join_groups
                    INNER JOIN users ON join_groups.user_id = users.id
                    WHERE join_groups.status = 's'
                    AND join_groups.group_id = '$group_id'";
            $allMember = mysqli_query($con, $sql);
            $count = 1;
            while($member = mysqli_fetch_array($allMember,MYSQLI_ASSOC)){?>
            <tr>
                <td style="text-align: center">
                    <?= $count++ ?>
                </td>
                <td>
                    <?= $member["stu_id"] ?>
                </td>
                <td>
                    <?= $member["prefix"].$member["fname_th"]." ".$member["lname_th"] ?>
                </td>
                <?php
                for ($index = 0; $index < $countExam; $index++) {
                    $sql = "SELECT *
                            FROM (
                                SELECT join_groups.user_id,examings.id as examing_id 
                                FROM join_groups
                                INNER JOIN examings
                                ON join_groups.group_id = examings.group_id
                                WHERE join_groups.user_id = ".$member["user_id"]."
                                AND join_groups.group_id = ".$group_id."
                                AND examings.id = ".$allExamingID[$index].") AS A
                            LEFT JOIN (
                                SELECT res_exams.examing_id,SUM(res_exams.score) AS sum_res_exam_score
                                FROM res_exams
                                WHERE res_exams.user_id = ".$member["user_id"]."
                                AND res_exams.examing_id = ".$allExamingID[$index]."
                                GROUP BY res_exams.examing_id) AS B
                            ON A.examing_id = B.examing_id";
                    $data = mysqli_fetch_array(mysqli_query($con, $sql),MYSQLI_ASSOC);?>
                    <td style="text-align: center">
                        <?= is_null($data["sum_res_exam_score"]) ? "0.00" : $data["sum_res_exam_score"] ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            }
            ?>
        </TABLE>
    </BODY>
</HTML>
<?php mysqli_close($con); ?>
