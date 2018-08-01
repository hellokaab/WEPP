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

$sql = "SELECT sheetings.id as sheeting_id,sheetings.sheeting_name,sumSheetingScore.sum_trial_score,sumSheetingScore.sum_quizz_score
        FROM sheetings
        INNER JOIN (
            SELECT sheet_sheetings.sheeting_id,SUM(sheetScore.full_score) as sum_trial_score,SUM(sheetScore.sum_quizz_score) as sum_quizz_score
            FROM sheet_sheetings
            INNER JOIN (
                SELECT sheets.id,sheets.full_score,quizzScore.sum_quizz_score
                FROM sheets 
                LEFT JOIN (  
                    SELECT quizzes.sheet_id,SUM(quizzes.quiz_score) AS sum_quizz_score
                    FROM quizzes
                    GROUP BY quizzes.sheet_id) as quizzScore 
                ON sheets.id =   quizzScore.sheet_id) as sheetScore
             ON sheet_sheetings.sheet_id = sheetScore.id
             GROUP BY sheet_sheetings.sheeting_id) as sumSheetingScore
        ON sheetings.id = sumSheetingScore.sheeting_id
        WHERE sheetings.group_id = '$group_id'
        ORDER BY sheetings.id";
$listSheeting = mysqli_query($con, $sql);

$sql = "SELECT COUNT(a.sheeting_name) AS count_sheeting FROM (SELECT sheetings.sheeting_name FROM sheetings WHERE sheetings.group_id = '$group_id') AS a";
$countSheeting = mysqli_fetch_array(mysqli_query($con, $sql),MYSQLI_ASSOC);

$countSheet = $countSheeting['count_sheeting'];
$allSheetingID = array();
$sumTrialScoreSheeting = array();
$sumQuizzScoreSheeting = array();

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
                <th colspan="<?= $countSheet*2?>">
                    การสั่งงาน
                </th>
            </tr>
            <tr>
                <th rowspan="2">ลำดับ</th>
                <th rowspan="2">รหัสนักศึกษา</th>
                <th rowspan="2">ชื่อ - นามสกุล</th>
            <?php while($sheeting = mysqli_fetch_array($listSheeting,MYSQLI_ASSOC)){ ?>
                <th colspan="2">
                    <?=  $sheeting["sheeting_name"]?>
                </th>
            <?php
                array_push($allSheetingID,$sheeting["sheeting_id"]);
                array_push($sumTrialScoreSheeting,is_null($sheeting["sum_trial_score"]) ? "0" : $sheeting["sum_trial_score"]);
                array_push($sumQuizzScoreSheeting,is_null($sheeting["sum_quizz_score"])? "0.00" : $sheeting["sum_quizz_score"]);
            } ?>
            </tr>
            <tr>
                <?php
                for ($index = 0; $index < $countSheet; $index++) {
                    ?>
                    <th>
                        คะแนนรวมใบงาน <br>(<?= $sumTrialScoreSheeting[$index] ?> คะแนน)
                    </th>
                    <th>
                        คะแนนรวมคำถาม <br>(<?= $sumQuizzScoreSheeting[$index] ?> คะแนน)
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
                for ($index = 0; $index < $countSheet; $index++) {
                    $sql = "SELECT *
                            FROM (
                                SELECT join_groups.user_id,sheetings.id as sheeting_id 
                                FROM join_groups
                                INNER JOIN sheetings
                                ON join_groups.group_id = sheetings.group_id
                                WHERE join_groups.user_id = ".$member["user_id"]."
                                AND join_groups.group_id = ".$group_id."
                                AND sheetings.id = ".$allSheetingID[$index].") as A
                            LEFT JOIN (
                                SELECT * FROM
                                (SELECT res_sheets.sheeting_id,res_sheets.user_id,SUM(res_sheets.score) AS sum_res_sheet_score,SUM(sumQuizzScore.sum_quizz_score) AS sum_res_quizz_score
                                FROM res_sheets
                                LEFT JOIN (
                                    SELECT res_quizzes.res_sheet_id,SUM(res_quizzes.score) as sum_quizz_score
                                    FROM res_quizzes
                                    GROUP BY res_quizzes.res_sheet_id) as sumQuizzScore
                                ON res_sheets.id = sumQuizzScore.res_sheet_id
                                GROUP BY res_sheets.sheeting_id,res_sheets.user_id) AS a
                                WHERE a.user_id = ".$member["user_id"]."
                                AND a.sheeting_id = ".$allSheetingID[$index]." ) as B
                            ON A.sheeting_id = B.sheeting_id";
                    $data = mysqli_fetch_array(mysqli_query($con, $sql),MYSQLI_ASSOC);?>
                    <td style="text-align: center">
                        <?= is_null($data["sum_res_sheet_score"]) ? "0.00" : $data["sum_res_sheet_score"] ?>
                    </td>
                    <td style="text-align: center">
                        <?= is_null($data["sum_res_quizz_score"]) ? "0.00" : $data["sum_res_quizz_score"] ?>
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
