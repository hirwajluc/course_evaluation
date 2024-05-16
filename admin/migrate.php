<?php
	ob_start();
	session_start();
	/*Include the default function file*/
	require_once("./includes/functions.php");
	/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
	global $connPDO;
	/*Include the default function file*/

	/*Step:1 Migrate Student Feedback to classwise_history table starts here*/
	
	/*Find the academic year value for the history table*/
	$acaYearQry					=	"SELECT `feedback_acc_year` FROM `tbl_feedback` GROUP BY `feedback_acc_year`";
	$acaYearRes					=	mysql_query($acaYearQry)or die(mysql_error());
	$acaYearNumRows				=	mysql_num_rows($acaYearRes);
	$acaYearArr					=	mysql_fetch_assoc($acaYearRes);
	$acaYear					=	$acaYearArr['feedback_acc_year'];
	/*Find the academic year value for the history table*/
	
	$classWiseHistorySelQry 	=	"SELECT `feedback_dept_id`,`feedback_acc_year`,`feedback_year`,`feedback_semester`,
									`feedback_sub_id`,`feedback_quality_id` FROM tbl_feedback 
									 GROUP BY `feedback_dept_id`,`feedback_acc_year`,`feedback_semester`,
									`feedback_sub_id`,`feedback_quality_id` ORDER BY `feedback_acc_year`,`feedback_year`,`feedback_semester`";
									
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
												$subjectId			= $totalFeedbackArr['subject_id'];	
												$subjectName		= $totalFeedbackArr['subject_name'];	
												$quality			= $totalFeedbackArr['quality'];	
												$answerType			= $totalFeedbackArr['answer_type'];	
												$amount				= $totalFeedbackArr['amount'];	
												$departmentCode		= $totalFeedbackArr['department_code'];	
												
											
													
													/*Find out year and semester from subject table starts here*/
													$subYearSemQry  		= "SELECT subject_year,subject_semester  FROM `tbl_subject` WHERE `subject_id` = $subjectId";
													$subYearSemRes			= mysql_query($subYearSemQry)or die(mysql_error());	
													$subYearSemResNumRows	=	mysql_num_rows($subYearSemRes);
													if($subYearSemResNumRows)
													{
															$subYearSemArr		= mysql_fetch_assoc($subYearSemRes);
															#echo "<pre>";print_r($subYearSemArr);die;
															$year				= $subYearSemArr['subject_year'];	
															$semester			= $subYearSemArr['subject_semester'];	
													
															$classwiseInsertQry	=	"INSERT INTO `classwise_history` (`id`, `subject_name`, `academic_year`, `dept_code`, `year`, `semester`, `quality`, `answer`, `amount`)
																					VALUES (NULL, '{$subjectName}', '{$acaYear}', '{$departmentCode}', '{$year}', '{$semester}','{$quality}','{$answerType}', '{$amount}')";
															$subYearSemRes		= mysql_query($classwiseInsertQry)or die(mysql_error());							
													}
														/*Find out year and semester from subject table ends here*/
												}/*End of while*/
										}/*End of If*/
				}/*End of while*/
			}/*End of if*/
		}/*End of while*/		
	}/*End of if*/
	/*Step:1 Migrate Student Feedback to classwise_history table ends here*/
	
	
	

	/*Step:2 Migrate Student Feedback to staffwise_history table*/
	$staffWiseHistorySelQry 	=	"SELECT `feedback_dept_id`,`feedback_acc_year`,`feedback_year`,`feedback_semester`,
									`feedback_teacher_id`,`feedback_quality_id` FROM tbl_feedback 
									 GROUP BY `feedback_dept_id`,`feedback_acc_year`,`feedback_semester`,
									`feedback_teacher_id`,`feedback_quality_id` ORDER BY
									`feedback_acc_year`,`feedback_year`,`feedback_semester`";
									
	$staffWiseHistorySelRes		=	mysql_query($staffWiseHistorySelQry)or die(mysql_error());								
	$staffWiseHistorySelRows	=	mysql_num_rows($staffWiseHistorySelRes);
	
	if($staffWiseHistorySelRows)
	{
		while($classWiseHistorySelAssoc	=	mysql_fetch_assoc($staffWiseHistorySelRes))
		{
			//echo "<pre>";print_r($classWiseHistorySelAssoc);
			
			$feedbackDeptId			=	$classWiseHistorySelAssoc['feedback_dept_id'];
			$feedbackAccYear		=	$classWiseHistorySelAssoc['feedback_acc_year'];
			$feedbackStaffId		=	$classWiseHistorySelAssoc['feedback_teacher_id'];
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
										WHERE `feedback_teacher_id` = {$feedbackStaffId} 
										AND `feedback_quality_id` = {$feedbackQualityId} AND `feedback_answer_id` = {$answerId} 
										AND `feedback_acc_year` = {$feedbackAccYear} AND `feedback_dept_id` = {$feedbackDeptId}";
													
										$totalFeedbackRes		=	mysql_query($totalFeedbackQry)or die(mysql_error());								
										$totalFeedbackNumRows	=	mysql_num_rows($totalFeedbackRes);

										if($totalFeedbackNumRows)
										{
											while($totalFeedbackArr = mysql_fetch_assoc($totalFeedbackRes))
											{
												echo '<pre>';print_r($totalFeedbackArr);	
												
												$staffFName			= $totalFeedbackArr['teacher_first_name'];	
												$staffLName			= $totalFeedbackArr['teacher_last_name'];	
												$quality			= $totalFeedbackArr['quality'];	
												$answerType			= $totalFeedbackArr['answer_type'];	
												$amount				= $totalFeedbackArr['amount'];	
												$departmentCode		= $totalFeedbackArr['department_code'];	
												
												$staffName			=	$staffFName.' '.$staffLName;
												$staffwiseInsertQry	=	"INSERT INTO .`staffwise_history` (`id`, `staff_name`, `academic_year`,`dept_code`, `quality`, `answer`, `amount`)
																		VALUES (NULL, '{$staffName}', '{$acaYear}', '{$departmentCode}', '{$quality}', '{$answerType}', '{$amount}')";
												$staffwiseInsertRes	= mysql_query($staffwiseInsertQry)or die(mysql_error());							
											
											}/*End of while*/
										}/*End of If*/
				}/*End of while*/
			}/*End of if*/
		}/*End of while*/		
	}/*End of if*/
	/*Step:2 Migrate Student Feedback to staffwise_history table*/
	
	/*Step:3 Truncate the tbl_feedback table starts here*/
		$truncateFeedbackQry	=	"TRUNCATE TABLE `tbl_feedback`";
		$truncateFeedbackRes	=	mysql_query($truncateFeedbackQry)or die(mysql_error());
	/*Step:3 Truncate the tbl_feedback table starts here*/
	
	/*Step:4 Migrate all the Students to their next level(1sem-2sem 1yr-2year)*/
	$oneToTwoMigarationQry 		= "UPDATE `tbl_users` SET `stud_year` = '1', `stud_sem` = '2' WHERE `stud_year`='1' AND `stud_sem`='1'";
	$TwoToThreeMigarationQry 	= "UPDATE `tbl_users` SET `stud_year` = '2', `stud_sem` = '1' WHERE `stud_year`='1' AND `stud_sem`='2'";
	$ThreeToFourMigarationQry 	= "UPDATE `tbl_users` SET `stud_year` = '2', `stud_sem` = '2' WHERE `stud_year`='2' AND `stud_sem`='1'";
	$FourToFiveMigarationQry 	= "UPDATE `tbl_users` SET `stud_year` = '0', `stud_sem` = '0' WHERE `stud_year`='2' AND `stud_sem`='2'";
	$oneToTwoMigarationRes		=	mysql_query($oneToTwoMigarationQry)OR die(mysql_error());
	/*Step:4 Migrate all the Students to their next level(1sem-2sem 1yr-2year)*/
	
	/*step 5: After Migration we have to redirect to the student new page*/
	header("Location:student_new.php?msg=migrate");
	exit;
?>