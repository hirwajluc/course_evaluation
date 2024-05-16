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
/*Include the database configuration file*/
require_once("includes/config.php");
if(isset($_GET['id']))
{
	$id 						= 	trim($_GET['id']);
	$qualityDependencyArr		=	array(
											"Feedback" => "SELECT *  FROM `tbl_feedback` WHERE `feedback_quality_id` = {$id} GROUP BY `feedback_quality_id`"
										);
	/*This function will check the dependency with the tables before delete the record*/
	$dependencyField			=	checkDependency($qualityDependencyArr);
	/*If there is any dependency we have to throw error*/
	if($dependencyField)	
	{
		header("Location:qualities_search.php?keyword=&depend=$dependencyField");
		exit;
	}	
	else
	{	
		$deleteQualQry		=	"DELETE FROM tbl_quality WHERE quality_id={$id}";
		$deleteQualRes		=	$connPDO->query($deleteQualQry);
		$deleteAffRows  	= 	$deleteQualRes->rowCount();
		if($deleteAffRows)
		{
			header("Location:qualities_search.php?keyword=&msg=deleted");
			exit;
		}
	}	
}
else
{
	header("Location:qualities_search.php?keyword=&msg=notdeleted");
	exit;
}
ob_end_flush();
?>