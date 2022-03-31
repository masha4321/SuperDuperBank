<?php
$timeout = 600;

ini_set("session.gc_maxlifetime", $timeout);
ini_set("session.cookie_lifetime", $timeout);
session_start();
$s_name = session_name();
if (isset($_COOKIE[$s_name])) {
  setcookie($s_name, $_COOKIE[$s_name], time() + $timeout, '/');
} else {
  header("Location: session_error.php");
  die();
}

if (isset($_SESSION['user_id'])) {
  $userID =  $_SESSION['user_id'];
  $array_result = InsertValue();
} else {
  header("Location: session_error.php");
  die();
}
function InsertValue()
{
  $array_result = array();

  require "connect.php";

  $sql = "select * from user_accounts";

  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $array_result = $result->fetch_all(MYSQLI_ASSOC);
  } else {
    echo "error" . $conn->connect_error;
  }
  $conn->close();
  return $array_result;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/admin.css">
</head>

<body>
  <p>HI ADMIN</p>

  <form name="form1" method="post" action="searchresults.php">
    <input name="search" type="text" size="40" maxlength="50">
    <input type="submit" name="Submit" value="search">
  </form>

  <table id="customers">
    <tr>
      <th>First name</th>
      <th>Last name</th>
      <th>Mobile</th>
      <th>address</th>
      <th>Email</th>
      <th>Registration date</th>
      <th>id</th>
      <th>Edit</th>
    </tr>
    <?php

    foreach ($array_result as $value) { ?>
      <tr>
        <td><?php echo $value['first_name']; ?></td>
        <td><?php echo $value['last_name']; ?></td>
        <td><?php echo $value['mobile']; ?></td>
        <td><?php echo $value['address']; ?></td>
        <td><?php echo $value['email']; ?></td>
        <td><?php echo $value['reg_date']; ?></td>
        <td><?php echo $value['id']; ?></td>
        <td><a href="edit.php">Edit</a> | <a href="edit.php">Delete</a></td>
      </tr>
    <?php } ?>

  </table>

  <a href="log_out.php" class="href">Log out</a> <br>
</body>

</html>