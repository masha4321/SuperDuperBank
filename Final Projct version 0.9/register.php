<?php
session_start();

$username = $pwd = $first_name = $last_name = $mobile = $address = $email = $amount = '';
if (!empty($_POST)) {
    if ($_POST['username'] != '') {
        $username = $_POST['username'];
    }
    if ($_POST['pwd'] != '') {
        $pwd = $_POST['pwd'];
    }
    if ($_POST['first_name'] != '') {
        $first_name = $_POST['first_name'];
    }
    if ($_POST['last_name'] != '') {
        $last_name = $_POST['last_name'];
    }
    if ($_POST['mobile'] != '') {
        $mobile = $_POST['mobile'];
    }
    if ($_POST['address'] != '') {
        $address = $_POST['address'];
    }
    if ($_POST['email'] != '') {
        $email = $_POST['email'];
    }
    if ($_POST['amount'] != '') {
        $email = $_POST['amount'];
    }
}

$error_log = array();
$error_log = formValidation();
function formValidation()
{
    $error_log['username'] = $error_log['pwd'] = $error_log['first_name'] = $error_log['last_name'] = $error_log['mobile'] = $error_log['address'] = $error_log['email'] =  $error_log['amount'] = $error_log['success'] = '';
    if (isset($_POST) && !empty($_POST)) {
        if (trim($_POST['username']) == '') {
            $error_log['username'] = 'Please enter your username';
        }
        if (trim($_POST['pwd']) == '') {
            $error_log['pwd'] = 'Please enter your password';
        }
        if (trim($_POST['first_name']) == '') {
            $error_log['first_name'] = 'Please enter your first name';
        }
        if (trim($_POST['last_name']) == '') {
            $error_log['last_name'] = 'Please enter your last name';
        }
        if (trim($_POST['mobile']) == '') {
            $error_log['mobile'] = 'Please enter your mobile';
        }
        if (trim($_POST['address']) == '') {
            $error_log['address'] = 'Please enter your address';
        }
        if (trim($_POST['email']) == '') {
            $error_log['email'] = 'Please enter your email';
        }
        if (trim($_POST['amount']) == '') {
            $error_log['amount'] = 'Please enter your amount';
        }
        if ($_POST['username'] != '' && $_POST['pwd'] != '' && $_POST['first_name'] != '' && $_POST['last_name'] != '' && $_POST['mobile'] != '' && $_POST['address'] != '' && $_POST['email'] != '' && $_POST['amount']) {
            $error_log['success'] = '<p class="success">Thank you! You are now registered!</p>';
            $username = $pwd = $first_name = $last_name = $mobile = $address = $email = $amount = '';
        }
    }
    return $error_log;
}
if (isset($error_log['success']) && !empty($error_log['success'])) {
    try {
        InsertValue();
        $username = $pwd = $first_name = $last_name = $mobile = $address = $email = '';
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function InsertValue()
{
    require "connect.php";

    //Random string generator
    function generateRandomString($length = 7)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //Random number generator
    function generateRandomNumber($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomAccountNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomAccountNumber .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomAccountNumber;
    }

    $userPwd = '$_POST[pwd]';
    $userAccountNumber = generateRandomNumber(7);
    $userPIN = generateRandomNumber(4);

    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $encryption_iv = '1234567891011121';
    $encryption_key = "GeeksforGeeks";
    $encryption = openssl_encrypt(
        $_POST['pwd'],
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );

    $transaction_informations = "User deposited " . $_POST['amount'] . " in his own account";
    $transactionType = "First deposit";

    $sql = "insert into user_accounts (account_number,pin,first_name,last_name,mobile,address,email,username,pwd,balance) values('$userAccountNumber','$userPIN','$_POST[first_name]', '$_POST[last_name]', '$_POST[mobile]', '$_POST[address]', '$_POST[email]','$_POST[username]','$encryption','$_POST[amount]')";
    if ($conn->query($sql) === true) {
        echo '<p class="success">' . "Your account has been successfully created." . '</p>';
    } else {
        echo "error" . $conn->connect_error;
    }

    $name = "My self";

    $sql1 = "insert into transactions (account_number,type,amount,transaction_informations) values('$userAccountNumber','$transactionType','$_POST[amount]','$transaction_informations')";
    if ($conn->query($sql1) === true) {
    } else {
        echo "error" . $conn->connect_error;
    }

    $sql2 = "insert into contacts (account_number,contact_number,contact_name) values('$userAccountNumber','$userAccountNumber','$name')";
    if ($conn->query($sql2) === true) {
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
    <title>User Registration</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="maindiv">
            <div id="top" class="col-6">
                <h2 class="success">Welcome to the registration form!</h2>
                <br>
                <?php echo $error_log['success']; ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <label for="username">Username<span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="username" name="username" placeholder="username" value="<?php echo $first_name; ?>">
                    <p class="error-msg"><?php echo $error_log['username']; ?></p>

                    <label for="password">Password<span class="error-msg">*<span></label>
                    <input type="password" class="input-div-nn" id="pwd" name="pwd" placeholder="password" value="<?php echo $first_name; ?>">
                    <p class="error-msg"><?php echo $error_log['pwd']; ?></p>

                    <label for="first_name">First name<span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="first_name" name="first_name" placeholder="first name" value="<?php echo $first_name; ?>">
                    <p class="error-msg"><?php echo $error_log['first_name']; ?></p>

                    <label for="last_name">Last name<span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="last_name" name="last_name" placeholder="last name" value="<?php echo $last_name; ?>">
                    <p class="error-msg"><?php echo $error_log['last_name']; ?></p>

                    <label for="mobile">Mobile Number<span class="error-msg">*</label>
                    <input type="text" class="input-div-nn" id="mobile" name="mobile" placeholder="mobile number" value="<?php echo $mobile; ?>">
                    <p class="error-msg"><?php echo $error_log['mobile']; ?></p>

                    <label for="address">Address <span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="address" name="address" placeholder="address" value="<?php echo $address; ?>">
                    <p class="error-msg"><?php echo $error_log['address']; ?></p>

                    <label for="email">Email<span class="error-msg">*</label>
                    <input type="email" class="input-div-nn" id="email" name="email" placeholder="email" value="<?php echo $email; ?>">
                    <p class="error-msg"><?php echo $error_log['email']; ?></p>

                    <label for="amount">Amount<span class="error-msg"></label>
                    <input type="number" class="input-div-nn" id="amount" name="amount" placeholder="first deposit amount" value="<?php echo $amount; ?>">
                    <p class="error-msg"><?php echo $error_log['amount']; ?></p>

                    <input type="submit" class="submit" value="Register">

                    <br>
                    <a href="log_in.php" class="href">Sign In</a> <br>
                    <a href="index.php" class="href">Home</a> <br>

                </form>
            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>