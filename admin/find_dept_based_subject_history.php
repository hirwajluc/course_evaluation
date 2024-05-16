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
		$deptCode			= 	($_GET['deptId'] !='')?$_GET['deptId']:0;	
		if(isset($_GET['subjectId']))
		{
			$subjectId			= $_GET['subjectId'];
		}
		$subjectSearchQry	=	"SELECT *  FROM `classwise_history` WHERE `dept_code` LIKE '{$deptCode}' AND 
								`year` LIKE '{$year}' AND `semester` LIKE '{$semester}' 
								GROUP BY dept_code , subject_name ORDER BY id;";
		$subjectSearchRes	=	$connPDO->query($subjectSearchQry);
		$subjectNumRows		=	$subjectSearchRes->rowCount();
		$subjectDropStr 	= "<select name='subject_id' id='subject_id' class='typeproforms'>";
		$subjectDropStr     .= "<option value=''>--select--</option>";
		if($subjectNumRows)
		{
			while($subjectSearchArr	=	$subjectSearchRes->fetch(PDO::FETCH_ASSOC))
			{	
					//var_dump($subjectId);//echo '<pre>';print_r($subjectSearchArr);//die;
					if(isset($subjectId) && ($subjectId == $subjectSearchArr["subject_name"]))
					{	
						$subjectDropStr .= "<option value='{$subjectSearchArr["subject_name"]}' selected = 'selected'>";
						$subjectDropStr .= $subjectSearchArr["subject_name"];
						$subjectDropStr .= "</option>";
					}
					else
					{
						$subjectDropStr .= "<option value='{$subjectSearchArr["subject_name"]}'>";	
						$subjectDropStr .= $subjectSearchArr["subject_name"];
						$subjectDropStr .= "</option>";
					}	
			}
		}
		$subjectDropStr .= "</select>";
		echo $subjectDropStr;
	}	
?>