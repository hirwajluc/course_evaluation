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
	$successMsg		=	"Level Successfully Saved!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$level_year	=	trim($_POST['level']);
	$deptName	=	trim($_POST['department']);
	if($level_year	==	'' || $deptName	==	'')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';	
	}	
	else
	{	
		$selectQuery			=	"SELECT *  FROM `tbl_levels` WHERE `level_year` = '{$level_year}' AND `level_department` = '{$deptName}'";
		$existFlag				=	recordAlreadyExist($selectQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error! Level Already Registered!";
		}	
		else
		{	
			$deptInsertQuery	= 	"INSERT INTO `tbl_levels` (`level_id`, `level_year`, `level_department`) VALUES (NULL, '{$level_year}', '{$deptName}')";
			/*Call General Function to Insert the record*/
			$insertFlag			= 	insertOrUpdateRecord($deptInsertQuery , $_SERVER['PHP_SELF']);
			if(!$insertFlag)
			{
				$errorMsg		=	"Error!Unable to save The level!";
			}
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Level|New</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to Validation JS source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/validation.js'></script>
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
	<!-- Spry Stuff Starts here-->
	<link href="spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/textfieldvalidation/SpryValidationTextField.js"></script>
	<!-- Spry Stuff Ends Here-->
</head>
<body>
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo(basename(__FILE__));
	?>
	<div class="body">
		<div class="main_body">
			<?php levelGroupLinks(); ?>
			<h2>Level Year - New</h2>
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
				<td width = '120' height = '30'>
					<strong>Program Name  </strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield2">
						<?php selectDepartment();?>
					</span>	
				</td>
			</tr><!--End of department name row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Level Year  </strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<select name="level">
							<option selected disabled hidden>--Choose Level--</option>
							<?php selectLevel(); ?>
						</select>
					</span>	
				</td>
			</tr><!--End of Level Selection code row-->
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Add Level' />
				</td>
			</tr>
		  	</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('level_search.php');
			/*This function will list the departments*/
			echo listLevel();
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
</script>
</body>
</html>
<?php
	ob_end_flush();
?>