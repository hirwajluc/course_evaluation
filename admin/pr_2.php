<?php
ob_start();
session_start();
$selYear= $_GET['acc_year'];
/*Include the default function file*/
require_once("./includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
/* This function will check the session */
checkSession();

$id = (int) $_GET['sid'];
$acc_year = $_GET['acc_year'];
$selYear=$_GET[''];
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

        <script type="text/javascript" src="js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>


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
                border: 1px solid #336699;
                margin: 5px;
            }
            table.rpt_table td{

                border: 1px solid #336699;
                padding: 2px;
            }
        </style>

        <?php
        $qualities1 = getFeedBackReport_x($acc_year, $id);
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
                <script   type="text/javascript" src="js/plugins/jqplot.logAxisRenderer.min.js"></script>
                <script  type="text/javascript" src="js/plugins/jqplot.canvasTextRenderer.min.js"></script>
                <script  type="text/javascript" src="js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
                <script  type="text/javascript" src="js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
                <script   type="text/javascript" src="js/plugins/jqplot.dateAxisRenderer.min.js"></script>
                <script   type="text/javascript" src="js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
                <script   type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>
				
				<script type="text/javascript">
			function onSbmtClick(){
			    window.open("pr_2.php?acc_year=<?php print $acc_year;?>&sid=<?php print $id ?>", "_newtab");
				return true;
			}

		</script>

                <script type="text/javascript" language ="javascript">
                    $(document).ready(function(){
                      
                        $.jqplot.config.enablePlugins = true;

                        var i = 0;
                        var ticks = new Array( );
                        var s1 = new Array( );
<?php
        $qualities = $qualities1;


       // $c_array = range('a', 'f');
        $c = 0;
        foreach ($qualities as $q => $p) {
?>

           // ticks[i] = '<?php //echo $c_array[$c++]; ?>';
            ticks[i] = '<?php echo $q; ?>';
            s1[i] = <?php echo $p[$acc_year]; ?>;
            i ++;
<?php 
  }
?>

        plot1 = $.jqplot('chart1', [s1], {
           // title: 'Quality Vs Score',
            series:[{
                    renderer:$.jqplot.BarRenderer,
                    rendererOptions: {
                     
                        barWidth: 30,
                        barPadding: -15,
                        barMargin: 0,
                        varyBarColor : true,
                        highlightMouseOver: true
                    
                    }
                }],
            axesDefaults: {
                tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
                tickOptions: {
                    angle: -30,
                    fontSize: '10pt'
                }
            },
          
            axes: {
                xaxis: {
                      ticks: ticks,
                    renderer: $.jqplot.CategoryAxisRenderer
                //    label: 'Qualities'
                },
                yaxis:{
                    min:0, max: 100,
                    tickInterval: 20,
                    tickOptions:{
                        angle: -30,
                        formatString: "%.0f%"
                    },
                  //  label: 'Score (%)',
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer

                }
            },
            highlighter: { show: false }
        });

        $('#chart1').bind('jqplotDataClick',
        function (ev, seriesIndex, pointIndex, data) {
            $('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
        }
    );
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
                        <div class="main_body"><font size="20" style="float:right;"><?php echo $acc_year;?></font>
                            <h2>Evaluation Reports </h2><!-- <button name="pdf_print" onClick="onSbmtClick();" >Generate PDF doc to Print</button> -->
                            <h4>Course:  <?php echo $course['subject_name']; ?> | 
                                Year:  <?php echo $course['subject_year']; ?> | 
                                Semester:  <?php echo $course['subject_semester']; ?>
								<br />Teacher: <?php echo $course['teacher_first_name'];?>
								<?php echo $course['teacher_last_name'];?></h4>

                            <?php echo plotFeedBackReport1_x($acc_year, $id); ?>


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