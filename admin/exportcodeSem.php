<?php 
ob_start();
session_start();
/*Include the default function file*/
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";

if (isset($_POST['sem'])) {
	$sem = $_POST['sem'];
	$yr = $_POST['yr'];
	?>

				<table class="table table-striped">
					<tr>
						<td >Department</td>
						<td>&nbsp;Year One</td>
						<td>&nbsp;Year Two</td>
						<td>&nbsp;Year Three</td>
					</tr>
					<?php
					getDepartmentRow($sem, $yr);
					?>
				</table>

<?php
}
?>