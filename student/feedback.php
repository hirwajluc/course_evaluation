<?php
ob_start();
session_start();
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/* Include the default function file */
require_once("../admin/includes/functions.php");
/* This function will check the session */
//checkStudentSession();

$stuId = $_SESSION['u_id'];
$departmentId = $_SESSION['stud_dept'];
$year = $_SESSION['stud_year'];
$semester = $_SESSION['stud_sem'];
$group = $_SESSION['stud_group'];
//print "group is: ".$group."<br>";
//print "stud id is: ".$stuId."<br>";

$feedbackExistQry = "SELECT *  FROM `tbl_feedback` WHERE `feedback_stud_id` = {$stuId} GROUP BY `feedback_stud_id";


if (isset($_POST['btnSave'])) {

    foreach ($_POST as $x => $y) {
        if (trim($y) == '') {
            header('Location: feedback.php?id=' . $subjectId . '&gid=' . $group);
            die;
        }
    } $qualitiesArr = extractQualitiesFromPost($_POST);



    $stuId = $_SESSION['u_id'];
    $departmentId = $_SESSION['stud_dept'];
    $year = $_SESSION['stud_year'];
    $semester = $_SESSION['stud_sem']; //
    $group = $_SESSION['stud_group']; //

    /*$dateArr = getdate();
    $curAcaYear = $dateArr['year']; //*/

    $curAcaYear = $_POST['subject_ac_year']; //Blaise

    $subjectId = $_POST['subject_id'];
    $answerArr = $_POST['answer'];

    /* Comment Stuff Starts here */
    $commentsArr = addslashes($_POST['comment']);

  
  //  $curYear = date('Y');
    $i = 0;
    $teacher_id = $_POST['subject_teacher_id'];
    $group = $_POST['subject_group'];
    //$group = 2; // ????????????????????????????/
    $group = 3; // ????????????????????????????/ by Dieudonne
    saveFeedBack($curAcaYear, $departmentId, $year, $semester, $group, $subjectId, $stuId, $teacher_id, $qualitiesArr, $commentsArr);
   //saveFeedBack(2013, 3, 2, 1, 2, 33, 105, 35,array("_1"=> 1, "_2"=> 2), array("xxx", "yyy"));
 

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Course Feedback</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!--Link to the template css file-->
        <link rel="stylesheet" type="text/css" href="../admin/css/style.css" />
        <!--Link to Favicon -->
        <link rel="icon" href="../admin/images/favi_logo.gif"/>
        <link href="../admin/css/collapse.css" rel="stylesheet"/>
        <script type="text/javascript" src ="../js/jquery.js"></script>
        <script src="../admin/css/jquery.js"></script>
        <script src="../admin/css/collapse.js"></script>

        <script>
            function x() {
                var count = 0;
                var controls = document.forms[0].elements;
                for (var i = 0 ; i < controls.length; i ++) {
                    if (controls[i].checked) {
                        count ++;
                    }

                }
                if (count < 14){
                    alert ('College-QA: "Please, Fill All Entries"');
                    return false;
                }
                else {
                    if (controls.comment.value.trim() == ''){
                        //alert ('IPRC TUMBA-QA: "Please, Leave Comment"');//By Dieudonne
						alert ('College-QA: "Please, Fill the Comment in the provided field"');//By Nepo

                        return false;
                    }
                    alert ('College-QA: "Feedback Submitted"');
                    return true;
                }
            }
        </script>
        <?php
        $rchars = 200;
        ?>
        <script>
            function countRChars () {
<?php
        $rchars = 12;
?>
    }
        </script>
    </head>
    <body>

        <div class="main">
            <?php echo plotHeaderStudentInfo(basename(__FILE__)); ?>

        </div>


        <div class="body">
            <div class="main_body">
                <?php
                $sid = (int) $_GET['id'];
                $course = getSubjectDetails($sid);

                $str = listStudentSubjects($_SESSION['u_uname'], $_SESSION['acad_year']);
                if ($str == "" or $course == "") {
                    echo "<h3>Message from QA Office<h3>";
                    echo "<font size = -1>You have completed the course evaluation process.</font>";
                    echo "<p><font size = -1>Thank you for collaborating with us.</font></p>";
                    echo "<br /><p><font size = -1><strong>QA Office</strong></font></p>";
                        __($_SESSION['u_uname']);
                } else {
                    $ret = plotFeedBackFormx($sid, $group, $course['subject_teacher_id'], $_SESSION['u_uname'], $course['subject_ac_year']);
                    if ($ret == "") {
                        if (firstOnThePage ()) {
                            echo "<h3>Message from QA Office<h3>";
                            echo "<font size = -1>Thank you for taking your time to do the course evaluation. Your ratings and comments will be very helpful to instructors, lecturers and the college in general.</font>
                        <p><font size = -1>To start evaluation process, click on any course to evaluate on the left side menu below the  College Logo.</font></p>";
                            echo "<br /><p><font size = -1><strong>Quality Assurance Office</font></p>";
                        } else {
                            echo "<h3>Message from QA Office<h3>";
                            echo "<font size = -1>Choose an other course to evaluate.</font>";
                        }
                    } else {
                        echo "<h2>Course: {$course['subject_name']} ({$group})</h2>";
                        echo "<p style='color: #aa0000; font-weight: bold; font-size: 12px; font-family: monospace;'>Teacher: {$course['teacher_first_name']} {$course['teacher_last_name']}</p>";
                        echo $ret;
                    }
                }
                ?>
            </div>
<?php
                /* This function will return the logo div string to the sidebody */
                echo plotLogoDiv();
                $str = "<div class='side_body shadowEffect'><h2>My Subjects ... </h2>" . $str;
                echo $str;
?>
            </div>
            </div>
            <div class="clr"></div>
            <br/><br/>


<?php
                /* This function will return the footer div information */
                echo plotFooterDiv();
?>
<script>
$(document).ready(function(){
	$()
});
</script>
            </body>
        </html>
<?php
                ob_end_flush();
?>