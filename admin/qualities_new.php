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
	$successMsg		=	"Quality Successfully Saved!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Quality Successfully Deleted!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted'))
{
	$errorMsg		=	"Error! Unable to Delete Quality!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$qualities		=	trim($_POST['qualities']);
	$types			=	trim($_POST['type']);
	if($qualities	==	'' || $type == '--select--')
	{
		$errorMsg	=	'Error! Quality Should Not Be Left Blank!';	
	}	
	else
	{	
		$selectQuery			=	"SELECT *  FROM `tbl_quality` WHERE `quality` LIKE '$qualities'";
		$existFlag				=	recordAlreadyExist($selectQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error! Quality Already Exists!";
		}	
		else
		{
			$qualitiesInsertQry	=	"INSERT INTO  `course_evaluation`.`tbl_quality` (
								`quality_id` ,
								`quality` ,
								`Title`
								)
								VALUES (
								NULL ,  '$qualities',  '$types'
								)";
			/*Call General Function to Insert the record*/
			$insertFlag			= 	insertOrUpdateRecord($qualitiesInsertQry,$_SERVER['PHP_SELF']);
			if(!$insertFlag)
			{
				$errorMsg		=	"Error!Unable to save Quality!";
			}
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Qualities-New</title>
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
<body onLoad="document.getElementById('qualities').focus();">
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo(basename(__FILE__));
	?>
	<div class="body">
		<div class="main_body">
			<h2>Quality - New</h2>
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
				
				<td width = '170' height = '30'>
					<strong>Type of Quality</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
				<span id = 'type'>
					<?php
						$type = (isset($type))?$type:'';
						echo plotTypeDropdown($type);
					?>	
					
				</span>	
				</td>
			</tr><!-- Semester dropdown ends here-->
			<tr>
				<td width = '70' height = '30'>
					<strong>Qualities</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td height = '30'>
					<span id="TextArea1">
						<textarea class='typeproforms' name = 'qualities' id = 'qualities' rows = '5' cols='85'></textarea>
						<span class="textareaRequiredMsg">Please Enter Quality!</span>
						<span class="textareaMinCharsMsg">Please enter at least 10 characters!</span>
						<span id="Countvalidta1"> </span> / 200
					</span>	
				</td>
			</tr>	
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Add Quality' />
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
			/*This function will list Qualities*/
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