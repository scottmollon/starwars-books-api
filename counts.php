<?php

include_once "mysql_wrapper.php";

header('content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 1000');

//get coutns and pages for a timeline type
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $timelineType = $_GET['type'];
    $pageSize = $_GET['page'];
    
    $query = "SELECT COUNT(id) as count FROM items ";
    $query .= "WHERE canon='both' OR canon='$timelineType'";
    
    $qresult = run_query($query, false);
    
    $count = (int)$qresult->result[0]['count'];
    $pages = ceil($count / $pageSize);
    
    $result = array("pages"=>$pages);
    echo json_encode($result);
}

?>