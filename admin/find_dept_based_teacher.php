<?php
	/*Include the database configuration file*/
	require_once("./includes/functions.php");
	/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";

	global $connPDO;
	if(isset($_GET['id']))
	{
		$teacherId		= isset($_GET['teacherId'])?$_GET['teacherId']:'';	
		$deptId			=	intval($_GET['id']);
		$deptSearchQry	=	"SELECT tbl_teacher.*, tbl_t_departments.* 
		FROM tbl_teacher
		JOIN tbl_t_departments
		ON teacher_department_id = dpt_id
		WHERE teacher_department_id = {$deptId} ORDER BY tbl_teacher.teacher_first_name";
		$deptSearchRes	=	$connPDO->query($deptSearchQry);
		$deptNumRows	=	$deptSearchRes->rowCount();
		$teacherDropStr = '';
		$teacherDropStr .= "<select name='teacher_id' id='teacher_id' class='typeproforms'>";
		$teacherDropStr .= "<option value=''>--select teacher--</option>";
		if($deptNumRows) {
			while($deptSearchArr	=	$deptSearchRes->fetch(PDO::FETCH_ASSOC))
			{
				if($teacherId == $deptSearchArr["teacher_id"])
				{	
					$teacherDropStr .= "<option value='{$deptSearchArr["teacher_id"]}' selected = 'selected'>".$deptSearchArr["teacher_first_name"]." ".$deptSearchArr["teacher_last_name"]."</option>";
				}
				else
				{
					$teacherDropStr .= "<option value='{$deptSearchArr["teacher_id"]}'>".$deptSearchArr["teacher_first_name"]." ".$deptSearchArr["teacher_last_name"]."</option>";	
				}
			}
		}
		$teacherDropStr .= "</select>";
		echo $teacherDropStr;
	}	
?>