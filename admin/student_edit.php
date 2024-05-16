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

if(isset($_GET['msg']) && ($_GET['msg'] == 'success'))
{
	$successMsg		=	"Successfully Updated!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Successfully Deleted!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$stuId				=	intval(trim($_GET['id']));
	$stuDept			=	trim($_POST['department']);
	$stuYear			=	trim($_POST['year']);
	$stuSemester		=	trim($_POST['semester']);
	$stuRegNumber		=	trim($_POST['reg_number']);
	$stuRegNumber		=	trim($_POST['reg_number']);
	$firstName			=	trim($_POST['first_name']);
	$lastName			=	trim($_POST['last_name']);
	$stuEmail			=	trim($_POST['email']);
	
	$userMD5Pass	=	md5($stuRegNumber);/*Reg Number is the password for the student at the first time*/
	
	if($stuRegNumber	==	'' || $stuEmail	==	'' || $firstName	==	'' || $lastName	==	'')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';
	}	
	else
	{		
		$stuSearchQuery			=	"SELECT *  FROM `tbl_users` WHERE (`u_uname` LIKE '{$stuEmail}' OR `stud_regno` LIKE '{$stuRegNumber}') AND `u_id` <>{$stuId}";
		$existFlag				=	recordAlreadyExist($stuSearchQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error!Information already Exists!";
		}	
		else
		{	
			/*Update Subject Information*/
			$updateQuery	=	"UPDATE `tbl_users` SET  `u_fname` = '{$firstName}', `u_lname` = '{$lastName}' , `stud_dept` = '{$stuDept}' , `stud_year` = '{$stuYear}' , `stud_sem` = '{$stuSemester}', `stud_regno` = '{$stuRegNumber}',`u_uname` = '{$stuEmail}',`u_pass` = '{$userMD5Pass}' WHERE `tbl_users`.`u_id` = {$stuId}";
			/*Call General Function to Insert the record*/
			$updateFlag			= 	insertOrUpdateRecord($updateQuery,$_SERVER['PHP_SELF'],$stuId);
			if(!$updateFlag)
			{
				$errorMsg		=	"Error!Unable to Edit Student Information!";
			}
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Student Edit</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
<body onLoad="document.getElementById('department').focus();">
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo("student_new.php");
	?>
	<div class="body">
		<div class="main_body">
			<h2>Student Edit</h2>
			<?php
				/*Display the Messages*/
				if(isset($errorMsg))
				{
					echo "<p><span class = 'error'>{$errorMsg}</span>";	
				}
				elseif(isset($successMsg))
				{
					echo "<p><span class = 'success'>{$successMsg}</span></p>";	
				}
			?>
			<br/>
			<?php
				if(isset($_GET['id']))
				{
					$id					=	$_GET['id'];
					$studentSelQry		=	"SELECT *  FROM `tbl_users` WHERE `u_id` ={$id} AND `u_utype` LIKE 'student'";
					$studentSelRes		=	$connPDO->query($studentSelQry);
					$studentSelNumRows	=	$studentSelRes->rowCount();
					if($studentSelNumRows)
					{
						$studentSelArr	=	$studentSelRes->fetch(PDO::FETCH_ASSOC);
						//echo '<pre>';print_r($studentSelArr);echo '</pre>';
						$stuId			=	$studentSelArr['u_id'];
						$stuDept		=	$studentSelArr['stud_dept'];
						$stuYear		=	$studentSelArr['stud_year'];
						$stuSemester	=	$studentSelArr['stud_sem'];
						$stuRegNumber	=	$studentSelArr['stud_regno'];
						$firstName		=	$studentSelArr['u_fname'];
						$lastName		=	$studentSelArr['u_lname'];
						$stuEmail		=	$studentSelArr['u_uname'];
					}
				}
			?>	
			<form method = 'POST' action = "<?php echo "{$_SERVER['PHP_SELF']}?id={$_GET['id']}"?>">
			<table  border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td width = '120' height = '30'>
					<strong>Department</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'department'>
						<?php
							echo plotDepartmentDropdown($deptSelVal = $stuDept,$ajaxEnabled='no');
						?>
					 </span>	
				</td>
			</tr><!-- End of department row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Year</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'year'>
						<?php
							echo plotYearDropdown($stuYear);
						?>
					</span>
				</td>
			</tr><!-- End of year row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Semester</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'semester'>
						<?php
							echo plotSemesterDropdown($stuSemester);
						?>
					</span>	
				</td>
			</tr><!-- End of semester row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Reg Number</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield1">
						<input type = 'text' name = 'reg_number' id = 'reg_number' class = 'typeproforms'
						value = "<?php if(isset($stuRegNumber))echo $stuRegNumber;?>"	/>
					</span>	
				</td>
			</tr><!-- End of Reg Number row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>First Name</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield2">
						<input type = 'text' name = 'first_name' id = 'first_name' class = 'typeproforms' 
						value = "<?php if(isset($firstName))echo $firstName;?>" />
					</span>	
				</td>
			</tr><!-- End First Name row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Last Name</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield3">
						<input type = 'text' name = 'last_name' id = 'last_name' class = 'typeproforms' 
						value = "<?php if(isset($lastName))echo $lastName;?>" />
					</span>	
				</td>
			</tr><!-- End Last Name row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Email</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield4">
						<input type = 'text' name = 'email' id = 'email' class = 'typeproforms' 
						value = "<?php if(isset($stuEmail))echo $stuEmail;?>" />
					</span>	
				</td>
			</tr><!-- End email row-->
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Register' />
				</td>
			</tr>
			</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			echo plotLogoDiv();
			echo plotSearchDiv($searchActionFile = 'student_search.php');
			/*This function will list the departments*/
			echo listStudents();
		?>
		<div class="clr"></div>
	</div><!-- End of Body div-->
</div><!--End of Main Div-->
<?php
	/*This function will return the footer div information*/
	echo plotFooterDiv();
?>
<script type="text/javascript">
	var department     	= new Spry.Widget.ValidationSelect("department", {validateOn:["change"]});
	var year    	   	= new Spry.Widget.ValidationSelect("year", {validateOn:["change"]});
	var semester       	= new Spry.Widget.ValidationSelect("semester", {validateOn:["change"]});
	var sprytextfield1 	= new Spry.Widget.ValidationTextField("sprytextfield1", "custom",{isRequired:true,characterMasking:/[a-zA-Z0-9 ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield2 	= new Spry.Widget.ValidationTextField("sprytextfield2", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield3 	= new Spry.Widget.ValidationTextField("sprytextfield3", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "email", {isRequired:true,validateOn:["change"]});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>