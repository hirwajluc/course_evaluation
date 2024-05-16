<?php
	ob_start();
	session_start();
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
	/*Include the default function file*/
	require_once("includes/functions.php");
	/*This function will check the session*/
	checkSession();
	if(isset($_GET['deptId']) && isset($_GET['teacherId']))
	{
		$deptId			=	$_GET['deptId'];
		$teacherId		=	$_GET['teacherId'];
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
<body>
<?php
	//echo plotLogoWithAddress();
?>
<?php
if(isset($deptId) && isset($teacherId))
{
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
		$acaYearRes					=	mysql_query($acaYearQry)or die(mysql_error());
		$acaYearNumRows				=	mysql_num_rows($acaYearRes);
		$acaYearArr					=	mysql_fetch_assoc($acaYearRes);
		$acaYear					=	$acaYearArr['feedback_acc_year'];
		/*Find the academic year value for the history table*/
		
		$checkFeedbackTableQry		=	"SELECT * FROM  tbl_feedback";
		$checkFeedbackTableRes		=	mysql_query($checkFeedbackTableQry);
		$staffWiseHistorySelRows	=	mysql_num_rows($checkFeedbackTableRes);
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
										
			$staffWiseHistorySelRes		=	mysql_query($staffWiseHistorySelQry)or die(mysql_error());								
			$staffWiseHistorySelRows	=	mysql_num_rows($staffWiseHistorySelRes);
			
			if($staffWiseHistorySelRows)
			{
				while($staffWiseHistorySelAssoc	=	mysql_fetch_assoc($staffWiseHistorySelRes))
				{
					//echo "<pre>";print_r($staffWiseHistorySelAssoc);
				
					$feedbackDeptId			=	$staffWiseHistorySelAssoc['feedback_dept_id'];
					$feedbackAccYear		=	$staffWiseHistorySelAssoc['feedback_acc_year'];
					$feedbackTeacherId		=	$staffWiseHistorySelAssoc['feedback_teacher_id'];
					$feedbackQualityId		=	$staffWiseHistorySelAssoc['feedback_quality_id'];
					
					/*For each answer*/
					$answerQry				=	"SELECT * FROM  `tbl_answer`";
					$answerRes				=	mysql_query($answerQry)or die(mysql_error());
					$answerNumRows			=	mysql_num_rows($answerRes)or die(mysql_error());
					if($answerNumRows)
					{
						while($answerArr    = 	mysql_fetch_assoc($answerRes))
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
					
							$totalFeedbackRes		=	mysql_query($totalFeedbackQry)or die(mysql_error());								
							$totalFeedbackNumRows	=	mysql_num_rows($totalFeedbackRes);

							if($totalFeedbackNumRows)
							{
								while($totalFeedbackArr = mysql_fetch_assoc($totalFeedbackRes))
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
				$teacherRes	 = mysql_query($teacherQry)or die(mysql_error());	
				$teacherArr	 = mysql_fetch_assoc($teacherRes);	
				$headerStr	 =	"Mr/Ms {$teacherArr['teacher_first_name']} {$teacherArr['teacher_last_name']},
								  {$teacherArr['department_Code']} Dept, Acadamic Year $currentYear Report";
				echo "<h2>{$headerStr}</h2>";
				/*Print the Headind*/
				echo "<br/>";
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
					$answerRes			=	mysql_query($answerQry)or die(mysql_error());
					$countAnswer		=	mysql_num_rows($answerRes);
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
							echo "<div class= 'printChartContainer'><!--Chart Conatainer Starts Here-->";
								/*Div that will hold the pie chart starts Here*/
								echo "<!--Div that will hold the  chart starts Here-->";
								echo "<div id='chart_div{$barChartDiv}' style='width:400; height:300'></div>";
								echo "<div id='chart_div{$i}' style='width:400; height:300'></div>";
								echo "<!--Div that will hold the chart ends Here-->";
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
							echo '<hr/>';
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
	echo '<script>';
	echo 'window.print();';
	echo '</script>';
}
else
{
	echo "<p align='center'>";
	echo "<span class='error'>Search Not Found!</span>"	;
	echo "</p>";
}
?>	
</body>
</html>