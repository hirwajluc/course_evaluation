<?php
error_reporting(0);
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
include './CodeGenerator.php';
global $connPDO;

if (isset($_POST['submit'])) {
	$ps = new CodeGenerator();
	$sem = $_POST['semester'];
	$ac_year = $_POST['ac_year'];
	$codesCount = $_POST['codes_num'];

	$getDep = $connPDO->query("SELECT * FROM tbl_department");
	while ($gotDep = $getDep -> fetch(PDO::FETCH_ASSOC)) {
		$dep_id = $gotDep['department_id'];

		$getYear = $connPDO->query("SELECT * FROM tbl_levels WHERE level_department = {$dep_id}");
		while ($gotYear = $getYear -> fetch(PDO::FETCH_ASSOC)) {
			$level_id = $gotYear['level_id'];
			$year = $gotYear['level_year'];

			$getGroup = $connPDO->query("SELECT * FROM tbl_classes WHERE class_level = {$level_id}");
			while ($gotGroup = $getGroup -> fetch(PDO::FETCH_ASSOC)) {
				$group = $gotGroup['class_group'];

				$codes[] =  $ps->generateCode($dep_id, $year, $sem, $group, $codesCount);
			}
		}
		?>
		<script type="text/javascript">
			window.alert('Codes are Generated Successfully!!');
			window.location = 'exportcode.php';	
		</script>
		<?php
	}

	$i = 1;
	foreach($codes as $x => $y){
		foreach ($y as $a){
			$connPDO->exec ("INSERT INTO tbl_codes SET code = '$a', academic_year = '$ac_year'");
		}
	}
	// while(list ($x, $y) = each ($codes)){
	//     foreach ($y as $a)
	        
	//    $connPDO->exec ("INSERT INTO tbl_codes SET code = '$a', academic_year = '$ac_year'");
	// }
}

?>
<?php
ob_start();
session_start();
/*Include the database configuration file*/
require_once("includes/config.php");
/*Include the default function file*/
require_once("includes/functions.php");
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
<body onLoad="document.getElementById('department').focus();">
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo(basename(__FILE__));
	?>
	<div class="body">
		<div class="main_body">
			<h2>
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
			<form method = 'POST' action = "<?php echo $_SERVER['PHP_SELF'];?>">
			<table border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td height = '30'>
						<strong>Academic Year</strong>
						<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<select name='ac_year' id='ac_year' class='typeproforms' required>
							<option value='' selected disabled>--Select Year--</option>
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
			</tr>
			<tr>
				<td height = '30'>
					<strong>Semester</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id = 'gender'>
						<select name='semester' id='semester' class='typeproforms' required>
							<option value='' selected disabled>--Select Semester--</option>
							<option value='1'>Semester 1</option>
							<option value='2'>Semester 2</option>						
						</select>
					</span>
				</td>
			</tr><!-- End of Gender row-->
			<tr>
				<td height = '30'>
						<strong>Codes per Class</strong>
						<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield2">
						<input type = 'text' name = 'codes_num' id = 'codesnum' class = 'typeproforms' placeholder="Number Of Codes per each Class" required/>
					</span>	
				</td>
			</tr><!-- Number of codes per Class Ends Here-->
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input class="btn-primary" type = 'submit' name = 'submit' class = 'button' value = 'Generate Codes' />
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

</body>
</html>
<?php
	ob_end_flush();
?>