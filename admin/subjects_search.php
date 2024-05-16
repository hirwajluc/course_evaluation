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
	$successMsg		=	"Subject Information Successfully Deleted!";	
}
if(isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted'))
{
	$errorMsg		=	"Error! Unable to Delete Subject Information!";	
}
/*To display the dependency message - Delete*/
if(isset($_GET['depend']))
{
	$dependMsg		=	trim($_GET['depend']);
	$errorMsg		=	"Error! Dependency exists in {$dependMsg}!";	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Subject Search</title>
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
  		echo plotHeaderMenuInfo("subjects_new.php");
  	?>
  <div class="body">
    <div class="main_body">
		<h2>Subject - Search</h2>
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
			$keyword		=	trim($_GET['keyword']);
			
			$searchQry		=  "SELECT *
								FROM tbl_subject
								INNER JOIN tbl_department ON tbl_subject.`subject_department_id` = tbl_department.department_id
								INNER JOIN tbl_teacher ON tbl_subject.`subject_teacher_id` = tbl_teacher.`teacher_id` WHERE 
								tbl_subject.`subject_name` LIKE '%{$keyword}%' OR
								tbl_subject.`subject_code` LIKE '%{$keyword}%' OR
								tbl_subject.`subject_ac_year` LIKE '%{$keyword}%' OR
								tbl_teacher.`teacher_first_name` LIKE '%{$keyword}%' OR
								tbl_teacher.`teacher_last_name` LIKE '%{$keyword}%' OR
								tbl_department.`department_code` LIKE '{$keyword}%'
								ORDER BY subject_id DESC";

 
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
                <!--<th height="30" align="center"><strong>Sub Code</strong></td>-->
                <th height="30" align="center"><strong>Sub Name</strong></td>
                <th height="30" align="center"><strong>Prog Code</strong></td>
                <th height="30" align="center"><strong>Level Year</strong></td>
                <th height="30" align="center"><strong>Group</strong></td>
                <th height="30" align="center"><strong>Acad Year</strong></td>
                <th height="30" align="center"><strong>Semester</strong></td>
                <th height="30" align="center"><strong>Teacher Name</strong></td>
                <th align="center"><strong>Actions</strong></td>
              </tr>

			<?php
			 	while ($searchArr = $searchRes->fetch(PDO::FETCH_ASSOC))
				{
					//echo "<pre>";print_r($searchArr);die;	
			?>
					<tr>
						<!--<td height="30">&nbsp;
							<?php 
								echo $searchArr["subject_code"];
							?>
						</td>-->
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["subject_name"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["department_code"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["subject_year"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								global $groupArrValue;
								echo $groupArrValue[$searchArr["subject_group"]-1];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								global $groupArrValue;
								echo $searchArr["subject_ac_year"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["subject_semester"];
							?>
						</td>
						<td height="30">&nbsp;
							<?php 
								echo $searchArr["teacher_first_name"]." ".$searchArr["teacher_last_name"];
							?>
						</td>
						
						
						<td align="center">
							<a href="subjects_edit.php?id=<?php echo $searchArr["subject_id"] ?>&keyword=<?php echo $_GET["keyword"]?>">Edit</a> | 
							<a onclick="javascript: return confirm('Sure! Do you want to Delete?');"
							href="subjects_delete.php?id=<?php echo $searchArr["subject_id"]?>&keyword=<?php $_GET["keyword"]?>">
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
			echo plotSearchDiv('subjects_search.php');
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