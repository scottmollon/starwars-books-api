<?php

include_once "mysql_wrapper.php";

header('content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 1000');

//get coutns and pages for a timeline type
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $key = $_POST['key'];
    
    $query = "SELECT key_val FROM key_vals WHERE name='admin'";
    $qresult = run_query($query, false);
    
    $admin_key = $qresult->result[0]['key_val'];
    
    if ($key == $admin_key)
    {
        $admin = true;
    }
    else
    {
        $admin = false;
    }
    
    $result = array("admin"=>$admin);
    echo json_encode($result);
}

?>