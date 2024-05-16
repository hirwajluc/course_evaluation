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
if(isset($_GET['msg']) && ($_GET['msg'] == 'deleted'))
{
	$successMsg		=	"Teacher Information Successfully Deleted!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted'))
{
	$errorMsg		=	"Error! Unable to Delete Teacher Information!";	
}
/*To display the dependency message - Delete*/
if(isset($_GET['depend']))
{
	$dependMsg		=	trim($_GET['depend']);
	$errorMsg		=	"Error! Dependency Exists in {$dependMsg}!";	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Teachers Search</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<!--Link to Favicon -->
	<link rel="icon" href="images/favi_logo.gif"/>
</head>
<body>
<div class="main">
	<?php
  		//To Plot Menus in this Page
  		echo plotHeaderMenuInfo("teachers_new.php");
  	?>
  <div class="body">
    <div class="main_body">
		<h2>Teacher - Search</h2>
    	<?php
				/*Display the Messages*/
				if(isset($errorMsg))
				{
					echo "<p><span class = 'error'>{$errorMsg}</span></p>";	
				}
				elseif(isset($successMsg))
				{
					echo "<p><span class = 'success'>{$successMsg}</span></p>";	
				}
		?>	
		<table id='listEntries' width="550" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse;">
        <?php
			$keyword			=	trim($_GET['keyword']);
			$searchQry		=  "SELECT *  FROM `tbl_teacher` INNER JOIN `tbl_t_departments`ON
				tbl_teacher.teacher_department_id = tbl_t_departments.dpt_id
				WHERE `teacher_first_name` LIKE '%{$keyword}%'
				OR `teacher_last_name` LIKE '%{$keyword}%' ORDER BY `teacher_first_name` ASC";
			$searchRes 		= 	$connPDO->query($searchQry);
			$searchNumRows	=	$searchRes->rowCount();
			if (!$searchNumRows)
			{
	           echo '<tr>
                <td height="30" colspan="3" align="center"><strong style="color:red;">Search Not Found</strong></td>
              </tr>';
			}
			else
			{ 
		  ?>
		      <tr>
                <th height="30" align="center"><strong>Employee Id</strong></td>
                <th height="30" align="center"><strong>First Name</strong></td>
                <th height="30" align="center"><strong>Last Name</strong></td>
                <th height="30" align="center"><strong>Dept Code</strong></td>
                <th align="center"><strong>Actions</strong></td>
              </tr>

			<?php
			 	while ($searchArr = $searchRes->fetch(PDO::FETCH_ASSOC))
				{
					//echo "<pre>";print_r($searchArr);die;	
			?>
					<tr>
						<td height="30">&nbsp;
							<?php 
								//echo $searchArr["teacher_emp_code"];
								echo $searchArr["teacher_id"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["teacher_first_name"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["teacher_last_name"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["dpt_code"];
							?>
						</td>
						<td align="center">
							<a href="teachers_edit.php?id=<?php echo $searchArr["teacher_id"] ?>&keyword=<?php echo $_GET["keyword"]?>">Edit</a> | 
							<a onclick="javascript: return confirm('Sure! Do you want to Delete?');"
							href="teachers_delete.php?id=<?php echo $searchArr["teacher_id"]?>&keyword=<?php $_GET["keyword"]?>">
							Delete
							</a>
						</td>
					</tr>
          <?php
		  		}
			}
		  ?>
        </table>
      <p>&nbsp;</p>

    </div>
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
			echo plotSearchDiv('teachers_search.php');
		?><!-- End of Search Div-->
    </div>	
		<div class="clr"></div>
		<br/><br/>
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