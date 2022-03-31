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

$amount = $account_traded_with = $transaction_informations = '';
if (!empty($_POST)) {
    if ($_POST['amount'] != '') {
        $amount = $_POST['amount'];
    }
    if ($_POST['account_traded_with'] != '') {
        $account_traded_with = $_POST['account_traded_with'];
    }
    if ($_POST['transaction_informations'] != '') {
        $transaction_informations = "Transfer<br>Comment: " . $_POST['transaction_informations'];
    }
}

$error_log = array();
$error_log = formValidation();
function formValidation()
{
    $error_log['amount'] = $error_log['account_traded_with'] = $error_log['transaction_informations'] = $error_log['success'] = '';
    if (isset($_POST) && !empty($_POST)) {
        if (trim($_POST['amount']) == '') {
            $error_log['amount'] = 'Please enter your amount';
        }
        if (trim($_POST['account_traded_with']) == '') {
            $error_log['account_traded_with'] = 'Please enter your account_traded_with';
        }
        if (trim($_POST['transaction_informations']) == '') {
            $error_log['transaction_informations'] = 'Please enter your transaction_informations';
        }
        if ($_POST['amount'] != '' && $_POST['account_traded_with'] != '' && $_POST['transaction_informations'] != '') {
            $error_log['success'] = '<p class="success">Thank you!</p>';
            $amount = $account_traded_with = $transaction_informations = '';
        }
    }
    return $error_log;
}
if (isset($error_log['success']) && !empty($error_log['success'])) {
    try {
        InsertValue($accountNumber);
        $amount = $account_traded_with = $transaction_informations = '';
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function InsertValue($accountNumber)
{
    require "connect.php";

    $transactionType = "Transfer to account ".$_POST['account_traded_with'];
    $updated_balance = $_SESSION["current_balance"] - $_POST['amount'];
    $sql = "insert into transactions (account_number,account_traded_with,type,amount,transaction_informations) values('$accountNumber','$_POST[account_traded_with]','$transactionType','$_POST[amount]','$_POST[transaction_informations]')";
    if ($conn->query($sql) === true) {

    } else {
        echo "error" . $conn->connect_error;
    }

    $transactionType = "Received from account ".$accountNumber;
    $updated_balance = $_SESSION["current_balance"] - $_POST['amount'];
    $sql1 = "insert into transactions (account_number,account_traded_with,type,amount,transaction_informations) values('$_POST[account_traded_with]','$accountNumber','$transactionType','$_POST[amount]','$_POST[transaction_informations]')";
    if ($conn->query($sql1) === true) {

    } else {
        echo "error" . $conn->connect_error;
    }


    $sql2 = "SELECT balance FROM user_accounts WHERE account_number='{$accountNumber}'";
    $result = $conn->query($sql2);
    $row = $result->fetch_assoc();
    $currentBalance = $row['balance'];
    $updated_balance = $currentBalance - $_POST['amount'];

    $sql3 = "UPDATE user_accounts 
    SET balance = '$updated_balance'
    WHERE account_number = '{$accountNumber}'";
    if ($conn->query($sql3) === true) {
        header("Location: transfer.php");
    } else {
        echo "error" . $conn->connect_error;
    }

    $sql4 = "SELECT balance FROM user_accounts WHERE account_number='{$_POST['account_traded_with']}'";
    $result = $conn->query($sql4);
    $row = $result->fetch_assoc();
    $currentBalance = $row['balance'];
    $updated_balance = $currentBalance + $_POST['amount'];

    $sql5 = "UPDATE user_accounts 
    SET balance = '$updated_balance'
    WHERE account_number = '{$_POST['account_traded_with']}'";
    if ($conn->query($sql5) === true) {
        header("Location: transfer.php");
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
    <title>Transfer</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="maindiv">
            <div id="top" class="col-6">
                <h2 class="success">Transfer</h2>
                <br>
                <?php echo $error_log['success']; ?>

                <?php
                foreach ($array_result as $value) {
                    $userFirstName = $value['first_name'];
                    $accountBalance = $value['balance'];
                    $accountNumber = $value['account_number'];
                }
                ?>

                <p>
                    <?php
                    echo "Account balance: " . $accountBalance . " $";
                    ?>
                </p>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <label for="amount">Amount<span class="error-msg">*</label>
                    <input type="number" class="input-div-nn" id="amount" name="amount" placeholder="Please enter the amount you wish to send" value="<?php echo $amount; ?>">
                    <p class="error-msg"><?php echo $error_log['amount']; ?></p>

                    <label for="account_traded_with">Recipient<span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="account_traded_with" name="account_traded_with" placeholder="Please enter recipient account number" value="<?php echo $account_traded_with; ?>">
                    <p class="error-msg"><?php echo $error_log['account_traded_with']; ?></p>

                    <label for="transaction_informations">Information<span class="error-msg">*</label>
                    <input type="text" class="input-div-nn" id="transaction_informations" name="transaction_informations" placeholder="Please enter any comments regarding the transaction" value="<?php echo $transaction_informations; ?>">
                    <p class="error-msg"><?php echo $error_log['transaction_informations']; ?></p>

                    <input type="submit" class="submit" value="Transfer money">

                    <div class="col-6">
                </form>
                <br>
                <table id="customers">
                    <tr>
                        <th>Contact Name</th>
                        <th>Account number</th>
                        <th>Select</th>
                    </tr>

                    <?php
                    foreach ($array_result_contact as $value) { ?>
                        <tr>
                            <td><?php echo $value['contact_name']; ?></td>
                            <td><?php echo $value['contact_number']; ?></td>
                            <td><input type='checkbox' name='delete[]' value='<?= $id ?>'></td>
                        </tr>
                    <?php }
                    ?>

                </table>
                <br>
                <a href="dashboard.php" class="href">Back</a>
                <br>
                <a href="log_out.php" class="href">Log out</a>
            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>