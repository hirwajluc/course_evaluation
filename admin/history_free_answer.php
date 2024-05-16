<?php
ob_start();
session_start();
/*Include the default function file*/
require_once("./includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
global $connPDO;
/*This function will check the session*/
checkSession();
if(isset($_POST['submit']))
{
		#echo '<pre>';print_r($_POST);die;
		$academicYear		=	$_POST['academic_year'];
		$year				=	$_POST['year'];
		$semester			=	$_POST['semester'];
		$deptCode			=	$_POST['dept_code'];
		$subjectId			=	$_POST['subject_id'];
}
$subjectId  = (isset($subjectId))?$subjectId:NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>History Free Answer</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../student/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="icon" href="images/favi_logo.gif"/>
	<!--link rel="shortcut icon" href="/mail/images/favicon.ico" type="image/x-icon"-->
	<!--Link to AJAX source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/ajax.js'></script>
	<!-- Spry Stuff Starts here-->
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
	<style>
	h1,h2,h3,h4,h5,h6
	{
		color		:	#000;
		text-align	:	center;
	}
	h2
	{
		margin		:	30px;	
	}
	h3
	{
		color		:	#E76800;
	}
	</style>
</head>
<body onload = "plotSubjectByDeptForHistory('<?php echo $subjectId;?>')">
<div style = 'float:right'>
	<p class='welcome' align='right'>Welcome 
		<strong><?php echo $_SESSION['u_fname']." ".$_SESSION['u_lname']?></strong><br/>
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
				<a href="history_class_wise.php">Class Wise</a>
			</td>
			<td align="center" >
				<a href="history_free_answer.php"   class='activelink'  >Student Free Answer</a>
			</td>
			
			<td align="center" >	
				<a href="current_feedback_status.php">Feedback Status</a>
			</td>
		</tr>
	</table>
</div>
<!--Report Menu Ends Here-->
<br/>
<form method = 'post' action="<?php echo $_SERVER['PHP_SELF'];?>">
<table id = "listEntries" width = "100%" border="1">
	<tr>
			<td>
				<strong>Academic Year</strong>
				<span class = 'mandatory'>*</span>
			</td>
			<td height = '30'>
				<?php
					$academicYear	=	isset($academicYear)?$academicYear:'';
					echo plotAcademicYear(2009,2030,$academicYear);
				?>
			</td>
			<td height = '30'>
				<strong>Year</strong>
				<span class = 'mandatory'>*</span>
			</td>
			<td height = '30'>
				<?php
					$year	=	isset($year)?$year:'';
					echo plotDropdownFromHistoryTable("SELECT `year` FROM classwise_history GROUP BY year" , 'year',$year);
				?>
			</td>
			<td height = '30'>
				<strong>Semester</strong>
				<span class = 'mandatory'>*</span>
			</td>
			<td height = '30'>
				<?php
					$semester	=	isset($semester)?$semester:'';
					echo plotDropdownFromHistoryTable("SELECT `semester` FROM classwise_history 
													   GROUP BY `semester`" , 'semester',$semester);
				?>
			</td>
			<td>
				<strong>Department</strong>
				<span class = 'mandatory'>*</span>
			</td>
			<td height = '30'>
				<?php
					$deptCode	=	isset($deptCode)?$deptCode:'';
					echo plotDropdownFromHistoryTable("SELECT `dept_code` FROM `classwise_history`
											GROUP BY `dept_code`" , 'dept_code',$deptCode,$subjectAjaxEnabled = 'yes');
				?>
			</td>
		<td>
			<strong>Subject</strong>
		</td>
		<td>
			<div id='subject_name_div'>
				<select name='subject_id' id='subject_id' class='typeproforms'>
					<option value=''>--select--</option>
				</select>
			</div>
		</td><!-- Subject Ends Here-->
		<td height = '30'>
			<input type = 'submit' name = 'submit' class = 'button' value = 'Search Feedback' />
		</td>
	</tr>
</table>
</form>
<?php
	if(isset($_POST['submit']))
	{
		#echo "<pre>";print_r($_POST);
		$academicYear		=	$_POST['academic_year'];
		$year				=	$_POST['year'];
		$semester			=	$_POST['semester'];
		$deptCode			=	$_POST['dept_code'];		
		$subjName			=	$_POST['subject_id'];		
		if($subjName == '')
		{
			echo "<p align='center'>";
			echo "<span class='error'>Please Choose Subject Name!</span>"	;
			echo "</p>";
		}
		else
		{
			/*List the Free Answer Starts here*/
			$freeAnswerQry		=	"SELECT comment_value 
									FROM  `tbl_comments` 
									WHERE  `academic_year` LIKE  '{$academicYear}'
									AND  `year` LIKE  '{$year}'
									AND  `semester` LIKE  '{$semester}'
									AND  `department` LIKE  '{$deptCode}'
									AND  `subject` LIKE  '{$subjName}'
									AND `comment_value`<>'' ";
			
			$freeAnswerRes		=	$connPDO->query($freeAnswerQry);
			$freeAnswerNumRows	=	$freeAnswerRes->rowCount();
			if($freeAnswerNumRows)
			{
				/*PDF LINK*/
					echo "<div style = 'float:right;padding:10px;'>
								<a href='print_comment_history.php?aca_yr=$academicYear&yr=$year&sem=$semester&deptId=$deptCode&subId=$subjectId' 
								target = '_blank'>
							Print As PDF</a>
					</div>";
				/*PDF LINK*/		
				/*Print the Headind*/
					$headerStr	 =	"Acadamic Year-{$academicYear} {$year}-Year  
									{$semester}-Semester {$deptCode}-Department <br/>
									'{$subjName}' - Student's Comments";
					echo "<h3>{$headerStr}</h3>";
				/*Print the Headind*/		
				$commentDiv				=	"<div id='commentWrapper'>";
				while($freeAnswerArr	=	$freeAnswerRes->fetch(PDO::FETCH_ASSOC))
				{
					
					
					
					$freeAnswerValue	=   $freeAnswerArr['comment_value'];	
					$commentDiv			.=	"<div id='comment' class='shadowEffect'>{$freeAnswerValue}</div>";
				}
				$commentDiv			.= '</div>';
				echo $commentDiv;	
			}	
			else
			{
				
				echo "<p align='center'>";
				echo "<span class='error'>Comments Not Found!</span>"	;
				echo "</p>";
			}
		}	
		/*List the Free Answer Ends here*/
	
	}
?>
<script type="text/javascript">
	var academic_year = new Spry.Widget.ValidationSelect("academic_year", {validateOn:["change"]});	
	var year    	  = new Spry.Widget.ValidationSelect("year", {validateOn:["change"]});						
	var semester      = new Spry.Widget.ValidationSelect("semester", {validateOn:["change"]});
	var dept_code     = new Spry.Widget.ValidationSelect("dept_code", {validateOn:["change"]});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>