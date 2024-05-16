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
			
	$deleteUserQry		=	"DELETE FROM `course_evaluation`.`tbl_users` WHERE `tbl_users`.`u_id`={$id}";
	$deleteUserRes		=	$connPDO->query($deleteUserQry);
	$deleteAffRows  		= 	$deleteUserRes->rowCount();
	if($deleteAffRows)
	{
		header("Location:user_search.php?keyword=&msg=deleted");
		exit;
	}	
	
}
else
{
	header("Location:user_search.php?keyword=&msg=notdeleted");
	exit;
}	
ob_end_flush();
?>