<?php
ob_start();
//Pdf document

//end of pdf document
session_start();
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/*Include the default function file*/
require_once("includes/functions.php");
/*This function will check the session*/
checkSession();
if(isset($_GET['yr']) && isset($_GET['sem']) && isset($_GET['deptId']))
{
//	echo "<pre>";print_r($_POST);die;
	$year			=	$_GET['yr'];
	$semester		=	$_GET['sem'];
	$deptId			=	$_GET['deptId'];
	$subjectId		=	$_GET['subId'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="../student/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="icon" href="images/favi_logo.gif"/>
	<!--link rel="shortcut icon" href="/mail/images/favicon.ico" type="image/x-icon"-->
	<style>
		h1,h2,h3,h4,h5,h6
		{
			color		:	#000;
			text-align	:	center;
		}
		h3
		{
			color		:	#E76800;
			
		}
		table
		{
			page-break-after:always
		}
		
	</style>
	<style type="text/css">

</style>
</head>

<body>
<?php
//	echo plotLogoWithAddress();
?>
<?php
if(isset($_GET['yr']) && isset($_GET['sem']) && isset($_GET['deptId']))
{
	//echo "<pre>";print_r($_POST);
	$year			=	$_GET['yr'];
	$semester		=	$_GET['sem'];
	$deptId			=	$_GET['deptId'];
	if($_GET['subId'] !=='')
	{
		$subjectId		=	$_GET['subId'];
	}	
	if($year =='' || $semester =='' || $deptId == '')
	{
		echo "<p align='center'>";
		echo "<span class='error'>Required Field Cannot Be Left Blank!</span>"	;
		echo "</p>";
	}
	else
	{
	
		/*Step:1 Migrate Student Feedback to classwise_history table starts here*/
		
		/*Find the academic year value for the history table*/
		$acaYearQry					=	"SELECT `feedback_acc_year` FROM `tbl_feedback` 
										GROUP BY `feedback_acc_year`";
		$acaYearRes					=	mysql_query($acaYearQry)or die(mysql_error());
		$acaYearNumRows				=	mysql_num_rows($acaYearRes);
		$acaYearArr					=	mysql_fetch_assoc($acaYearRes);
		$acaYear					=	$acaYearArr['feedback_acc_year'];
		/*Find the academic year value for the history table*/
		
		$checkFeedbackTableQry		=	"SELECT * FROM  tbl_feedback";
		$checkFeedbackTableRes		=	mysql_query($checkFeedbackTableQry);
		$classWiseHistorySelRows	=	mysql_num_rows($checkFeedbackTableRes);
		/*If the feedback table is empty (migrated into history table) we need to give error Message*/
		if(!$classWiseHistorySelRows)
		{
			echo "<p align='center'>";
			echo "<span class='error'>Feedback Not Exist!</span>"	;
			echo "</p>";
		}
		else
		{
			if($subjectId)
			{
				$classWiseHistorySelQry 	=	"SELECT `feedback_dept_id`,`feedback_acc_year`,`feedback_year`,`feedback_semester`,
											`feedback_sub_id`,`feedback_quality_id` 
											 FROM tbl_feedback 
											 WHERE `feedback_dept_id` = {$deptId} AND `feedback_year` LIKE '{$year}' AND `feedback_semester` LIKE '{$semester}'
											 AND `feedback_sub_id` LIKE {$subjectId}
											 GROUP BY `feedback_dept_id`,`feedback_acc_year`,`feedback_semester`,
											`feedback_sub_id`,`feedback_quality_id` ORDER BY `feedback_acc_year`,`feedback_year`,`feedback_semester`";
			}
			else
			{
				$classWiseHistorySelQry 	=	"SELECT `feedback_dept_id`,`feedback_acc_year`,
												`feedback_year`,`feedback_semester`,
												`feedback_sub_id`,`feedback_quality_id` 
												 FROM tbl_feedback 
												 WHERE `feedback_dept_id` = {$deptId} AND `feedback_year` LIKE '{$year}' AND `feedback_semester` LIKE '{$semester}'
												 GROUP BY `feedback_dept_id`,`feedback_acc_year`,`feedback_semester`,
												`feedback_sub_id`,`feedback_quality_id` ORDER BY `feedback_acc_year`,`feedback_year`,`feedback_semester`";
			}								
											
			$classWiseHistorySelRes		=	mysql_query($classWiseHistorySelQry)or die(mysql_error());		
			$classWiseHistorySelRows	=	mysql_num_rows($classWiseHistorySelRes);
			
			if($classWiseHistorySelRows)
			{
				while($classWiseHistorySelAssoc	=	mysql_fetch_assoc($classWiseHistorySelRes))
				{
					//echo "<pre>";print_r($classWiseHistorySelAssoc);
					
					$feedbackDeptId			=	$classWiseHistorySelAssoc['feedback_dept_id'];
					$feedbackAccYear		=	$classWiseHistorySelAssoc['feedback_acc_year'];
					$feedbackYear			=	$classWiseHistorySelAssoc['feedback_year'];
					$feedbackSemester		=	$classWiseHistorySelAssoc['feedback_semester'];
					$feedbackSubId			=	$classWiseHistorySelAssoc['feedback_sub_id'];
					$feedbackQualityId		=	$classWiseHistorySelAssoc['feedback_quality_id'];
					
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
					
							
							$totalFeedbackQry	=	"SELECT COUNT(*) AS amount,tbl_subject.subject_id,tbl_subject.subject_name,tbl_quality.quality,tbl_answer.answer_type,
												tbl_department.department_code 	FROM tbl_feedback INNER JOIN  tbl_subject ON 
												tbl_feedback.`feedback_sub_id` = tbl_subject.`subject_id`
												INNER JOIN tbl_quality ON
												tbl_feedback.`feedback_quality_id` = tbl_quality.`quality_id`
												INNER JOIN tbl_answer ON
												tbl_feedback.`feedback_answer_id` = tbl_answer.`answer_id`
												INNER JOIN tbl_department ON
												tbl_feedback.`feedback_dept_id` = tbl_department.`department_id`
												RIGHT JOIN tbl_users ON 
												tbl_feedback.`feedback_stud_id` = tbl_users.u_id
												WHERE `feedback_sub_id` = {$feedbackSubId} 
												AND `feedback_quality_id` = {$feedbackQualityId} AND `feedback_answer_id` = {$answerId} 
												AND `feedback_acc_year` = {$feedbackAccYear} AND `feedback_dept_id` = {$feedbackDeptId} 
												AND `feedback_year` = {$feedbackYear} AND `feedback_semester` = {$feedbackSemester}";
					
											
													
												$totalFeedbackRes		=	mysql_query($totalFeedbackQry)or die(mysql_error());								
												$totalFeedbackNumRows	=	mysql_num_rows($totalFeedbackRes);

												if($totalFeedbackNumRows)
												{
													while($totalFeedbackArr = mysql_fetch_assoc($totalFeedbackRes))
													{
														#echo '<pre>';print_r($totalFeedbackArr);
														$subjectIdNew		= $totalFeedbackArr['subject_id'];	
														$subjectName		= $totalFeedbackArr['subject_name'];	
														$quality			= $totalFeedbackArr['quality'];	
														$answerType			= $totalFeedbackArr['answer_type'];	
														$amount				= $totalFeedbackArr['amount'];	
														$departmentCode		= $totalFeedbackArr['department_code'];	
														
													
															
															/*Find out year and semester from subject table starts here*/
															$subYearSemQry  		= "SELECT subject_year,subject_semester  FROM `tbl_subject` WHERE `subject_id` = $subjectIdNew";
															$subYearSemRes			= mysql_query($subYearSemQry)or die(mysql_error());	
															$subYearSemResNumRows	=	mysql_num_rows($subYearSemRes);
															if($subYearSemResNumRows)
															{
																	$subYearSemArr		= mysql_fetch_assoc($subYearSemRes);
																	#echo "<pre>";print_r($subYearSemArr);die;
																	$year				= $subYearSemArr['subject_year'];	
																	$semester			= $subYearSemArr['subject_semester'];	
															
																	$classwiseFeedbackArr[]	=	array($acaYear,$year,$semester,$departmentCode,$subjectName,$quality,$answerType,$amount);	
															}
																/*Find out year and semester from subject table ends here*/
														}/*End of while*/
												}/*End of If*/
						}/*End of while*/
					}/*End of if*/
				}/*End of while*/		
			}/*End of if*/
			/*Step:1 Migrate Student Feedback to classwise_history table ends here*/
			
			//echo "<pre>";print_r($classwiseFeedbackArr);
			if(isset($classwiseFeedbackArr) && 	is_array($classwiseFeedbackArr))
			{
				foreach($classwiseFeedbackArr as $key => $row)
				{
					$ac_year[$key]		=	$row[0];
					$yearArr[$key]		=	$row[1];
					$sem[$key]			=	$row[2];
					$dept[$key]			=	$row[3];
					$sub[$key]			=	$row[4];
					$qualityArr[$key]	=	$row[5];
					$answer[$key]		=	$row[6];
				}
				array_multisort($ac_year,SORT_DESC
								,$yearArr,SORT_DESC
								,$sem,SORT_DESC
								,$dept,SORT_DESC
								,$sub,SORT_DESC
								,$qualityArr,SORT_ASC
								//,$answer,SORT_DESC
								,$classwiseFeedbackArr);
				//echo "<pre>";print_r($classwiseFeedbackArr);
					$tempArr='';
					$tempQuality = '';
				/*Print the Headind*/
				$currentYear	=	date("Y");
				if($subjectId)
				{
					$subjQry = 	"SELECT `subject_name`,`department_code` 
								FROM tbl_subject INNER JOIN tbl_department
								ON `subject_department_id` = `department_id`
								WHERE `subject_id`=$subjectId";
				}
				else
				{
					$subjQry = 	"SELECT `department_code` 
								FROM tbl_department
								WHERE `department_id`=$deptId";
				}	
				$subjRes	 		= mysql_query($subjQry)or die(mysql_error());	
				$subArr	 	 		= mysql_fetch_assoc($subjRes);
				$subjNameForHeader	= isset($subArr['subject_name'])?$subArr['subject_name']:'';
				
				$headerStr	 =	"Acadamic Year-{$currentYear} {$year}-Year 
								{$semester}-Semester {$subArr['department_code']}-Department 
								{$subjNameForHeader}  Report";
				echo "<h3>{$headerStr}</h3>";
				/*Print the Headind*/
				echo "<br/>";	
				$i=1;
				foreach($classwiseFeedbackArr as $qualityFeedbackArr)
				{
					$classwiseFeedbackChunkArr	=	array_chunk($qualityFeedbackArr,5,true);
					if($tempArr !== $classwiseFeedbackChunkArr[0])
					{
						echo "<h2>{$classwiseFeedbackChunkArr[0][4]}</h2>";
					}
					if($tempQuality	!== $classwiseFeedbackChunkArr[1][5])
					{
						echo '<h3>',$classwiseFeedbackChunkArr[1][5],'</h3>';
					}
					$tempQuality	=	$classwiseFeedbackChunkArr[1][5];
					$tempArr		=	$classwiseFeedbackChunkArr[0];
					
					$arrForChart[$classwiseFeedbackChunkArr[1][6]]	=	$classwiseFeedbackChunkArr[1][7];
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
							echo "<div class= 'printChartContainer' >
								<!--Chart Conatainer Starts Here-->";
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
							
							/*Main Chart Container Div ends here*/
							$arrForChart = array();
						/*Chart Functionality ends here*/
					}
					//echo '<pre>';print_r($arrForChart);
					$i++;
					echo '<script>';
					//echo 'window.print();';
					echo 'window.save();';
					echo '</script>';
				}
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
else
{
	echo "<p align='center'>";
	echo "<span class='error'>Search Not Found!</span>"	;
	echo "</p>";
}
?>	
</body>
<?php;?>
</html>