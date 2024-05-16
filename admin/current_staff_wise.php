<?php
	ob_start();
	session_start();
	/* Include the database configuration file */
	require_once("includes/functions.php");
	/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
	global $connPDO;
	checkSession();
	if(isset($_POST['submit']))
	{
		//echo "<pre>";print_r($_POST);
		$deptId			=	$_POST['department'];
		$teacherId		=	$_POST['teacher_id'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Staff wise current Report</title>
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
<body onload = "plotTeacherByDept(<?php echo $deptId;?>,<?php echo $teacherId;?>)">
	<div style = 'float:left'>
		<p class='welcome' align='left'>Welcome 
			<strong><?php echo $_SESSION['u_utype'];?></strong><br/>
			<a href='change_password.php'>Change Password</a> | <a href='logout.php'>Logout</a>
		</p>
	</div>
	<?php
		echo plotLogoWithAddress();
	?>
	<div>
		<table id='listEntries' width="50%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" bordercolor="#CCCCCC">
			<tr bgcolor="#FFFFCC">
				<th colspan='2' align='center'>Current</th>
				<th colspan='2' align='center'>History</th>
			</tr>
			<tr>	
				<td align="center"><a href="current_class_wise.php">Class Wise</a></td>
				<td align="center"><a href="current_staff_wise.php" class='activelink'>Staff Wise</a></td>
				<td align="center"><a href="history_class_wise.php">Class Wise</a></td>
				<td align="center"><a href="history_staff_wise.php">Staff Wise</a></td>
			</tr>
		</table>
	</div><!--Report Menu Ends Here-->
<br/>
<form method = 'post' action="<?php echo $_SERVER['PHP_SELF'];?>">
<table id = "listEntries" width = "100%" border="1">
<tr>
		<td>
			<strong>Department</strong>
			<span class = 'mandatory'>*</span>
		</td>
		<td height = '30'>
			<?php
				$deptId	=	isset($deptId)?$deptId:'';
				echo plotDepartmentDropdown($deptSelVal = $deptId,$ajaxEnabled='yes')
			?>
		</td><!-- End of department-->
		<td width = '170' height = '30'>
					<strong>Staff Name</strong>
					<span class = 'mandatory'>*</span>
		</td>
		<td height = '30'>
					<div id='teacher_name_div'>
						<select name='teacher_id' id='teacher_id' class='typeproforms'>
							<option value=''>--select--</option>
						</select>
					</div>
		</td><!-- End of Staff Name-->
		<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Search Feedback' />
		</td>
</tr>
</table>
</form>
<?php
if(isset($_POST['submit']))
{
	//echo "<pre>";print_r($_POST);
	$deptId			=	$_POST['department'];
	$teacherId		=	$_POST['teacher_id'];
	
	if($deptId == '' || $teacherId == '')
	{
		echo "<p align='center'>";
		echo "<span class='error'>Required Field Cannot Be Left Blank!</span>"	;
		echo "</p>";
	}
	else
	{
		/*Step:1 Migrate Student Feedback to classwise_history table starts here*/
		
		/*Find the academic year value for the history table*/
		$acaYearQry					=	"SELECT `feedback_acc_year` FROM `tbl_feedback` GROUP BY `feedback_acc_year`";
		$acaYearRes					=	$connPDO->query($acaYearQry);
		$acaYearNumRows				=	$acaYearRes->rowCount();
		$acaYearArr					=	$acaYearRes->fetch(PDO::FETCH_ASSOC);
		$acaYear					=	$acaYearArr['feedback_acc_year'];
		/*Find the academic year value for the history table*/
		
		$checkFeedbackTableQry		=	"SELECT * FROM  tbl_feedback";
		$checkFeedbackTableRes		=	$connPDO->query($checkFeedbackTableQry);
		$staffWiseHistorySelRows	=	$checkFeedbackTableRes->rowCount();
		/*If the feedback table is empty (migrated into history table) we need to give error Message*/
		if(!$staffWiseHistorySelRows)
		{
			echo "<p align='center'>";
			echo "<span class='error'>Feedback Not Exist!</span>"	;
			echo "</p>";
		}
		else
		{
		$staffWiseHistorySelQry 	=	"SELECT `feedback_dept_id`,`feedback_acc_year`,
											`feedback_teacher_id`,`feedback_quality_id` 
											 FROM tbl_feedback WHERE 
											`feedback_dept_id` = {$deptId} AND 
											`feedback_teacher_id` = {$teacherId} 
											GROUP BY `feedback_acc_year`,`feedback_dept_id`,`feedback_teacher_id`,`feedback_quality_id` 
											ORDER BY `feedback_acc_year`,`feedback_teacher_id`,`feedback_quality_id`";
										
			$staffWiseHistorySelRes		=	$connPDO->query($staffWiseHistorySelQry);								
			$staffWiseHistorySelRows	=	$staffWiseHistorySelRes->rowCount();
			
			if($staffWiseHistorySelRows)
			{
				while($staffWiseHistorySelAssoc	= $staffWiseHistorySelRes->fetch(PDO::FETCH_ASSOC))
				{
					//echo "<pre>";print_r($staffWiseHistorySelAssoc);
				
					$feedbackDeptId			=	$staffWiseHistorySelAssoc['feedback_dept_id'];
					$feedbackAccYear		=	$staffWiseHistorySelAssoc['feedback_acc_year'];
					$feedbackTeacherId		=	$staffWiseHistorySelAssoc['feedback_teacher_id'];
					$feedbackQualityId		=	$staffWiseHistorySelAssoc['feedback_quality_id'];
					
					/*For each answer*/
					$answerQry				=	"SELECT * FROM  `tbl_answer`";
					$answerRes				=	$connPDO->query($answerQry);
					$answerNumRows			=	$answerRes->rowCount();
					if($answerNumRows)
					{
						while($answerArr    = 	$answerRes->fetch(PDO::FETCH_ASSOC))
						{
							#echo '<pre>';print_r($answerArr);
							$answerId		=	$answerArr['answer_id'];
							$answerType		=	$answerArr['answer_type'];
					
							
							$totalFeedbackQry	=	"SELECT COUNT(*) AS amount,tbl_teacher.teacher_first_name,tbl_teacher.teacher_last_name,
													tbl_quality.quality,tbl_answer.answer_type,
													tbl_department.department_code 	FROM tbl_feedback INNER JOIN  tbl_teacher ON 
													tbl_feedback.`feedback_teacher_id` = tbl_teacher.`teacher_id`
													INNER JOIN tbl_quality ON
													tbl_feedback.`feedback_quality_id` = tbl_quality.`quality_id`
													INNER JOIN tbl_answer ON
													tbl_feedback.`feedback_answer_id` = tbl_answer.`answer_id`
													INNER JOIN tbl_department ON
													tbl_feedback.`feedback_dept_id` = tbl_department.`department_id`
													WHERE `feedback_teacher_id` = {$feedbackTeacherId} 
													AND `feedback_quality_id` = {$feedbackQualityId} AND `feedback_answer_id` = {$answerId} 
													AND `feedback_acc_year` = {$feedbackAccYear} AND `feedback_dept_id` = {$feedbackDeptId}";
					
							$totalFeedbackRes =	$connPDO->query($totalFeedbackQry);					
							$totalFeedbackNumRows	=	$totalFeedbackRes->rowCount();

							if($totalFeedbackNumRows)
							{
								while($totalFeedbackArr = $totalFeedbackRes->fetch(PDO::FETCH_ASSOC))
								{
									//echo '<pre>';print_r($totalFeedbackArr);	
									
									$staffFName			= $totalFeedbackArr['teacher_first_name'];	
									$staffLName			= $totalFeedbackArr['teacher_last_name'];	
									$quality			= $totalFeedbackArr['quality'];	
									$answerType			= $totalFeedbackArr['answer_type'];	
									$amount				= $totalFeedbackArr['amount'];	
									$departmentCode		= $totalFeedbackArr['department_code'];	
									
									$staffName				=	$staffFName.' '.$staffLName;
									$staffwiseFeedbackArr[]	= array($staffName,$quality,$answerType,$amount,$departmentCode);							
								
								}/*End of while*/
							}/*End of If*/
										
						}/*End of while*/
					}/*End of if*/
				}/*End of while*/		 
			
			}/*End of if*/
			/*Step:1 Migrate Student Feedback to classwise_history table ends here*/
			if(isset($staffwiseFeedbackArr) && 	is_array($staffwiseFeedbackArr))
			{
				/*PDF LINK*/
				echo "<div style = 'float:right;padding:10px;'>
						<a href='print_current_staffwise.php?deptId=$deptId&teacherId=$teacherId' target = '_blank'>
						Print As PDF</a>
					</div>";
				foreach($staffwiseFeedbackArr as $key => $row)
				{
					$staffNameuuu[$key]			=	$row[0];
					$qualityuuu[$key]			=	$row[1];
					$answerTypeuuu[$key]		=	$row[2];
					$amountuuu[$key]			=	$row[3];
					$departmentCodeuuu[$key]	=	$row[4];
					
				}
				array_multisort($staffNameuuu,SORT_DESC
								,$qualityuuu,SORT_DESC
								,$answerTypeuuu,SORT_DESC
								,$amountuuu,SORT_DESC
								,$departmentCodeuuu,SORT_DESC
								,$staffwiseFeedbackArr
								);
				
				$tempArr='';
				$tempQuality = '';
				/*Print the Headind*/
				$currentYear	=	date("Y");
				$teacherQry = "SELECT `teacher_first_name`,`teacher_last_name`,
							   `department_Code` FROM tbl_teacher INNER JOIN tbl_department
							    ON `teacher_department_id` = department_id
								WHERE `teacher_id` = $teacherId AND teacher_department_id=$deptId";
				$teacherRes	 = $connPDO->query($teacherQry);	
				$teacherArr	 = $teacherRes->fetch(PDO::FETCH_ASSOC);	
				$headerStr	 =	"Mr/Ms {$teacherArr['teacher_first_name']} {$teacherArr['teacher_last_name']},
								  {$teacherArr['department_Code']} Dept, Acadamic Year $currentYear Report";
				echo "<h2>{$headerStr}</h2>";
				/*Print the Headind*/
				$i = 0;	
				foreach($staffwiseFeedbackArr as $qualityFeedbackArr)
				{
					$staffwiseFeedbackArrNew= array();
					$staffName = array_shift($qualityFeedbackArr);
					$staffNameArr	=	array($staffName);
					$staffwiseFeedbackArrNew[]	= $staffNameArr;
					$staffwiseFeedbackArrNew[]	= $qualityFeedbackArr;
					/*
					if($tempArr !== $staffwiseFeedbackArrNew[0])
					{
						echo "<h2>{$staffwiseFeedbackArrNew[0][0]}</h2>";
					}
					*/
					
					if($tempQuality	!== $staffwiseFeedbackArrNew[1][0])
					{
						echo '<h3>',$staffwiseFeedbackArrNew[1][0],'</h3>';
					}
					$tempQuality	=	$staffwiseFeedbackArrNew[1][0];
					$tempArr		=	$staffwiseFeedbackArrNew[0];
					$arrForChart[$staffwiseFeedbackArrNew[1][1]]	=	$staffwiseFeedbackArrNew[1][2];
					//echo '<pre>';print_r($arrForChart);
					/*Find the number of answers in the answer table*/
					$answerQry			=	"SELECT * FROM `tbl_answer`";
					$answerRes			=	$connPDO->query($answerQry);
					$countAnswer		=	$answerRes->rowCount();
					/*Find the number of answers in the answer table*/
					
					if(count($arrForChart) === $countAnswer)
					{
							/*Chart Functionality starts here*/
							$titleArr				=	 array('title'=>'FeedBack','width'=>400,'height'=>300);
							$jsonTitleForMap		=	 json_encode($titleArr);
							$jsonContentForMap  	= 	 setJsonStrForChart($arrForChart);
							$barChartDiv			=	 $i*100;
							plotChart($jsonTitleForMap,$jsonContentForMap,'BarChart',$barChartDiv);//Plot Bar Chart
							plotChart($jsonTitleForMap,$jsonContentForMap,'PieChart',$divId = $i);//Plot Pie Chart
							/*Main Chart Container Div starts here*/
							echo "<div class= 'chartContainer'><!--Chart Conatainer Starts Here-->";
								/*Div that will hold the pie chart starts Here*/
								echo "<!--Div that will hold the  chart starts Here-->";
								echo "<div id='chart_div{$barChartDiv}' style='width:400; height:300'></div>";
								echo '<br/>';
								echo "<div id='chart_div{$i}' style='width:400; height:300'></div>";
								echo "<!--Div that will hold the chart ends Here-->";
								echo '<br/>';
								/*Div that will hold the pie chart ends Here*/
								/*Display Table results starts here*/
								echo "<table id='listEntries' width='20%'  border='1'>
									 <!--Display Table results starts here -->";
								foreach($arrForChart as $answer => $value)
								{	
									echo "<tr>";
									echo "<td>{$answer}</td>";
									echo "<td>{$value}</td>";
									echo "</tr>";
								}
								echo "</table><!--Display Table results starts here-->";
								/*Display Table results starts here*/
							echo "</div><!--Chart Conatainer Ends Here-->";
							/*Main Chart Container Div ends here*/
							$arrForChart = array();
						/*Chart Functionality ends here*/
					}
					$i++;		
				}
				echo "</table>";
			}/*If result array is not empty*/
			else
			{
				/*If result array is empty*/
				echo "<p align='center'>";
				echo "<span class='error'>Feedback Not Exist!</span>"	;
				echo "</p>";
			}
		}/*End of Else - If Feedback table has records(not migrated to history table)*/	
	}/*End of Else if the mantatory fields is not empty*/		
}
?>
<script type="text/javascript">
	var deptId    	 = new Spry.Widget.ValidationSelect("deptId", {validateOn:["change"]});	
</script>	
</body>
</html>
<?php
	ob_end_flush();
?>