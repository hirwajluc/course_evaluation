<?php
ob_start();
session_start();
/*Include the default function file*/
require_once("./includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/* This function will check the session */
checkSession();

$id = (int) $_GET['sid'];
$acc_year = (int) $_GET['acc_year'];
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
        <link href="../admin/css/collapse.css" rel="stylesheet"/>
        <script type="text/javascript" src ="../js/jquery.js"></script>
        <script src="../admin/css/jquery.js"></script>
        <script src="../admin/css/collapse.js"></script>


        <script type="text/javascript" src="../js/shCore.min.js"></script>
        <script type="text/javascript" src="../js/shBrushJScript.min.js"></script>
        <script type="text/javascript" src="../js/shBrushXml.min.js"></script>

        <!-- Additional plugins go here -->

        <script  type="text/javascript" src="../js/plugins/jqplot.logAxisRenderer.min.js"></script>
        <script  type="text/javascript" src="../js/plugins/jqplot.canvasTextRenderer.min.js"></script>
        <script type="text/javascript" src="../js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
        <script type="text/javascript" src="../js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
        <script type="text/javascript" src="../js/plugins/jqplot.dateAxisRenderer.min.js"></script>

        <script type="text/javascript" src="../plugins/jqplot.categoryAxisRenderer.min.js"></script>
        <script type="text/javascript" src="../plugins/jqplot.barRenderer.min.js"></script>


        <script>
            $(document).ready(function () {
                $("#ccomment").hide();
                $("#ccomments").click (function () {                        
                    $("#ccomment").slideToggle('fast');
                });
            });
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
            table.rpt_table {
                width: 250px;
                border: 1px solid #006;
                margin: 5px;
            }
            table.rpt_table td{

                border: 1px solid #006;
                padding: 2px;
            }
        </style>

        <?php
        $Q = getCriteria();

        $qualities1 = getFeedBackReport($acc_year, $id);

        //print_r ($qualities1);
        //die;
        //$qualities = $qualities [$Q[0]];
        ?>

        <html>
            <head>

                <style>
                    @media print {
                        body {
                            color: #00FF00;
                        }
                    }
                </style>
                <link rel="stylesheet" type="text/css" href ="css/jquery.jqplot.min.css" />
                <script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
                <script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
                <script  type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>
                <script type="text/javascript" src="js/plugins/jqplot.pointLabels.min.js"></script>
                <script  type="text/javascript" src="js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
                 <!--[if lt IE 9]><script language="javascript" type="text/javascript" src = "excanvas.js"></script><![endif]-->
                <script type="text/javascript" language ="javascript">
                    $(document).ready(function(){
                        $.jqplot.config.enablePlugins = true;
                        var i = 0 ;


<?php
        for ($y = 1; $y <= sizeof($Q); $y++) {
?>
            var i = 0;
            var ticks1 = new Array( );
<?php
            $qualities = $qualities1[$Q[$y - 1]];


            foreach ($qualities as $q => $p) {
?>

                ticks1[i ++] = ['<?php echo $q; ?>',<?php echo $p[$acc_year]; ?>];
<?php
            }
?>

            var plot1 = $.jqplot('chart' + <?php echo $y; ?>, [ticks1], {

                seriesDefaults:{
                    renderer:$.jqplot.BarRenderer,

                    pointLabels: { show: false }

                },

                axes: {
                    xaxis: {
                        renderer: $.jqplot.CategoryAxisRenderer,
                        //label: 'Quality',
                        labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                        tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                        // ticks: ticks1,
                        tickOptions: {
                            angle: 15,
                            fontFamily: 'Courier New',
                            fontSize: '9pt'

                        }

                    },
                    yaxis: {
                        label: '',

                        labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                        tickOptions: {
                            angle: -90
                        }

                    }
                }});

<?php } ?>
    });

                </script>
            </head>
            <body>
                <div class="main">
                    <?php
                    //To Plot Menus in this Page
                    echo plotHeaderMenuInfo("current_class_wise.php");
                    $course = getSubjectDetails($id);
                    ?>
                    <div class="body">
                        <div class="main_body">
                            <h2>Evaluation Reports</h2>
                            <h4>Course:  <?php echo $course['subject_name']; ?>,
                                Year:  <?php echo $course['subject_year']; ?>,
                                Sem:  <?php echo $course['subject_semester']; ?></h4>

                            <?php echo plotFeedBackReport1($acc_year, $id); ?>


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