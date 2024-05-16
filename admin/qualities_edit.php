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
	$successMsg		=	"Quality Successfully Updated!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notupdated'))
{
	$errorMsg		=	"Error!Unable to Update Quality!";	
}
if(isset($_POST['submit']))
{
	//echo '<pre>';print_r($_POST);die;	
	$qualities		=	trim($_POST['qualities']);
	$qualityId		=	trim($_POST['quality_id']);
	if($qualities	==	'')
	{
		$errorMsg	=	'Error! Quality Should Not Be Left Blank!';	
	}	
	else
	{	
		$selectQuery			=	"SELECT *  FROM `tbl_quality` WHERE `quality` LIKE '$qualities'   AND `quality_id` <>{$qualityId}";
		$existFlag				=	recordAlreadyExist($selectQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error! Quality Already Exists!";
		}	
		else
		{
			$qualityUpdateQuery	= 	"UPDATE `course_evaluation`.`tbl_quality` SET `quality` = '{$qualities}' WHERE `tbl_quality`.`quality_id` ={$qualityId} LIMIT 1" ;
			/*call the function to update the department*/
			$updateFlag 			= insertOrUpdateRecord($qualityUpdateQuery,$_SERVER['PHP_SELF'],$qualityId);
			if(!$updateFlag)
			{
				$errorMsg		=	"Error!Unable to update Quality!";
			}
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Quality - Edit</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
	<!-- Spry Stuff Starts here-->
	<link href="spry/textareavalidation/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="spry/textareavalidation/SpryValidationTextarea.js"></script>
	<!-- Spry Stuff ends here-->
</head>
<body>
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo("qualities_new.php");
	?>
	<div class="body">
		<div class="main_body">
			<h2>Quality - Edit</h2>
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
					$id				=	intval($_GET['id']);
					$qualitySelQry	=	"SELECT *  FROM `tbl_quality` WHERE `quality_id`={$id}";
					$qualitySelRes	=	$connPDO->query($qualitySelQry);
					$qualityNumRows	=	$qualitySelRes->rowCount();
					if($qualityNumRows)
					{
						$qualitySelArr	=	$qualitySelRes->fetch(PDO::FETCH_ASSOC);
						$qualities		=	$qualitySelArr['quality'];
						$qualityId		=	$qualitySelArr['quality_id'];
					}
				}
			?>	
			<form method = 'POST' action = "<?php echo $_SERVER['PHP_SELF'];?>">
			<table width ="550" border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td width = '70' height = '30'>
					<strong>Quality  </strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="TextArea1">
						<textarea class='typeproforms' name = 'qualities' id = 'qualities' rows = '5' cols='85'><?php echo trim($qualities);?></textarea>
						<input type = 'hidden' name = 'quality_id' value = "<?php echo $qualityId;?>"/>
						<span class="textareaRequiredMsg">Please Enter Quality!</span>
						<span class="textareaMinCharsMsg">Please enter at least 10 characters!</span>
						<span id="Countvalidta1"> </span> / 200
					</span>	
				</td>
			</tr>	
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Edit Quality' />
				</td>
			</tr>
		  	</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('qualities_search.php');
			/*This function will list the departments*/
			echo listQualities();
		?>
	<div class="clr"></div>
	</div><!-- End of Body div-->
</div><!--End of Main Div-->
<?php
	/*This function will return the footer div information*/
	echo plotFooterDiv();
?>
</body>
<script type="text/javascript">
var TextArea1 = new Spry.Widget.ValidationTextarea("TextArea1", {useCharacterMasking:true, minChars:10, maxChars:200,
				counterType:"chars_count", counterId:	"Countvalidta1", validateOn:["change"]});
</script>
</html>
<?php
	ob_end_flush();
?>