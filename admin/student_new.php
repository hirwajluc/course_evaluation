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
	$successMsg		=	"Successfully Saved!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Successfully Deleted!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'migrate'))
{
	$successMsg		=	"Students Details Successfully Migrated!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$department		=	trim($_POST['department']);
	$year			=	trim($_POST['year']);
	$semester		=	trim($_POST['semester']);
	$regNumber		=	trim($_POST['reg_number']);
	$firstName		=	trim($_POST['first_name']);
	$lastName		=	trim($_POST['last_name']);
	$age			=	trim($_POST['age']);
	$email			=	trim($_POST['email']);
	$gender			=	trim($_POST['gender']);
	$sponsor		=	trim($_POST['sponsor']);
	$desab			=	trim($_POST['desab']);
	$school			=	trim($_POST['school']);
	
	$userMD5Pass	=	md5($regNumber);/*Reg Number is the password for the student at the first time*/
	
	if($regNumber	==	'' || $email	==	'' || $firstName	==	'' || $lastName	==	'' || $age	==	'')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';	
	}	
	else
	{		
		$stuSearchQuery			=	"SELECT *  FROM `tbl_users` WHERE (`u_uname` LIKE '{$email}' OR `stud_regno` LIKE '{$regNumber}')";
		$existFlag				=	recordAlreadyExist($stuSearchQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error!Information already Exists!";
		}	
		else
		{	
			$stuInsertQry	=	"INSERT INTO `tbl_users` (`u_id`, `u_fname`, `u_lname`, `u_uname`, `u_pass`, `u_utype`, `stud_year`, `stud_dept`, `stud_sem`, `stud_regno`, `gender`, `sponsorship`, `desability`, `age`) VALUES (NULL, '{$firstName}', '{$lastName}', '{$email}', '{$userMD5Pass}', 'student', '{$year}', '{$department}', '{$semester}', '{$regNumber}', '{$gender}', '{$sponsor}', '{$desab}', '{$age}')";

//print "sql=".$stuInsertQry;
//exit;			
                        /*Call General Function to Insert the record*/
			$insertFlag			= 	insertOrUpdateRecord($stuInsertQry,$_SERVER['PHP_SELF']);
			if(!$insertFlag)
			{
				$errorMsg		=	"Error!Unable to save Student Information!";
			}
			
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Student New</title>
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
		echo plotHeaderMenuInfo(basename(__FILE__));
	?>
	<div class="body">
		<div class="main_body">
			<h2>
				<?php
				if ($_SESSION['u_utype'] == "admin") {
					?>
					<a href="codes.php" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Generate Codes</a>||
					
					<!-- <a href="codes_report.php" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					View Codes</a>|| -->

					<a href="exportcode.php" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Exporting codes</a>||

					<a href="evaluation_report.php" target="new" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Evaluation Report</a>
					<?php
				} elseif ($_SESSION['u_utype'] == "coladmin") {
					?>
					<a href="codes.php" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Generate Codes</a>||
					<a href="exportcode.php" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Exporting codes</a>||
					<a href="evaluation_report.php" target="new" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Evaluation Report</a>
					<?php
				} else{
					?>
					<a href="exportcode.php" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Exporting codes</a>||
					<a href="evaluation_report.php" target="new" style='background-color:#a5d3f4; color:white;
					border:1px solid white;
					border-radius:2px 4px 6px 8px;
					box-shadow:3px 4px 5px 6px;'>
					Evaluation Report</a>
					<?php
				}
				?>
					
					
					
					<? //Ending By Nepo ?>
					

			</h2>
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
			<form method = 'POST' action = "<?php echo $_SERVER['PHP_SELF'];?>" hidden>
			<table  width = '300' border = '0' cellspacing = '0' cellpadding = '0'>
			
			<tr>
				<td width = '120' height = '30'>
					<strong>Gender</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'gender'>
						<?php
							$gender = isset($gender)?$gender:'';
							echo plotGenderDropdown($gender);
						?>
					</span>
				</td>
			</tr><!-- End of Gender row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Sponsorship</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'sponsor'>
						<?php
							$sponsor = isset($sponsor)?$sponsor:'';
							echo plotSpoDropdown($sponsor);
						?>
					</span>
				</td>
			</tr><!-- End of sponsorship row-->

			<tr>
				<td width = '120' height = '30'>
					<strong>Desability</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'desab'>
						<?php
							$desab = isset($desab)?$desab:'';
							echo plotDesabDropdown($desab);
						?>
					</span>
				</td>
			</tr><!-- End of sponsorship row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Department</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'department'>
						<?php
							$department = isset($department)?$department:'';
							echo plotDepartmentDropdown($deptSelVal = $department,$ajaxEnabled='no');
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
							$year = isset($year)?$year:'';
							echo plotYearDropdown($year);
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
							$semester = isset($semester)?$semester:'';
							echo plotSemesterDropdown($semester);
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
						value = "<?php if(isset($regNumber))echo $regNumber;?>"	/>
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
					<strong>Your Age</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield3">
						<input type = 'text' name = 'age' id = 'age' class = 'typeproforms' 
						value = "<?php if(isset($$age))echo $$age;?>" />
					</span>
				</td>
			</tr><!-- End Age row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Email</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield4">
						<input type = 'text' name = 'email' id = 'email' class = 'typeproforms' 
						"<?php if(isset($email))echo $email;?>" />
						<span class="textfieldInvalidFormatMsg">Invalid Email!</span>
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
			echo <<<ABC
				<!--<div id='migrate'>
				<form method = 'post' action = 'migrate.php' >	
				<input type = 'submit' class = 'button' value= 'Migrate Students'
				onclick="javascript: return confirm('Sure! Do you want to Migrate Students?');"/>
				</form>-->
			</div><!-- Migrate Button -->
ABC;
			//echo plotSearchDiv($searchActionFile = 'student_search.php');
			/*This function will list the departments*/
			//echo listStudents();
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
	var sprytextfield1 	= new Spry.Widget.ValidationTextField("sprytextfield1", "custom",
						  {isRequired:true,characterMasking:/[a-zA-Z0-9 ]/,useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield2 	= new Spry.Widget.ValidationTextField("sprytextfield2", "custom",
						  {isRequired:true,characterMasking:/[a-zA-Z ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield3 	= new Spry.Widget.ValidationTextField("sprytextfield3", "custom",
						 {isRequired:true,characterMasking:/[a-zA-Z ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "email", {isRequired:true,validateOn:["change"]});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>