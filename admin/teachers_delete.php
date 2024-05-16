<?php
ob_start();
session_start();
/*Include the default function file*/
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
global $connPDO;
/*This function will check the session*/
checkSession();
if(isset($_GET['id']))
{
	$id 						= 	trim($_GET['id']);
	$teacherDependencyArr		=	array(
											"Subject"  => "SELECT subject_name FROM `tbl_subject` WHERE `subject_teacher_id` = {$id}",
											"Feedback" => "SELECT *  FROM `tbl_feedback` WHERE `feedback_teacher_id` = {$id} GROUP BY `feedback_teacher_id`"
										);
	/*This function will check the dependency with the tables before delete the record*/
	$dependencyField		=	checkDependency($teacherDependencyArr);
	/*If there is any dependency we have to throw error*/
	if($dependencyField)	
	{
		header("Location:teachers_search.php?keyword=&depend=$dependencyField");
		exit;
	}	
	else
	{	
		$deleteTeacherQry		=	"DELETE FROM `course_evaluation`.`tbl_teacher` WHERE `tbl_teacher`.`teacher_id`={$id}";
		$deleteTeacherRes		=	$connPDO->query($deleteTeacherQry);
		$deleteAffRows  		= 	$deleteTeacherRes->rowCount();
		if($deleteAffRows)
		{
			header("Location:teachers_search.php?keyword=&msg=deleted");
			exit;
		}	
	}	
}
else
{
	header("Location:teachers_search.php?keyword=&msg=notdeleted");
	exit;
}	
ob_end_flush();
?>