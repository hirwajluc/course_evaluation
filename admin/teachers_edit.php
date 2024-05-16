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
if(isset($_GET['msg']) && ($_GET['msg'] == 'success'))
{
	$successMsg		=	"Teacher's Inforamtion Successfully Saved!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Teacher's Inforamtion Successfully Deleted!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted'))
{
	$errorMsg		=	"Error! Unable to Delete Teacher's Inforamtion!";	
}
if(isset($_POST['submit']))
{	
	$teacherFirstName		=	trim($_POST['teacher_first_name']);
	$teacherLastName		=	trim($_POST['teacher_last_name']);
	//$employeeCode			=	trim($_POST['employee_code']);
	$departmentId			=	trim($_POST['department']);
	$teacherId				=	trim($_GET['id']);		
	
	if($teacherFirstName	==	'' || $teacherLastName == '' || $departmentId == '')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';	
	}
	else
	{
		/*Update Teacher Information*/
		$teacherUpdateQuery	= "UPDATE `tbl_teacher` SET `teacher_first_name` = '{$teacherFirstName}', `teacher_last_name` = '{$teacherLastName}', `teacher_department_id` = '$departmentId' WHERE `tbl_teacher`.`teacher_id` = $teacherId";	
		/*Call General Function to Insert the record*/
		$insertFlag			= 	insertOrUpdateRecord($teacherUpdateQuery,$_SERVER['PHP_SELF'],$teacherId);
		if(!$insertFlag)
		{
			$errorMsg		=	"Error!Unable to save Teacher Information!";
		}
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Teacher-New</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to Validation JS source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/validation.js'></script>
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
	<!-- Spry Stuff Starts here-->
	<link href="spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/textfieldvalidation/SpryValidationTextField.js"></script>
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
</head>
<body>
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo("teachers_new.php");
	?>
	<div class="body">
		<div class="main_body">
			<h2>Teacher - Edit</h2>
			<?php
				/*Display the Messages*/
				if(isset($errorMsg))
				{
					echo "<span class = 'error'>{$errorMsg}</span>";	
				}
				elseif(isset($successMsg))
				{
					echo "<span class = 'success'>{$successMsg}</span>";	
				}
			?>
			<br/>
			<?php
				if(isset($_GET['id']))
				{
					$id					=	$_GET['id'];
					$teacherSelQry		=	"SELECT * FROM tbl_teacher INNER JOIN 
												 tbl_t_departments ON 
												 tbl_teacher.teacher_department_id = tbl_t_departments.dpt_id 
												 WHERE tbl_teacher.teacher_id={$id}";
										
					$teacherSelRes		=	$connPDO->query($teacherSelQry);
					$teacherSelNumRows	=	$teacherSelRes->rowCount();
					if($teacherSelNumRows)
					{
						$teacherSelArr	=	$teacherSelRes->fetch(PDO::FETCH_ASSOC);
						//echo '<pre>';print_r($teacherSelArr);						
						$teacherFirstName = $teacherSelArr['teacher_first_name'];
						$teacherLastName  = $teacherSelArr['teacher_last_name'];
						$teacherEmpCode   = $teacherSelArr['teacher_emp_code'];
						$teacherDepName   = $teacherSelArr['dpt_name'];
						$departmentId  	  = $teacherSelArr['dpt_id'];
					}
				}
			?>
			<form method = 'POST' action = "<?php echo $_SERVER['PHP_SELF'].'?id='.$_GET['id']?>">
			<table width ="550" border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td width = '170' height = '30'>
					<strong>Teacher's First Name</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<input type = 'text' name = 'teacher_first_name' id = 'teacher_first_name' class = 'typeproforms' 
						value = "<?php echo $teacherFirstName;?>" />
					</span>	
				</td>
			</tr>	
			<tr>
				<td width = '170' height = '30'>
					<strong>Teacher's Last Name</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield2">
						<input type = 'text' name = 'teacher_last_name' id = 'teacher_last_name' class = 'typeproforms' 
						value = "<?php echo $teacherLastName;?>" />
					</span>	
				</td>
			</tr>
			<!-- <tr>
				<td width = '170' height = '30'>
					<strong>Employee Code</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield3">
						<input type = 'text' name = 'employee_code' id = 'employee_code' class = 'tinyforms' 
						value = "<?php //echo $teacherEmpCode;?>" />
					</span>	
				</td>
			</tr> -->
			<tr>
				<td width = '170' height = '30'>
					<strong>Department</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="department">
						<?php
							$departmentId = (isset($departmentId))?$departmentId:'';
							echo plotMainDepartmentDropdown($deptSelVal = $departmentId);
						?>
					</span>	
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Edit Teacher' />
				</td>
			</tr>
		  	</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('teachers_search.php');
			/*This function will list the departments*/
			echo listTeacher();
		?>		
	<div class="clr"></div>
	</div><!-- End of Body div-->
</div><!--End of Main Div-->
<?php
	/*This function will return the footer div information*/
	echo plotFooterDiv();
?>
<script type="text/javascript">
	var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
						useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
						useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "custom",{isRequired:true,characterMasking:/[a-zA-Z0-9 ]/,
						useCharacterMasking:true, validateOn:["change"]});
	var department     = new Spry.Widget.ValidationSelect("department", {validateOn:["change"]});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>