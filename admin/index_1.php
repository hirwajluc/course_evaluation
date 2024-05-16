<html>
    <head>
        <style type ="text/css">
            p,textarea {
                width: 500px;
                height: auto;
                font-family: "Times new roman", times, serif;
                font-size: 12pt;
                font-style: normal;
                font-weight: normal;
                font-variant: normal;
            }
            table {
                width: 500px;
            }

        </style>
        <script type="text/javascript" src ="jquery.min.js"></script>
        <script type ="text/javascript" src ="functions.js"></script>
    </head>
    <body>
        <?php
        mysql_connect('localhost', 'root', '');
        mysql_select_db('testdb');

        $query_pag_data = "SELECT pid,name from products";
        $result_pag_data = mysql_query($query_pag_data) or die('MySql Error' . mysql_error());
        $finaldata = "";
        $tabledata = "";

        while ($row = mysql_fetch_array($result_pag_data)) {
            $id = $row['pid'];
            $name = htmlentities($row['name']);

            $tabledata.="<p id='$id' class='edit_tr'>

<span id='one_$id' class='text'>$name</span>
<textarea value='$name' class='editbox' id='one_input_$id' ></textarea>
<br />
            <a href='#' class='delete' id=del_$id> X </a>  <a href='#' class='save' id=save_$id> Edit </a>
            </p>
 ";
        }

        echo $tabledata;
        ?>
    </body>
</html>