<?php
ob_start();
session_start();
/*Include the default function file*/
require_once("../admin/includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/*This function will check the session*/
checkStudentSession();
//echo '<pre>';print_r($_SESSION);die;
if(isset($_GET['msg']) && $_GET['msg']=='success')
{
	$succMsg			=	"Thank You For Your Feedback!";
}
$studId					=	$_SESSION['u_id'];
$uFName					=	$_SESSION['u_fname'];
$uLName					=	$_SESSION['u_lname'];
$departmentId			=	$_SESSION['stud_dept'];
$year					=	$_SESSION['stud_year'];
$semester				=	$_SESSION['stud_sem'];

/*Here We have checked whether the Questions from the subjects had preapared */
$subjListQry		=	"SELECT *  FROM `tbl_subject` WHERE `subject_year` 
						LIKE '{$year}' AND `subject_semester` = {$semester} AND 
						`subject_department_id`={$departmentId}";
$subjListRes		=	$connPDO->query($subjListQry);
$subjListNumRows	=	$subjListRes->rowCount();
if(!$subjListNumRows)
{
	$errorMsg			=	"Sorry!Questions Not Prepared For Your Subjects Yet!";
}
/*Here We have checked whether the Questions from the subjects had preapared */
else
{
	/*Here We have checked whether the feedback has already submitted before we leaving feedback page*/
	$feedbackExistQry		=	"SELECT *  FROM `tbl_feedback` WHERE `feedback_stud_id` = {$studId} 
								GROUP BY 	`feedback_stud_id";
	$existFlag				=	recordAlreadyExist($feedbackExistQry);
	if($existFlag)
	{
		$errorMsg			=	"Your Feedback already Exists!";
	}
	else
	{
		header("Location:feedback.php");
		exit;
	}
}	
/*Here We have checked whether the feedback has already submitted before we leaving feedback page*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Student Feedback</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<!--Link to Favicon -->
	<link rel="icon" href="../admin/images/favi_logo.gif"/>
	<style>
		h1,h2,h3,h4,h5,h6
		{
			color	:	#fff;
		}
	</style>
</head>
<body>
<div id = 'wrapper'>
	<div id='headerText'>
		<p class='welcome' align='right'>Welcome
			<strong><?php echo $uFName." ".$uLName?></strong><br/>
			<a href='change_password.php'>Change Password</a> | <a href='logout.php'>Logout</a>
		</p>
	</div>
	<div style = 'clear:both;'></div>
	<div id = 'myDiv'>
		<h1>TCT Course Evaluation News Board</h1>
		<div class='dashed_border'></div>
		<?php
			if(isset($succMsg))
			{
				echo "<h2>Dear Mr/Ms {$uFName} {$uLName}, <p style ='text-indent:25px;'> {$succMsg}</p></h2>";
			}
			elseif(isset($errorMsg))
			{
				echo "<h2>Dear Mr/Ms {$uFName} {$uLName}, <p style ='text-indent:25px;'> {$errorMsg}</p></h2>";
				
			}
		?>	
	</div><!--myDiv Ends Here-->
<div><!-- Wrapper Div Ends Here-->
</body>
</html>
<?php
	ob_end_flush();
?>