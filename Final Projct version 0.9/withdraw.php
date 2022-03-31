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

foreach ($array_result as $value) {
    $accountNumber = $value['account_number'];
    $currentBalance = $value['balance'];
}

$array_result_banking = InsertBankingValue($accountNumber);

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


$amount = '';
if (!empty($_POST)) {
    if ($_POST['amount'] != '') {
        $amount = $_POST['amount'];
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
        if ($_POST['amount'] != '') {
            $error_log['success'] = '<p class="success">Thank you! The money has been withdrew.</p>';

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
    $transaction_informations = "User withdrew " . $_POST['amount'] . " from his own account";
    $transactionType = "Withdrawal";

    $sql0 = "SELECT balance FROM user_accounts WHERE account_number='{$accountNumber}'";
    $result = $conn->query($sql0);
    $row = $result->fetch_assoc();
    $currentBalance = $row['balance'];

    if (($currentBalance > $_POST['amount'])) {
        $updated_balance = $currentBalance - $_POST['amount'];
    } else {
        $error_log['amount'] = 'Insufficient Funds';
    }

    $sql1 = "insert into transactions (account_number,type,amount,transaction_informations) values('$accountNumber','$transactionType','$_POST[amount]','$transaction_informations')";

    if ($conn->query($sql1) === true) {
    } else {
        echo "error" . $conn->connect_error;
    }

    $sql2 = "UPDATE user_accounts 
    SET balance = '$updated_balance'
    WHERE account_number = '{$accountNumber}'";
    if ($conn->query($sql2) === true) {
        header("Location: withdraw.php");
    } else {
        echo "error" . $conn->connect_error;
    }

    $conn->close();
    return $error_log;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta first_name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="maindiv">
            <div class="col-6">
                <h2 class="success">Withdrawal</h2>
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

                    <label for="amount">Amount<span class="error-msg"></label>
                    <input type="number" class="input-div-nn" id="amount" name="amount" placeholder="Please enter the amount you wish to withdraw" value="<?php echo $amount; ?>">
                    <p class="error-msg"><?php echo $error_log['amount']; ?></p>

                    <input type="submit" class="submit" value="Confirm">

                    <div class="col-6">
                </form>
                <a href="dashboard.php" class="href">Back</a>
                <br>
                <a href="log_out.php" class="href">Log out</a>
            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>