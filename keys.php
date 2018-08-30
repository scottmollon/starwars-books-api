<?php

include_once "mysql_wrapper.php";

header('content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 1000');


if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $name = $_GET['name'];
    
    $query = "SELECT key_val FROM key_vals WHERE name='$name'";
    $qresult = run_query($query, false);
    
    $key = $qresult->result[0]['key_val'];
    
    $result = array("key"=>$key);
    echo json_encode($result);
}

?>