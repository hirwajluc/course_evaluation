<?php 
ob_start();
session_start();
/*Include the default function file*/
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";

if (isset($_POST['yr'])) {
	$yr = $_POST['yr'];
	?>

	<option selected disabled hidden>--Choose Sem--</option>
	<option value="1">Semester 1</option>
	<option value="2">Semester 2</option>

<?php
}
?>