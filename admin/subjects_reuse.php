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
	//echo '<pre>';print_r($_POST);//die;
	$subjectAcYear			=	trim($_POST['ac_year']);
	$subjectName			=	trim($_POST['subject_name']);
	$subjectCode			=	trim($_POST['subject_code']);
	$year					=	trim($_POST['year']);
	$group					=	trim($_POST['group']);
	$semester				=	trim($_POST['semester']);
	$departmentId			=	trim($_POST['department']);
	$teacherId				=	trim($_POST['teacher_id']);
	$subjectId				=	intval($_GET['id']);		
	
	if($subjectName	==	'' || $subjectCode == '' || $year	==	''|| $group	==	'' || $group == '' || $departmentId == '' || $teacherId	==	'')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';		
	}
	else
	{	
		$selectQuery	=	"SELECT *  FROM `tbl_subject` WHERE `subject_name` LIKE '{$subjectName}' AND
							`subject_ac_year` = '{$subjectAcYear}' AND `subject_code` LIKE '{$subjectCode}' AND 
							`subject_year` LIKE '{$year}'AND `subject_group` = $group AND `subject_semester` = $semester AND 
							`subject_department_id` = $departmentId AND `subject_teacher_id` = $teacherId  AND
							`subject_id` <>{$subjectId}"; 
		$existFlag		=	recordAlreadyExist($selectQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error! Subject Already Exist!";
		}
		else
		{
			/*Update Subject Information*/
			$subjectInsertQuery	=	"INSERT INTO `tbl_subject` SET `subject_ac_year` = '{$subjectAcYear}' , `subject_name` = '{$subjectName}' , `subject_code` = '{$subjectCode}',
									`subject_year` = '{$year}',`subject_group` = '{$group}',`subject_semester` = '{$semester}', `subject_department_id` = '{$departmentId}', `subject_teacher_id` = '{$teacherId}'"; 
			
			/*Call General Function to Insert the record*/
			$updateFlag			= 	insertOrUpdateRecord($subjectInsertQuery,$_SERVER['PHP_SELF'],$subjectId);
			if(!$updateFlag)
			{
				$errorMsg		=	"Error!Unable to Edit Subject Information!";
			}
		}	
	}
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Subject-Edit</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
	<!--Link to Validation JS source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/validation.js'></script>
	<!--Link to AJAX source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/ajax.js'></script>
	<script type = 'text/javascript' language='javascript' src = 'js/ajax.js'></script>
	<!-- Spry Stuff Starts here-->
	<link href="spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/textfieldvalidation/SpryValidationTextField.js"></script>
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
</head>
<?php
	if(isset($_GET['id']))
	{
		$id				=	$_GET['id'];
		$answerSelQry	=	"SELECT *  FROM `tbl_subject` WHERE subject_id={$id}";
		$answerSelRes	=	$connPDO->query($answerSelQry);
		$answerNumRows	=	$answerSelRes->rowCount();
		if($answerNumRows)
		{
			$answerSelArr	=	$answerSelRes->fetch(PDO::FETCH_ASSOC);
			//echo '<pre>';print_r($answerSelArr);die;
			$subjectAcYear				=	$answerSelArr['subject_ac_year'];
			$subjectName				=	$answerSelArr['subject_name'];
			$subjectCode				=	$answerSelArr['subject_code'];
			$subjectYear				=	$answerSelArr['subject_year'];
			$subjectSemester			=	$answerSelArr['subject_semester'];
			$subjectDepartmentId		=	$answerSelArr['subject_department_id'];
			$subjectTeacherId			=	$answerSelArr['subject_teacher_id'];
		}	$Subjectgroup				=	$answerSelArr['subject_group'];
	}
?>
<body  onload = "plotTeacherByDept(<?php echo $subjectDepartmentId;?>,<?php echo $subjectTeacherId;?>)">
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo("subjects_new.php");
	?>
	<div class="body">
		<div class="main_body">
			<h2>Subject-Reuse</h2>
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
			<form method = 'POST' action = "<?php echo "{$_SERVER['PHP_SELF']}?id={$_GET['id']}"?>">
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
									for ($year=date('Y'); $year >= 2018 ; $year--) {
										$yearRange = $year.'-'.($year+1);
										if ($yearRange == $subjectAcYear) {
											?>
											<option selected value="<?php echo $yearRange;?>"><?php echo $yearRange;?></option>
											<?php
										} else {
											?>
											<option value="<?php echo $yearRange;?>"><?php echo $yearRange;?></option>
											<?php
										}
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
							<strong>Program</strong>
						</td>
						<td height = '30'>
							<span id = 'department'>
								<?php
									echo plotDepartmentDropdown($deptSelVal = $subjectDepartmentId,$ajaxEnabled='no',$subjectAjaxEnabled='no', 1)
								?>
							</span>
						</td>
					</tr><!-- Department dropdown ends here-->
					<tr>
						<td width = '170' height = '30'>
							<strong>Year</strong>
							<span class = 'mandatory'>*</span>
						</td>
						<td height = '30'>
							<span id = 'year'>
								<?php
									echo plotYearDropdown($subjectYear);
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
									echo plotGroupDropdown($Subjectgroup);
								?>
							</span>		
						</td>
					</tr><!-- Semester dropdown ends here-->
					<tr>
						<td width = '170' height = '30'>
							<strong>Semester</strong>
							<span class = 'mandatory'>*</span>
						</td>
						<td height = '30'>
							<span id = 'semester'>
								<?php
									echo plotSemesterDropdown($subjectSemester);
								?>
							</span>		
						</td>
					</tr><!-- Semester dropdown ends here-->
					<tr>
						<td width = '170' height = '30'>
							<strong>Teacher Dpt/Prog</strong>
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
							<input type = 'submit' name = 'submit' class = 'button' value = 'Save Subject' />
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
		?><!-- End of Search Div-->
	</div><!-- End of Body div-->
	<?php
		/*This function will list the */
		echo listSubjects();
	?>
	<div style='clear:both'></div>
</div><!--End of Main Div-->	
<?php
	/*This function will return the footer div information*/
	echo plotFooterDiv();
?>
</body>
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
</html>
<?php
	ob_end_flush();
?>