<?php
	/*Include the database configuration file*/
	require_once("./includes/functions.php");
	/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
	global $connPDO;
	if(isset($_GET['year']) && isset($_GET['sem']) && isset($_GET['deptId']))
	{
		$year				= 	($_GET['year'] !='')?$_GET['year']:0;	
		$semester			= 	($_GET['sem'] !='')?$_GET['sem']:0;	
		$deptId				= 	($_GET['deptId'] !='')?$_GET['deptId']:0;	
		if(isset($_GET['subjectId']))
		{
			$subjectId			= $_GET['subjectId'];
		}	
		//var_dump($subjectId	);
		$subjectSearchQry	=	"SELECT *  FROM `tbl_subject` WHERE `subject_year` LIKE '{$year}' AND 
								`subject_semester` = {$semester}
								AND `subject_department_id` = {$deptId}";
		$subjectSearchRes	=	$connPDO->query($subjectSearchQry);
		$subjectNumRows		=	$subjectSearchRes->rowCount();
		$subjectDropStr 	= "<select name='subject_id' id='subject_id' class='typeproforms'>";
		$subjectDropStr     .= "<option value=''>--select--</option>";
		if($subjectNumRows)
		{
			while($subjectSearchArr	=	$subjectSearchRes->fetch(PDO::FETCH_ASSOC))
			{
					if(isset($subjectId) && ($subjectId == $subjectSearchArr["subject_id"]))
					{	
						$subjectDropStr .= "<option value='{$subjectSearchArr["subject_id"]}' selected = 'selected'>";
						$subjectDropStr .= $subjectSearchArr["subject_name"];
						$subjectDropStr .= "</option>";
					}
					else
					{
						$subjectDropStr .= "<option value='{$subjectSearchArr["subject_id"]}'>";	
						$subjectDropStr .= $subjectSearchArr["subject_name"];
						$subjectDropStr .= "</option>";
					}	
			}
		}
		$subjectDropStr .= "</select>";
		echo $subjectDropStr;
	}	
?>