<?php

$id = (int) $_GET['id'];

$sql = "";
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $sql = "DELETE FROM products where pid = $id";
} else if (isset ($_GET['action']) && $_GET['action'] == 'edit'){
    $name = $_GET['name'];
    $name = trim($name);
    $sql = "UPDATE products SET name = '$name' where pid = $id";
}
if (strlen($sql) > 0 && $id > 0) {
    mysql_connect('localhost', 'root', '');
    mysql_select_db('testdb');
    mysql_query($sql);
    echo "success";
}
?>
