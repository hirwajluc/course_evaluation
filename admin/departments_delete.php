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
	$id 				= 	trim($_GET['id']);
	$type 				= 	trim($_GET['type']);
	if ($type == 'p') {
		# code...
		$deptDependencyArr	=	array(
			"Subject"	=> "SELECT subject_name  FROM `tbl_subject` WHERE `subject_department_id` = {$id}"
		);
	} elseif ($type == 'd') {
		# code...
		$deptDependencyArr	=	array(
			"Teacher" => "SELECT teacher_first_name  FROM `tbl_teacher` WHERE `teacher_department_id` = {$id}"
		);
	} else {
		// Get the previous page URL
		$previousPage = $_SERVER['HTTP_REFERER'];
		// Redirect to the previous page
		header("Location: $previousPage");
		exit();
	}
	
	/*This function will check the dependency with the tables before delete the record*/
	$dependencyField		=	checkDependency($deptDependencyArr);
	/*If there is any dependency we have to throw error*/
	if($dependencyField)	
	{
		$previousPage = $_SERVER['HTTP_REFERER'];
		header("Location: $previousPage&depend=$dependencyField");
		exit();
	}	
	else
	{	
		if ($type == 'd') {
			$deleteDept		=	"DELETE FROM tbl_t_departments WHERE dpt_id={$id}";
		} else {
			$deleteDept		=	"DELETE FROM tbl_department WHERE department_id={$id}";
		}
		$deleteDeptRes	=	$connPDO->query($deleteDept);
		$deleteAffRows  = 	$deleteDeptRes->rowCount();
		if($deleteAffRows)
		{
			if ($type == 'd') {
				header("Location:department_t_search.php?keyword=&msg=deleted");
			} else {
				header("Location:department_search.php?keyword=&msg=deleted");
			}
			exit;
		}	
	}	
}
else
{
	header("Location:index.php?keyword=&msg=notdeleted");
	exit;
}	
?>
<?php
	ob_end_flush();
?>