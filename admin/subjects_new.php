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
	$successMsg		=	"Subject Inforamtion Successfully Saved!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Subject Inforamtion Successfully Deleted!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted'))
{
	$errorMsg		=	"Error! Unable to Delete Subject Inforamtion!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$subjectAcYear			=	trim($_POST['ac_year']);
	$subjectName			=	trim($_POST['subject_name']);
	$subjectCode			=	trim($_POST['subject_code']);
	$year					=	trim($_POST['year']);
	$group					=	trim($_POST['group']);
	$semester				=	trim($_POST['semester']);
	$departmentId			=	trim($_POST['department']);
	$teacherId				=	trim($_POST['teacher_id']);
			
	if($subjectName	==	'' || $subjectCode == '' || $year	==	'' || $group == '' || $semester == '' || $departmentId == '' || $teacherId	==	'')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';		
	}
	else
	{	
		/*This query will search subcode in the same dept*/
		$selectQuery	=	"SELECT *  FROM `tbl_subject` WHERE (`subject_name` LIKE '{$subjectName}' OR 
							`subject_code` LIKE '{$subjectCode}') AND subject_department_id = {$departmentId} AND subject_ac_year = {$subjectAcYear}";	
		$existFlag		=	recordAlreadyExist($selectQuery);
		//if($existFlag)
		//{
		//	$errorMsg			=	"Error! Subject Name or Code  In the Same Department Already Exists!";
		//}
		//else
		//{
		
			/*Insert Subject Information*/
			$subjectInsertQuery	= "INSERT INTO `tbl_subject` (`subject_id`, `subject_ac_year`, `subject_name`, `subject_code`, `subject_year`, `subject_semester`, `subject_group`, `subject_department_id`, `subject_teacher_id`)
			VALUES (NULL, '{$subjectAcYear}', '{$subjectName}', '{$subjectCode}', '{$year}', '{$semester}', '{$group}', '{$departmentId}', '{$teacherId}')";

			/*Call General Function to Insert the record*/
			$insertFlag			= 	insertOrUpdateRecord($subjectInsertQuery,$_SERVER['PHP_SELF']);
			if(!$insertFlag)
			{
				$errorMsg		=	"Error!Unable to save Subject Information!";
			}
		//}	
	}
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Subject-New</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
	<!--Link to Validation JS source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/validation.js'></script>
	<!--Link to AJAX source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/ajax.js'></script>
	<!-- Spry Stuff Starts here-->
	<link href="spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/textfieldvalidation/SpryValidationTextField.js"></script>
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
<style type="text/css">
	.btn-primary{
		color:#fff;
		background-color:#337ab7;
		border-color:#2e6da4;
	}
	.btn-primary:focus{
		color:#fff;
		background-color:#286090;
		border-color:#122b40;
	}
	.btn-primary:hover{
		color:#fff;
		background-color:#286090;
		border-color:#204d74;
	}
</style>
</head>
<body onload = "plotTeacherByDept(<?php echo $departmentId;?>,<?php echo $teacherId;?>)">
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo(basename(__FILE__));
	?>
	<div class="body">
		<div class="main_body">
			<h2>
				Subject - New
				<a href="subjects_upload.php"><button class="btn-primary">Upload Subjects</button></a>
			</h2>
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
			<form method = 'POST' action = "<?php echo $_SERVER['PHP_SELF'];?>">
			<table width ="550" border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td width = '170' height = '30'>
						<strong>Academic Year</strong>
						<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<select name='ac_year' id='ac_year' class='typeproforms' required>
							<option value=''>--Select Year--</option>
							<?php
							for ($year=date('Y'); $year >= 2020 ; $year--) { 
								?>
								<option value="<?php echo $year.'-'.($year+1);?>"><?php echo $year."-".($year+1);?></option>
								<?php
							}
							?>
						</select>
					</span>		
				</td>
			</tr><!-- Subject Academic Year Ends Here -->	
			<tr>
				<td width = '170' height = '30'>
						<strong>Subject Name</strong>
						<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<input type = 'text' name = 'subject_name' id = 'subject_name' class = 'typeproforms' 
							value="<?php echo isset($subjectName)?$subjectName:'';?>" />
					</span>		
				</td>
			</tr><!-- Subject Name Ends Here -->	
			<tr>
				<td width = '170' height = '30'>
						<strong>Subject Code</strong>
						<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield2">
						<input type = 'text' name = 'subject_code' id = 'subject_code' class = 'tinyforms' 
							value="<?php echo isset($subjectCode)?$subjectCode:'';?>" />
					</span>	
				</td>
			</tr><!-- Subject Code Ends Here-->
			<tr>
				<td width = '170' height = '30'>
					<strong>Semester</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
				<span id = 'semester'>
					<?php
						$semester = (isset($semester))?$semester:'';
						echo plotSemesterDropdown($semSelValue = $semester);
					?>	
				</span>	
				</td>
			</tr><!-- Semester dropdown ends here-->
			<tr>
				<td width = '170' height = '30'>
					<strong>Program</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id = 'department'>
						<?php
							$departmentId = (isset($departmentId))?$departmentId:'';
							echo plotDepartmentDropdown($deptSelVal = $departmentId,$ajaxEnabled='no', 'no', 1);
						?>
					</span>	
				</td>
			</tr><!-- Department dropdown ends here-->
			<tr>
				<td width = '170' height = '30'>
					<strong>Level - Year</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id = 'level'>
						<?php
							$year = (isset($year))?$year:'';
							echo plotYearDropdown($yearSelValue = $year);
						?>
					</span>
				</td>
			</tr><!-- Year dropdown ends here-->
			
			<tr>
				<td width = '170' height = '30'>
					<strong>Class Group</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
				<span id = 'semester'>
					<?php
						$group = (isset($group))?$group:'';
						echo plotGroupDropdown($groupSelValue = $group);
					?>	
				</span>	
				</td>
			</tr><!-- Class Group dropdown ends here-->
			<tr>
				<td width = '170' height = '30'>
					<strong>Teacher Department</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id = 'department'>
						<?php
							$departmentId = (isset($departmentId))?$departmentId:'';
							echo plotTeacherDepartmentDropdown($deptSelVal = '',$ajaxEnabled='yes')
						?>
					</span>	
				</td>
			</tr><!-- Department dropdown ends here-->
			<tr>
				<td width = '170' height = '30'>
					<strong>Teacher Name</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<div id='teacher_name_div'>
						<select name='teacher_id' id='teacher_id' class='typeproforms'>
							<option value=''>--select--</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Add Subject' />
				</td>
			</tr>
		  	</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('subjects_search.php');
			/*This function will list the */
			echo listSubjects();
		?>
	<div class="clr"></div>
	</div><!-- End of Body div-->
</div><!--End of Main Div-->
<?php
	/*This function will return the footer div information*/
	echo plotFooterDiv();
?>
<script type="text/javascript">
	var sprytextfield1 	= new Spry.Widget.ValidationTextField("sprytextfield1", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield2 	= new Spry.Widget.ValidationTextField("sprytextfield2", "custom",{isRequired:true,characterMasking:/[a-zA-Z0-9 ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield3 	= new Spry.Widget.ValidationTextField("sprytextfield3", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "email", {isRequired:true,validateOn:["change"]});
	var department     	= new Spry.Widget.ValidationSelect("department", {validateOn:["change"]});
	var year    	   	= new Spry.Widget.ValidationSelect("year", {validateOn:["change"]});
	var semester       	= new Spry.Widget.ValidationSelect("semester", {validateOn:["change"]});
	var department      = new Spry.Widget.ValidationSelect("department", {validateOn:["change"]});
	//var teacher_id   	= new Spry.Widget.ValidationSelect("teacher_id", {validateOn:["change"]});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>