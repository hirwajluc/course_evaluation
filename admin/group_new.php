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
	$successMsg		=	"Group Successfully Saved!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$group	=	trim($_POST['group']);
	$level	=	trim($_POST['level']);
	if($group	==	'' || $level	==	'')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';	
	}	
	else
	{	
		$selectQuery			=	"SELECT *  FROM `tbl_classes` WHERE `class_level` = '{$level}' AND `class_group` = '{$group}'";
		$existFlag				=	recordAlreadyExist($selectQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error! Group Already Registered!";
		}	
		else
		{	
			$deptInsertQuery	= 	"INSERT INTO `tbl_classes` (`class_id`, `class_group`, `class_level`) VALUES (NULL, '{$group}', '{$level}')";
			/*Call General Function to Insert the record*/
			$insertFlag			= 	insertOrUpdateRecord($deptInsertQuery , $_SERVER['PHP_SELF']);
			if(!$insertFlag)
			{
				$errorMsg		=	"Error!Unable to save The Group!";
			}
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Department-New</title>
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
			<h2>Group - New</h2>
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
					<strong>Choose Level  </strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield2">
						<?php selectLevelOption();?>
					</span>	
				</td>
			</tr><!--End of Level Selection row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Choose Group  </strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<select name="group">
							<option selected disabled hidden>--Choose Group--</option>
							<?php selectGroup(); ?>
						</select>
					</span>	
				</td>
			</tr><!--End of Group Letter Selection code row-->
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Add Group' />
				</td>
			</tr>
		  	</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('group_search.php');
			/*This function will list the departments*/
			echo listGroup();
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