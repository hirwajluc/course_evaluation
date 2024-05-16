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
	$successMsg		=	"Successfully Updated!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Successfully Deleted!";	
}
if(isset($_POST['submit']))
{	
	//echo '<pre>';print_r($_POST);die;
	$uId			=	intval(trim($_GET['id']));
	$firstName		=	trim($_POST['first_name']);
	$lastName		=	trim($_POST['last_name']);
	$gender			=	trim($_POST['gender']);
	$username		=	trim($_POST['username']);
	$password		=	trim($_POST['password']);
	$u_utype		=	trim($_POST['u_utype']);
	$phone			=	trim($_POST['phone']);
	
	$userMD5Pass	=	md5($password);
	if($phone	==	'' || $username	==	'' || $firstName	==	'' || $lastName	==	'' || $password	==	'')
	{
		$errorMsg	=	'Error! Required Fields Cannot Be Left Blank!';	
	}	
	else
	{		
		$stuSearchQuery			=	"SELECT *  FROM `tbl_users` WHERE (`u_fname` LIKE '{$firstName}' AND `u_lname` LIKE  '{$lastName}' AND `u_gender` LIKE '{$gender}' AND `u_uname` LIKE '{$username}' AND `u_pass` LIKE '{$password}' AND `u_utype` LIKE '{$u_utype}' AND `u_phone` LIKE '{$phone}')";
		$existFlag				=	recordAlreadyExist($stuSearchQuery);
		if($existFlag)
		{
			$errorMsg			=	"Error!Information already Exists!";
		}	
		else
		{	
			$stuInsertQry	=	"UPDATE `tbl_users` SET `u_fname` = '{$firstName}', `u_lname` =  '{$lastName}', `u_gender` = '{$gender}', `u_uname` = '{$username}', `u_pass` = '{$userMD5Pass}', `u_utype` = '{$u_utype}', `u_phone` = '{$phone}' WHERE `u_id` = '{$uId}'";

//print "sql=".$stuInsertQry;
//exit;			
                        /*Call General Function to Insert the record*/
			$insertFlag			= 	insertOrUpdateRecord($stuInsertQry,$_SERVER['PHP_SELF']);
			if(!$insertFlag)
			{
				$errorMsg		=	"Error!Unable to Edit User Information!";
			}
			
		}	
	}	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Users Edit</title>
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
		echo plotHeaderMenuInfo("users_new.php");
	?>
	<div class="body">
		<div class="main_body">
			<h2>User Edit</h2>
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
			<?php
				if(isset($_GET['id']))
				{
					$id					=	$_GET['id'];
					$userSelQry		=	"SELECT *  FROM `tbl_users` WHERE `u_id` ={$id}";
					$userSelRes		=	$connPDO->query($userSelQry);
					$userSelNumRows	=	$userSelRes->rowCount();
					if($userSelNumRows)
					{
						$userSelArr	=	$userSelRes->fetch(PDO::FETCH_ASSOC);
						//echo '<pre>';print_r($studentSelArr);echo '</pre>';
						$uId		=	$userSelArr['u_id'];
						$ufname		=	$userSelArr['u_fname'];
						$ulname		=	$userSelArr['u_lname'];
						$ugender	=	$userSelArr['u_gender'];
						$uname		=	$userSelArr['u_uname'];
						$upass		=	$userSelArr['u_pass'];
						$utype		=	$userSelArr['u_utype'];
						$uphone		=	$userSelArr['u_phone'];
					}
				}
			?>	
			<form method = 'POST' action = "<?php echo "{$_SERVER['PHP_SELF']}?id={$_GET['id']}"?>">
			<table  border = '0' cellspacing = '0' cellpadding = '0'>
			<tr>
				<td width = '120' height = '30'>
					<strong>First Name</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield2">
						<input type = 'text' name = 'first_name' id = 'first_name' class = 'typeproforms' value = "<?php if(isset($ufname))echo $ufname;?>" required/>
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
						<input type = 'text' name = 'last_name' id = 'last_name' class = 'typeproforms'	value = "<?php if(isset($ulname))echo $ulname;?>" required/>
					</span>
				</td>
			</tr><!-- End Last Name row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Gender</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'gender'>
						<?php
							$gender = isset($ugender)?$ugender:'';
							echo plotGenderDropdown($ugender);
						?>
					</span>
				</td>
			</tr><!-- End of Gender row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Username</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield4">
						<input type = 'text' name = 'username' id = 'username' class = 'typeproforms' value = "<?php if(isset($uname))echo $uname;?>" required/>
					</span>
				</td>
			</tr><!-- End Username row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Password</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield4">
						<input type = 'password' name = 'password' id = 'password' class = 'typeproforms' value = "<?php if(isset($upass))echo $upass;?>" required/>
					</span>
				</td>
			</tr><!-- End password row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>User Type</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id = 'utype'>
						<?php
							$type = isset($utype)?$utype:'';
							echo plotUserTypeDropdown($utype);
						?>
					</span>
				</td>
			</tr><!-- End User Type row-->
			<tr>
				<td width = '120' height = '30'>
					<strong>Phone Number</strong>
					<span class = 'mandatory'>*</span>
				</td>
				<td width = '120' height = '30'>
					<span id="sprytextfield1">
						<input type = 'text' name = 'phone' id = 'phone' class = 'typeproforms'
						value = "<?php if(isset($uphone))echo $uphone;?>"	required/>
					</span>	
				</td>
			</tr><!-- End of Phone Number row-->
			<tr>
				<td>&nbsp;</td>
				<td height = '30'>
					<input type = 'submit' name = 'submit' class = 'button' value = 'Update User' />
				</td>
			</tr>
			</table>
			</form><!-- End of form-->
			<br/>
		</div><!-- End of main_body div(main white div)-->
		<?php
			echo plotLogoDiv();
			echo plotSearchDiv($searchActionFile = 'user_search.php');
			/*This function will list the departments*/
			echo listUsers();
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
	var sprytextfield1 	= new Spry.Widget.ValidationTextField("sprytextfield1", "custom",{isRequired:true,characterMasking:/[a-zA-Z0-9 ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield2 	= new Spry.Widget.ValidationTextField("sprytextfield2", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/,
						  useCharacterMasking:true, validateOn:["change"]});
	var sprytextfield3 	= new Spry.Widget.ValidationTextField("sprytextfield3", "custom",{isRequired:true,characterMasking:/[a-zA-Z ]/, 
						  useCharacterMasking:true, validateOn:["change"]});
</script>
</body>
</html>
<?php
	ob_end_flush();
?>