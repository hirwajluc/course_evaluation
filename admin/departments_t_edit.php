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
	$successMsg		=	"Department Successfully Updated!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notupdated'))
{
	$errorMsg		=	"Error!Unable to Update Department!";	
}
if(isset($_POST['submit']))
{
	//echo '<pre>';print_r($_POST);die;	
	$deptCode	=	trim($_POST['department_code']);
	$deptName	=	trim($_POST['department_name']);
	$deptId		=	trim($_POST['department_id']);
	if($deptName	==	'')
	{
		$errorMsg	=	'Error! Department Name Should Not Be Left Blank!';	
	}	
	else
	{	
		$selectQuery			=	"SELECT *  FROM `tbl_t_departments` WHERE `dpt_id` = {$deptId} AND `dpt_code` LIKE '{$deptCode}' AND `dpt_name` LIKE '{$deptName}'";
		$existFlag				=	recordAlreadyExist($selectQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error! Department Name & Code Already Exist!";
		}	
		else
		{	
			$deptUpdateQuery	= 	"UPDATE `course_evaluation`.`tbl_t_departments` SET `dpt_code` = '{$deptCode}', `dpt_name` = '{$deptName}' WHERE 
									`tbl_t_departments`.`dpt_id` ={$deptId} LIMIT 1" ;
			/*call the function to update the department*/
			$updateFlag 		= insertOrUpdateRecord($deptUpdateQuery,$_SERVER['PHP_SELF'],$deptId);
			if(!$updateFlag)
			{
				$errorMsg		=	"Error!Unable to update Department!";
			}
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Department | Edit</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to Validation JS source File -->
	<!--<script type = 'text/javascript' language='javascript' src = 'js/validation.js'></script>-->
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
		echo plotHeaderMenuInfo("department_t_new.php");
	?>
	<div class="body">
		<div class="main_body">
			<h2>Department - Edit</h2>
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
					$id				=	$_GET['id'];
					$deptSelQry		=	"SELECT *  FROM `tbl_t_departments` WHERE `dpt_id`={$id}";
					$deptSelRes		=	$connPDO->query($deptSelQry);
					$deptSelNumRows	=	$deptSelRes->rowCount();
					if($deptSelNumRows)
					{
						$deptSelArr	=	$deptSelRes->fetch(PDO::FETCH_ASSOC);
						$deptId		=	$deptSelArr['dpt_id'];
						$deptCode	=	$deptSelArr['dpt_code'];
						$deptName	=	$deptSelArr['dpt_name'];
					}
				}
			?>	
			<form method = 'POST' action = "<?php echo $_SERVER['PHP_SELF'];?>">
			<table width ="550" border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td width = '120' height = '30'>
					<strong>Department Code  </strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<input type = 'text' name = 'department_code' id = 'department_code' 
						class = 'tinyforms' value = "<?php if(isset($deptCode))echo $deptCode;?>"/>
					</span>	
					<!-- <input type = 'hidden' name = 'department_code' id = 'department_code' 
					class = 'tinyforms'  value = "<?php //if(isset($deptCode))echo $deptCode;?>"/> -->
				</td>
				
			</tr><!--End of department code row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Department Name  </strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield2">
						<input type = 'text' name = 'department_name' id = 'department_name' 
						class = 'typeproforms' value = "<?php if(isset($deptName))echo $deptName;?>" />
					</span>	
					<input type = 'hidden' name = 'department_id' value = "<?php echo $deptId;?>"/>
				</td>
			</tr><!--End of department name row-->	
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Edit Department' />
				</td>
			</tr>
		  	</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('department_t_search.php');
			/*This function will list the departments*/
			echo listDepartments();
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