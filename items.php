<?php

include_once "mysql_wrapper.php";

header('content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Max-Age: 1000');

//get an element type
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $type = $_GET['type'];
    $pageSize = (int)$_GET['size'];
    $page = (int)$_GET['page'];
    
    $query = "SELECT * FROM items WHERE canon='$type' OR canon='both' ORDER BY place ASC";
    $qresult = run_query($query, false);
    $items = $qresult->result;
    
    $start = ($page == 0) ? 0 : $page * $pageSize;
    $end = $start + $pageSize;
    
    $list = array();
    for ($i = $start; ($i < count($items)) && ($i < $end); $i++)
    {   
        array_push($list, $items[$i]);
    }
    
    $result = array("list"=>$list,"nextpage"=>++$page);
    echo json_encode($result);
}

//Add new item
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //get the next page number from the client
    $nextPage = $_POST['nextPage'];
    $pageSize = $_POST['size'];
    
    //new item information
    $newPlace = $_POST['newplace'];
    $newType = $_POST['newtype'];
    $newCanon = $_POST['newcanon'];
    $newTitle = $_POST['newtitle'];
    $newSub = $_POST['newsubtitle'];
    $newBody = $_POST['newbody'];
    $newGoogle = $_POST['newgoogleid'];
    
    //add the new item to the db
    $query = "INSERT INTO items (type, place, canon, googleid, title, subtitle, body) ";
    $query .= "VALUES ('$newType', '$newPlace', '$newCanon', '$newGoogle', '$newTitle', '$newSub', '$newBody')";

    $qresult = run_query($query, true);

    $query = "SELECT * FROM items WHERE canon='$newCanon' AND type='$newType' AND place='$newPlace'";
    $qresult = run_query($query, false);
    $items = $qresult->result;
    
    //get total number of pages after the add
    $query = "SELECT COUNT(id) as count FROM items ";
    $query .= "WHERE canon='both' OR canon='$newCanon'";
    
    $qresult = run_query($query, false);
    
    $count = (int)$qresult->result[0]['count'];
    $totalPages = ceil($count / $pageSize);
    
    //if nextPage != totalPages drop the last item on the client side so paging doesn't get messed up
    if ($nextPage == $totalPages)
        $dropLast = false;
    else
        $dropLast = true;
    
    $result = array("newItem"=>$items[0], "dropLast"=>$dropLast, "totalPages"=>$totalPages);
    echo json_encode($result);   
}

?>