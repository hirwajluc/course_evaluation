<?php
ob_start();
session_start();
/*Include the default function file*/
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
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
	<title>Exporting Codes</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
	<script type = 'text/javascript' language='javascript' src = 'js/jquery-2.0.3.min.js'></script>
	<!--Link to AJAX source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/ajax.js'></script>
	<!-- Spry Stuff Starts here-->
	<!-- Spry Stuff Starts here-->
	<link href="spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
	<link href="spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="spry/textfieldvalidation/SpryValidationTextField.js"></script>
	<script type="text/javascript" src="spry/selectvalidation/SpryValidationSelect.js"></script>
	<!-- Spry Stuff Ends Here-->
	<style type="text/css">
		a:hover{
			color:black;
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
			<h1><center><strong>Exporting Evaluation Codes</strong></center></h1>
			<h4>
				<form method="POST" action="">
					<label>Academic Year</label>
					<select name="semester" id="ac_year">
						<option selected disabled hidden>--Acad Year--</option>
						<?php
							for ($year=date('Y'); $year >= 2020 ; $year--) { 
								?>
								<option value="<?php echo $year.'-'.($year+1);?>"><?php echo $year."-".($year+1);?></option>
								<?php
							}
						?>
					</select><br>
					<label>Select Semester</label>
					<select name="semester" id="semester">
						<option selected disabled hidden>--Semester--</option>
					</select>
				</form>
			</h4>
			<h2 id="content_header">
					
					
			</h2>
			<h2>
				<a href="student_new.php">Close the Exportation</a>
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

<script>
	$(document).ready(function () {
		$('#ac_year').change(function () {
			var yr = $('#ac_year').val();
			if (yr != ''){
				$.ajax({
					url:"exportcodeYear.php",
					method:"POST",
					data:{yr:yr},
					success:function (data) {
						$('#semester').html(data);
					}
				})
			}
		});
	});
	$(document).ready(function () {
		$('#semester').change(function () {
			var sem = $('#semester').val();
			var yr = $('#ac_year').val();
			if (sem != ''){
				$.ajax({
					url:"exportcodeSem.php",
					method:"POST",
					data:{sem:sem, yr:yr},
					success:function (data) {
						$('#content_header').html(data);
					}
				})
			}
		});
	});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>