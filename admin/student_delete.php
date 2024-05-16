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

if(isset($_GET['id']))
{
	$id 					= 	trim($_GET['id']);
	$stuDependencyArr		=	array("Feedback" => "SELECT *  FROM `tbl_feedback` WHERE `feedback_stud_id` = {$id}
									   GROUP BY `feedback_stud_id`");
	/*This function will check the dependency with the tables before delete the record*/
	$dependencyField			=	checkDependency($stuDependencyArr);
	/*If there is any dependency we have to throw error*/
	if($dependencyField)	
	{
		header("Location:student_search.php?keyword=&depend=$dependencyField");
		exit;
	}	
	else
	{	
		$deleteDept		=	"DELETE FROM `tbl_users` WHERE `tbl_users`.`u_id`={$id}";
		$deleteDeptRes	=	$connPDO->query($deleteDept);
		$deleteAffRows  = 	$deleteDeptRes->rowCount();
		if($deleteAffRows)
		{
			header("Location:student_search.php?keyword=&msg=deleted");
			exit;
		}
	}		
}
else
{
	header("Location:student_search.php?keyword=&msg=notdeleted");
	exit;
}	
ob_end_flush();
?>