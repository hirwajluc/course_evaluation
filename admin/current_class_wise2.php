<?php
ob_start();
session_start();
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/* Include the default function file */
require_once("includes/functions.php");
/* This function will check the session */
checkSession();
if (isset($_GET['msg']) && ($_GET['msg'] == 'deleted')) {
    $successMsg = "Subject Information Successfully Deleted!";
}
if (isset($_GET['msg']) && ($_GET['msg'] == 'notdeleted')) {
    $errorMsg = "Error! Unable to Delete Subject Information!";
}
/* To display the dependency message - Delete */
if (isset($_GET['depend'])) {
    $dependMsg = trim($_GET['depend']);
    $errorMsg = "Error! Dependency exists in {$dependMsg}!";
}

//2016/11/11
//$year= date("Y");
$acc_year = "";
if (isset($_POST['s_year'])) {
	$acc_year = $_POST['s_year'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Reports</title>
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
            echo plotHeaderMenuInfo("current_class_wise.php");
            //2016/11/11
            print "<form name=\"frm\" action=\"".$_SERVER['PHP_SELF']."\"  method=\"POST\" >";
            ?>
            <div class="body">
                <div class="main_body">
                    <h2>Evaluation Reports
					
					<select title="Select year" name="s_year">
						<option value="0">Select Year</option>
							<?php
								for($i=date("Y");$i>=2007;$i--){
									//echo "<option>".$i."</option>";
									if ($acc_year == $i){
										echo "<option value=\"".$i."\" selected >".$i."</option>";
									}else{
										echo "<option value=\"".$i."\" >".$i."</option>";
									}
								}
							?>
					</select>	
					
						<button name="go">Go</button>
						<button name="print">Generate PDF Doc</button></h2>
					
                    <?php
                    /* Display the Messages */
                    if (isset($errorMsg)) {
                        echo "<p><span class = 'error'>{$errorMsg}</span></p>";
                    } elseif (isset($successMsg)) {
                        echo "<p><span class = 'success'>{$successMsg}</span></p>";
                    }
                    ?>
                    <table id='listEntries' width="550" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" style="border-collapse:collapse;">
                        <?php
                        $keyword = mysql_real_escape_string(trim($_GET['keyword']));

                        $searchQry = "SELECT *
								FROM tbl_subject
								INNER JOIN tbl_department ON tbl_subject.`subject_department_id` = tbl_department.department_id
								INNER JOIN tbl_teacher ON tbl_subject.`subject_teacher_id` = tbl_teacher.`teacher_id` WHERE
								tbl_subject.`subject_name` LIKE '{$keyword}%' OR
								tbl_subject.`subject_code` LIKE '{$keyword}%'
								ORDER BY subject_id DESC";
                        $searchRes = mysql_query($searchQry);
                        $searchNumRows = mysql_num_rows($searchRes);
                        if (!$searchNumRows)
							{
                            echo '<tr>
                <td height="30" colspan="3" align="center"><strong style="color:red;">Search Not Found</strong></td>
              </tr>';
							} else {
                        ?>
                            <tr>
                      <!--<th height="30" align="center"><strong>Sub Code</strong></td>-->
                                <th height="30" align="center"><strong>Sub Name</strong></td>
                                    <th height="30" align="center"><strong>Dept Code</strong></td>
                                        <th height="30" align="center"><strong>Year</strong></td>
                                            <th height="30" align="center"><strong>Semester</strong></td>
                                                <th height="30" align="center"><strong>Teacher Name</strong></td>
                                                    <th align="center"><strong>Evaluations</strong></td>
                                                        </tr>

                                                    <?php
													//$year=	date("Y");
                                                    //$acc_year = $year; // ?????????muganga
                                                    while ($searchArr = mysql_fetch_assoc($searchRes)) {
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
                                                            echo $searchArr["subject_semester"];
                                                            ?>
                                                        </td>
                                                        <td height="30">&nbsp;
                                                            <?php
                                                            echo $searchArr["teacher_first_name"];
                                                            ?>
                                                        </td>


                                                        <td align="center">
                                                            <?php
                                                            $link = hasEvaluationData($searchArr["subject_id"], $acc_year);

                                                            if ($link) {
                                                            ?>
                                                                <a href="feedback.php?id=<?php echo $searchArr["subject_id"]; ?>&acc_year=<?php echo $acc_year; ?>">View<?php echo $acc_year; ?></a>
                                                             <!--   <a href="#?id=<?php echo $searchArr["subject_id"] ?>">
                                                                <?php echo $acc_year - 1; ?>
                                                            </a> | 
                                                            <a href="#?id=<?php echo $searchArr["subject_id"] ?>">
                                                                <?php echo $acc_year - 2; ?>
                                                            </a> -->
                                                            <?php
                                                            } else {
                                                            ?>
                                                                No evaluation
                                                            <?php
                                                            }
                                                            ?>
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
                                                    /* This function will return the logo div string to the sidebody */
                                                    echo plotLogoDiv();
                                                    echo plotSearchDiv('current_class_wise.php');
                                                    ?><!-- End of Search Div-->
                                                    </div>
                                                    <div class="clr"></div>
                                                    <br/><br/>
                                                    </div><!-- End of Body div-->
                                                    </div><!--End of Main Div-->
                                                    <?php
                                                    /* This function will return the footer div information */
                                                    echo plotFooterDiv();
                                                    ?>
                                                    </body>
                                                    </html>
                                                    <?php
                                                    ob_end_flush();
                                                    ?>