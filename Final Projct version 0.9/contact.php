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
    $array_result = GetValue($userID);
} else {
    header("Location: session_error.php");
    die();
}

$array_result = GetValue($userID);

foreach ($array_result as $value) {
    $accountNumber = $value['account_number'];
}

$array_result_banking = InsertBankingValue($accountNumber);

$array_result_contact = InsertContactValue($accountNumber);

function GetValue($userID)
{
    require "connect.php";
    $sql = "select * from user_accounts WHERE username = '{$userID}'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $array_result = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "error" . $conn->connect_error;
    }
    $conn->close();
    return $array_result;
}

function InsertBankingValue($accountNumber)
{
    require "connect.php";
    $sql = "select * from transactions WHERE account_number = '{$accountNumber}'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $array_result_banking = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "error" . $conn->connect_error;
    }
    $conn->close();
    return $array_result_banking;
}

function InsertContactValue($accountNumber)
{
    require "connect.php";
    $sql = "select * from contacts WHERE account_number  = '{$accountNumber}'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $array_result_contact = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "error" . $conn->connect_error;
    }
    $conn->close();
    return $array_result_contact;
}

$contact_name = $contact_number = '';
if (!empty($_POST)) {
    if ($_POST['contact_name'] != '') {
        $contact_name = $_POST['contact_name'];
    }
    if ($_POST['contact_number'] != '') {
        $contact_number = $_POST['contact_number'];
    }
}

$error_log = array();
$error_log = formValidation();
function formValidation()
{
    $error_log['contact_name'] = $error_log['contact_number'] = $error_log['success'] = '';
    if (isset($_POST) && !empty($_POST)) {
        if (trim($_POST['contact_name']) == '') {
            $error_log['contact_name'] = 'Please enter your contact_name';
        }
        if (trim($_POST['contact_number']) == '') {
            $error_log['contact_number'] = 'Please enter your contact_number';
        }
        if ($_POST['contact_name'] != '' && $_POST['contact_number'] != '') {
            $error_log['success'] = '<p class="success">Contact registered!</p>';
            $contact_name = $contact_number = '';
        }
    }
    return $error_log;
}
if (isset($error_log['success']) && !empty($error_log['success'])) {
    try {
        InsertValue($accountNumber);
        $contact_name = $contact_number = '';
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function InsertValue($accountNumber)
{
    require "connect.php";

    $sql = "insert into contacts (account_number,contact_number,contact_name) values('$accountNumber','$_POST[contact_number]','$_POST[contact_name]')";
    if ($conn->query($sql) === true) {
    } else {
        echo "error" . $conn->connect_error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta first_name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="maindiv">
            <div class="col-6">
                <h2 class="success">Contact list</h2>
                <br>
                <?php echo $error_log['success']; ?>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <label for="contact_name">Contact name<span class="error-msg">*</label>
                    <input type="text" class="input-div-nn" id="contact_name" name="contact_name" placeholder="Please enter the contact name" value="<?php echo $contact_name; ?>">
                    <p class="error-msg"><?php echo $error_log['contact_name']; ?></p>

                    <label for="contact_number">Contact account number<span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="contact_number" name="contact_number" placeholder="Please enter recipient account number" value="<?php echo $contact_number; ?>">
                    <p class="error-msg"><?php echo $error_log['contact_number']; ?></p>

                    <input type="submit" class="submit" value="Register contact">

                    <div class="col-6">
                </form>
                <br>
                <table id="customers">
                    <tr>
                        <th>Contact Name</th>
                        <th>Account number</th>
                    </tr>

                    <?php
                    foreach ($array_result_contact as $value) { ?>
                        <tr>
                            <td><?php echo $value['contact_name']; ?></td>
                            <td><?php echo $value['contact_number']; ?></td>
                        </tr>
                    <?php }
                    ?>

                </table>
                <button id="register_btn"><a href="dashboard.php">Back</a></button>
                <button id="register_btn"><a href="log_out.php">Log out</a></button>

            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>