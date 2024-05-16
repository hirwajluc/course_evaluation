<?php
ob_start();
session_start();
/*Include the default function file*/
require_once("./includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
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

// Uploading the file
$connect = mysqli_connect("localhost", "root", "", "course_evaluation");
if(isset($_POST["submit"]))
{
 if($_FILES['file']['name'])
 {
  $filename = explode(".", $_FILES['file']['name']);
  if($filename[1] == 'csv')
  {
   $handle = fopen($_FILES['file']['tmp_name'], "r");
   $i = 0;
   while($data = fgetcsv($handle)){
   		if ($i > 0) {
   			$item1 = mysqli_real_escape_string($connect, $data[0]);  
	        $item2 = mysqli_real_escape_string($connect, $data[1]);
	        $item3 = mysqli_real_escape_string($connect, $data[2]);
	        $item4 = mysqli_real_escape_string($connect, $data[3]);
	        $item5 = mysqli_real_escape_string($connect, $data[4]);
	        $item6 = mysqli_real_escape_string($connect, $data[5]);
	        $item7 = mysqli_real_escape_string($connect, $data[6]);
	        $item8 = mysqli_real_escape_string($connect, $data[7]);
	        $query	= "INSERT INTO `tbl_subject` (`subject_id`, `subject_ac_year`, `subject_name`, `subject_code`, `subject_year`, `subject_semester`, `subject_group`, `subject_department_id`, `subject_teacher_id`)
				VALUES (NULL, '{$item1}', '{$item2}', '{$item3}', '{$item4}', '{$item5}', '{$item6}', '{$item7}', '{$item8}')";
	        mysqli_query($connect, $query);
   		}
   		$i++;
   }
   fclose($handle);
   echo "<script>alert('Subjects Saved Successfully');</script>";
  }
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
				Subject - Upload
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
			<form method = 'POST' action = "<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
			<table width ="550" border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td width = '170' height = '30'>
						<strong>Academic Year</strong>
						<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<input type="file" name="file" class = "btn-primary" accept=".csv, text/csv"/>
				</td>
			</tr><!-- Subject Academic Year Ends Here -->
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Upload Subject' />
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
</body>
</html>
<?php
	ob_end_flush();
?>