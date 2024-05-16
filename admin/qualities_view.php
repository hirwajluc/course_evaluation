<?php
	require_once("includes/functions.php");
	/* Include the database configuration file */
	require_once ('../admin/includes/config.php');
	include "../admin/includes/dbConnectPDO.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Department-New</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--Link to the template css file-->
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div class="main">
	<?php
		/*This function will return the header string with menu information*/
		echo plotHeaderMenuInfo("qualities_new.php");
	?>
	<div class="body">
		<div class="main_body">
		  <h2>Qualities - View</h2>
			
		  
		  
		  
		</div><!-- End of main_body div(main white div)-->
		<?php
			/*This function will return the logo div string to the sidebody*/
			echo plotLogoDiv();
		?>
		<div class="search">
		  <form action="register.php" method="post">
			<input name="" type="text"  class="keywords" value="Search..." />
			<input name="" type="image" src="images/search.gif" />
		  </form>
		</div><!-- End of Search Textbox Div-->
    	<div class="side_body">
		  <p>
			sidebody sidebody sidebody sidebody sidebody sidebody sidebody 
		  </p>
		</div><!--End of side body div -->
		<div class="clr"></div>
	</div><!-- End of Body div-->
</div><!--End of Main Div-->	
<?php
	/*This function will return the footer div information*/
	echo plotFooterDiv();
?>
</body>
</html>