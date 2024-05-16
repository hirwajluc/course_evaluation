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
	$successMsg		=	"Answer Successfully Saved!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Answer Successfully Deleted!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted'))
{
	$errorMsg		=	"Error! Unable to Delete Answer!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$answerType		=	trim($_POST['answer_type']);
	if($answerType	==	'')
	{
		$errorMsg	=	'Error! Answer Should Not Be Left Blank!';	
	}	
	else
	{	
		$selectQuery			=	"SELECT *  FROM `tbl_answer` WHERE `answer_type` LIKE '{$answerType}'";
		$existFlag				=	recordAlreadyExist($selectQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error! Answer Already Exists!";
		}	
		else
		{	
			$answerInsertQuery	= "INSERT INTO `course_evaluation`.`tbl_answer` (`answer_id`, `answer_type`) VALUES (NULL, '{$answerType}')";
			/*Call General Function to Insert the record*/
			$insertFlag			= 	insertOrUpdateRecord($answerInsertQuery,$_SERVER['PHP_SELF']);
			if(!$insertFlag)
			{
				$errorMsg		=	"Error!Unable to save Answer!";
			}
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Answer-New</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
	<!--Link to Validation JS source File -->
	<script type = 'text/javascript' language='javascript' src = 'js/validation.js'></script>
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
			<h2>Answer - New</h2>
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
					<strong>Answer Type</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="sprytextfield1">
						<?php 
							$answerType = isset($answerType)?$answerType:'';
						?>
						<input type = 'text' name = 'answer_type' id = 'answer_type' 
						class = 'typeproforms' value = "<?php echo $answerType;?>" />
					</span>		
				</td>
			</tr>	
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Add Answer' />
				</td>
			</tr>
		  	</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('answers_search.php');
			/*This function will list the departments*/
			echo listAnswer();
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

		var sprytextfield1 	= 	new Spry.Widget.ValidationTextField("sprytextfield1", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
								useCharacterMasking:true, validateOn:["change"]});
					  
</script>	
</html>
<?php
	ob_end_flush();
?>