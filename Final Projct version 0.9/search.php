<?php

// NEED TO EDIT

Select_Result();
function Select_Result()
{

    $array_result = array();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "myDB";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Failed! " . $conn->connect_error);
    }
    // echo $encryption;exit;
    $sql = "Select * from contactus where firstname like '%$_POST[search_keyword]%' OR mobile like '%$_POST[search_keyword]%' OR email like '%$_POST[search_keyword]%' OR message like '%$_POST[search_keyword]%'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $array_result = $result->fetch_all(MYSQLI_ASSOC);
        $html = include 'include_table.php';
        return $html;
    } else {
        echo $conn->connect_error;
    }
    $conn->close();
    return $array_result;
}
