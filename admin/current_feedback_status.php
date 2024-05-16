<?php
ob_start();
session_start();
/* Include the database configuration file */
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
global $connPDO;
/*This function will check the session*/
checkSession();
if(isset($_POST['submit']))
{
	#echo "<pre>";print_r($_POST);die;
	$year			=	$_POST['year'];
	$semester		=	$_POST['semester'];
	$deptId			=	$_POST['department'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Feedback Status</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../student/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="icon" href="images/favi_logo.gif"/>
	<!-- Spry Stuff Starts here-->
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
	<style>
	h1,h2,h3,h4,h5,h6
	{
		color		:	white;
	}
	</style>
</head>

<body onload = "plotSubjectByDept(<?php echo $subjectId;?>)">
<div style = 'float:right'>
	<p class='welcome' align='left'>Welcome 
		<strong><?php echo $_SESSION['u_utype'];?></strong><br/>
		<a href='change_password.php'>Change Password</a> | <a href='logout.php'>Logout</a>
	</p>
</div>
<?php
	
	echo plotLogoWithAddress();
?>
<!-- Report Menu Starts Here-->
<div>
	<table id='listEntries' width="50%" border="1" cellpadding="0" cellspacing="0" 
		style="border-collapse:collapse;" bordercolor="#CCCCCC">
		<tr bgcolor="#FFFFCC">
			<th	align='center' colspan ='2'>Current</th>
			<th align='center' colspan ='2'>History</th>
			<th align='center'>Status</th>
		</tr>
		<tr>	
			<td align="center" >
				<a href="current_class_wise.php">Class Wise</a>
			</td>
			<td align="center" >
				<a href="current_free_answer.php">Student Free Answer</a>
			</td>
			
			<td align="center">
				<a href="history_class_wise.php" >Class Wise</a>
			</td>
			<td align="center" >
				<a href="history_free_answer.php">Student Free Answer</a>
			</td>
			
			<td align="center" >	
				<a href="current_feedback_status.php" class='activelink' >Feedback Status</a>
			</td>
		</tr>
	</table>
</div>
<!--Report Menu Ends Here-->
<br/>
<form method = 'post' action="<?php echo $_SERVER['PHP_SELF'];?>">
<table id = "listEntries" width = "100%" border="1">
<tr>
		<td height = '30'>
			<strong>Year</strong>
			<span class = 'mandatory'>*</span>
		</td>
		<td height = '30'>
				<?php
					$year	=	isset($year)?$year:'';
					echo plotYearDropdown($year);
				?>
			
		</td><!-- Year ends here-->
		
		<td height = '30'>
			<strong>Semester</strong>
			<span class = 'mandatory'>*</span>
		</td>
		<td height = '30'>
				<?php
					$semester	=	isset($semester)?$semester:'';
					echo plotSemesterDropdown($semester);
				?>
			
		</td><!-- Semester Ends here-->
		
		<td>
			<strong>Department</strong>
			<span class = 'mandatory'>*</span>
		</td>
		<td height = '30'>
			<?php
					$deptId	=	isset($deptId)?$deptId:'';
					//var_dump($deptId);
					echo plotDepartmentDropdown($deptSelVal = $deptId,$ajaxEnabled='no',$subjectAjaxEnabled='yes');
			?>
		</td><!-- Department Ends here-->
		<td>
			<input type = 'submit' name = 'submit' class = 'button' value = 'Search Status' />
		</td>
</tr>
</table>
</form>
<?php
if(isset($_POST['submit']))
{
	//echo "<pre>";print_r($_POST);//die;
	$year		=	trim($_POST['year']);
	$semester	=	trim($_POST['semester']);
	$deptId		=	trim($_POST['department']);

	/*Total Number Of Students Registered - Starts Here*/	
	$totalStudentsRegisterQry 		=	"SELECT COUNT( * ) AS total
										FROM  `tbl_users` 
										WHERE  `u_utype` LIKE  'student'
										AND  `stud_year` LIKE  '{$year}'
										AND  `stud_dept` LIKE  '{$semester}'
										AND  `stud_sem` LIKE  '{$deptId}'";

	$totalStudentsRegisterRes		=	$connPDO->query($totalStudentsRegisterQry);
	$totalStudentsRegisterNumRows	=	$totalStudentsRegisterRes->rowCount();
	if($totalStudentsRegisterNumRows)
	{
		$totalStudentsRegisterArr	=	$totalStudentsRegisterRes->fetch(PDO::FETCH_ASSOC);
		$totalStudentsRegister		=	$totalStudentsRegisterArr['total'];
	}
	/*Total Number Of Students Registered - Ends Here*/
	
	
	/*Total Number Of Voted Students - Starts Here*/
	$totalStudentsVotedQry 		=		"SELECT * 
												FROM  `tbl_feedback` 
												INNER JOIN  `tbl_users` ON  
												`tbl_feedback`.`feedback_stud_id` =  `tbl_users`.`u_id` 
												WHERE  `feedback_dept_id` = {$deptId}
												AND  `feedback_year` LIKE  '{$year}'
												AND  `feedback_semester` LIKE  '{$semester}'
												GROUP BY  `tbl_feedback`.`feedback_stud_id`";

	$totalStudentsVotedRes		=	$connPDO->query($totalStudentsVotedQry);
	$totalStudentsVotedNumRows	=	$totalStudentsVotedRes->rowCount();
	$totalStudentsVoted			=	$totalStudentsVotedNumRows;
	/*Total Number Of Voted Students - Ends Here*/
	$totalPendingStudents		=	($totalStudentsRegister >= $totalStudentsVoted)
									?($totalStudentsRegister-$totalStudentsVoted):'';
	/*Total Pending Students to Vote*/
		
	
	/*Total Pending Students to Vote*/
	
	/*Diplay Result Starts Here*/
	if(isset($totalStudentsRegister) && isset($totalStudentsVoted))
	{
		echo <<<ABC
		<div id = 'myDiv1'>
			<h1>Feedback Status</h1>
			<div class='dashed_border'></div>
				<h2>Total Registered Students --> $totalStudentsRegister</h2>
				<h2>Total Voted Students	  --> $totalStudentsVoted</h2>
				<h2>Total Pending Students	  --> $totalPendingStudents</h2>
				
		</div><!--myDiv Ends Here-->
ABC;
	}	
	/*Display Result Ends Here*/
}
?>	
<script type="text/javascript">
	var year    	 = new Spry.Widget.ValidationSelect("year", {validateOn:["change"]});						
	var semester     = new Spry.Widget.ValidationSelect("semester", {validateOn:["change"]});						
	var department   = new Spry.Widget.ValidationSelect("department", {validateOn:["change"]});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>