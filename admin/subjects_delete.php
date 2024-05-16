<?php
ob_start();
session_start();
/*Include the default function file*/
require_once("./includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/*This function will check the session*/
checkSession();
/*Include the database configuration file*/
require_once("includes/config.php");
if(isset($_GET['id']))
{
	$id 					= 	trim($_GET['id']);
	$subjDependencyArr		=	array(
										"Feedback" => "SELECT *  FROM `tbl_feedback` WHERE `feedback_sub_id` = {$id} GROUP BY feedback_sub_id"
									);
	/*This function will check the dependency with the tables before delete the record*/
	$dependencyField		=	checkDependency($subjDependencyArr);
	/*If there is any dependency we have to throw error*/
	if($dependencyField)	
	{
		header("Location:subjects_search.php?keyword=&depend=$dependencyField");
		exit;
	}	
	else
	{	
		$deleteSubj		=	"DELETE FROM `tbl_subject` WHERE `tbl_subject`.`subject_id` = {$id}";
		$deleteSubjRes	=	$connPDO->query($deleteSubj);
		$deleteAffRows  = 	$deleteSubjRes -> rowCount();
		if($deleteAffRows)
		{
			header("Location:subjects_search.php?keyword=&msg=deleted");
			exit;
		}	
	}	
}
else
{
	header("Location:subjects_search.php?keyword=&msg=notdeleted");
	exit;
}
ob_end_flush();
?>