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
	$id 				= 	trim($_GET['id']);
	$deptDependencyArr	=	array("Class" => "SELECT class_id  FROM `tbl_classes` WHERE `class_level` LIKE '{$id}'"
								  );
	/*This function will check the dependency with the tables before delete the record*/
	$dependencyField		=	checkDependency($deptDependencyArr);
	/*If there is any dependency we have to throw error*/
	if($dependencyField)	
	{
		header("Location:level_search.php?keyword=&depend=$dependencyField");
		exit;
	}	
	else
	{	
		$deleteDept		=	"DELETE FROM tbl_levels WHERE level_id={$id}";
		$deleteDeptRes	=	$connPDO->query($deleteDept);
		$deleteAffRows  = 	$deleteDeptRes->rowCount();
		if($deleteAffRows)
		{
			header("Location:level_search.php?keyword=&msg=deleted");
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