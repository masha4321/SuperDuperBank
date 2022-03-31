<?php
$name = $email = $mobile = $message = '';
if (!empty($_POST)) {
    if ($_POST['username'] != '') {
        $name = $_POST['username'];
    }
    if ($_POST['pwd'] != '') {
        $email = $_POST['pwd'];
    }
}
$error_log = array();
$error_log = formValidation();

function formValidation()
{
    $error_log['username'] = $error_log['pwd'] = '';
    if (isset($_POST) && !empty($_POST)) {
        if (trim($_POST['username']) == '') {
            $error_log['username'] = 'Please enter your Name';
        }
        if ($_POST['pwd'] == '') {
            $error_log['pwd'] = 'Please enter your Email';
        }
        if ($_POST['username'] != '' && $_POST['pwd'] != '') {
            $error_log['success'] = '<p class="success">Thank you!</p>';
            $name = '';
        }
    }
    return $error_log;
}

if (isset($error_log['success']) && !empty($error_log['success'])) {
    try {
        InsertValue();
        $name = $email = $mobile = $message = '';
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function InsertValue()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "banking_system_db";
    $conn = new mysqli($servername, $username, $password, $dbname);

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
    if ($conn->connect_error) {
        die("Failed! " . $conn->connect_error);
    }

    $sql = "insert into admin (username,pwd) values('$_POST[username]','$encryption')";

    if ($conn->query($sql) === true) {
        echo '<p class="success">' . "Your administrative account has been created." . '</p>';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="maindiv">
            <div class="col-6">
                <h2 class="success">Welcome to the admin registration form!</h2>
                <br>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="username">User Name <span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="username" name="username" placeholder="username" value="<?php echo $name; ?>">

                    <p class="error-msg"><?php echo $error_log['username']; ?></p>

                    <label for="password">Password<span class="error-msg">*</label>
                    <input type="password" class="input-div-nn" id="pwd" name="pwd" placeholder="password" value="<?php echo $email; ?>">
                    <p class="error-msg"><?php echo $error_log['pwd']; ?></p>

                    <input type="submit" class="submit" value="Confirm">

                    <br>
                    <a href="admin_log_in.php" class="href">Sign in as an administrator</a> <br>
                    <a href="index.php" class="href">Home</a> <br>
                </form>
            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>