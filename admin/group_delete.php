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
	
	$deleteDept		=	"DELETE FROM tbl_classes WHERE class_id={$id}";
	$deleteDeptRes	=	$connPDO->query($deleteDept);
	$deleteAffRows  = 	$deleteDeptRes->rowCount();
	if($deleteAffRows)
	{
		header("Location:group_search.php?keyword=&msg=deleted");
		exit;
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