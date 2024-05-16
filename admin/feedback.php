<?php
ob_start();
session_start();
$selYear= $_GET['acc_year'];
/* Include the default function file */
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/* This function will check the session */
checkSession();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Reports</title>

        <style>
            .ccomments{
                background-color: #ccc;
                border:1px solid #aaa;
                font-family: "Helvetica",Helvetica,Arial, sans-serif;
                font-weight: bolder;
            }
            a.ccomments{
                padding: 4px;
                cursor: w-resize;
            }
        </style>
        <script type="text/javascript" src ="jquery.min.js"></script>
        <script type ="text/javascript" src ="functions.js"></script>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!--Link to the template css file-->
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <!--Link to Favicon -->
        <link rel="icon" href="images/favi_logo.gif"/>
        <link href="../admin/css/collapse.css" rel="stylesheet"/>
        <script type="text/javascript" src ="../js/jquery.js"></script>
        <script src="../admin/css/jquery.js"></script>
        <script src="../admin/css/collapse.js"></script>
        <script>

            $(".edit_tr").live('click', function()
            {
                var ID = $(this).attr('id');
                ID = correct_id(ID);
                $('span').show();
                //$('textarea').hide();
                $("#one_" + ID).hide();
                $('#one_input_' + ID).val($('#one_' + ID).html());
                $("#one_input_" + ID).show();
                $("#save_" + ID).show();

            });

            $("a.save").live('click', function() {
                var el = $(this).attr('id');

                id = correct_id(el.split('_')[1]);

                var name = $('#one_input_' + id).val();

                name = name.trim();

                $.ajax({
                    type: "POST",
                    url: "edit_comment.php?action=edit&id=" + id + "&name=" + name,
                    data: "id=" + id + "&name=" + name,
                    cache: false,
                    success: function(e) {
                        if (e == 'success')
                        {
                            $('#one_input_' + id).hide();
                            $('#one_' + id).html(name);
                            $('#one_' + id).show();
                            $("#save_" + id).hide();
                        }
                    }
                });
            });

            $("a.delete").live('click', function() {
                var el = $(this).attr('id');

                id = correct_id(el.split('_')[1]);

                var name = $('#one_input_' + id).val();
                name = name.trim();


                $.ajax({
                    type: "POST",
                    url: "edit_comment.php?action=delete&id=" + id + "&name=" + name,
                    data: "id=" + id + "&name=" + name,
                    cache: false,
                    success: function(e) {
                        if (e == 'success')
                        {
                            $('#' + id).remove();

                        }
                    }
                });
            });
            function correct_id(str) {

                var tmp = "";
                for (var i = 0; i < str.length; i++) {
                    if (str.charAt(i) == "'")
                        continue;
                    tmp += str.charAt(i);
                }
                return tmp;
            }
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#ccomment").hide();
                $("#ccomments").click(function() {
                    $("#ccomment").slideToggle('fast');
                });

            });

            function pr() {
                alert('xxxx');
            }
        </script>

        <style>
            p.pcomment {
                border: 1px dashed #cccccc;
                font: normal 11px Arial, Helvetica, sans-serif;
                padding: 5px;

                background-color: #eceeef;

            }
            p.pcomment:first-letter {
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <div class="main">
            <?php
			
            //To Plot Menus in this Page
            echo plotHeaderMenuInfo("current_class_wise.php");

           // $cid = (int) $_GET['cid'];

            $id = (int) $_GET['id'];
            $acc_year = $_GET['acc_year'];
            $course = getSubjectDetails($id);
            ?>
			
            <div class="body">
                <div class="main_body"><font size="20" style="float:right;"><?php echo $selYear;?></font>
                    <h2>Evaluation Reports</h2> 
                    <h4>Course:  <?php echo $course['subject_name']; ?>,
                        Year:  <?php echo $course['subject_year']; ?>,
                        Sem:  <?php echo $course['subject_semester']; ?></h4>
                    <p style="font-weight: bold; color: #aa0000;">Teacher: <?php echo $course['teacher_first_name'];?> <?php echo $course['teacher_last_name'];?></p>
                    <?php echo plotFeedBackReport($acc_year, $id); ?>


                </div>
                <?php
                /* This function will return the logo div string to the sidebody */
                echo plotLogoDiv();
                //		echo plotSearchDiv('current_class_wise.php');
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