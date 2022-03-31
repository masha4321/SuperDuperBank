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

require "connect.php";
if (isset($_POST['deleteButton'])) {

    if (isset($_POST['delete'])) {
        foreach ($_POST['delete'] as $deleteUser) {

            $deleteSQL = "DELETE from user_accounts WHERE id=" . $deleteUser;
            mysqli_query($conn, $deleteSQL);
        }
    }
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

    <form name="form1" method="post" action="search.php">
        <input name="search" type="text" size="40" maxlength="50">
        <input type="submit" name="Submit">
    </form>

    <form method="post" action=" <?php echo $_SERVER['PHP_SELF']; ?>">

        <table id="customers">
            <tr>
                <th>ID</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Mobile</th>
                <th>address</th>
                <th>Email</th>
                <th>Registration date</th>
                <th>Delete</th>
            </tr>
            <?php
            require "connect.php";

            $searchKey = $_POST['search'];

            if ($searchKey === '') {
                $query = "SELECT * FROM user_accounts";
            } else {
                $query = "SELECT * FROM user_accounts WHERE 
            first_name LIKE '%$searchKey%' OR 
            last_name LIKE '%$searchKey%' OR 
            mobile LIKE '%$searchKey%' OR 
            address LIKE '%$searchKey%' OR 
            email LIKE '%$searchKey%'";
            }

            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_array($result)) {
                $id = $row['id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $mobile = $row['mobile'];
                $address = $row['address'];
                $email = $row['email'];
                $reg_date = $row['reg_date'];
            ?>
                <tr id='tr_<?= $id ?>'>
                    <td><?= $id ?></td>
                    <td><?= $first_name ?></td>
                    <td><?= $last_name ?></td>
                    <td><?= $mobile ?></td>
                    <td><?= $address ?></td>
                    <td><?= $email ?></td>
                    <td><?= $reg_date ?></td>
                    <td><input type='checkbox' name='delete[]' value='<?= $id ?>'></td>
                </tr>
            <?php } ?>

        </table>
        <input type="submit" value="delete" name="deleteButton" onclick="return confirmDelete();">
    </form>
    <a href="log_out.php" class="href">Log out</a> <br>


    <script>
        function confirmDelete() {
            return confirm('Do you really want to delete?');
        }
    </script>
</body>

</html>