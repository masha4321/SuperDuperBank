<?php
session_start();

if(isset($_SESSION['userid'])){ header('location:admin_dashboard.php');}

if(isset($_SESSION['num_login_fail'])==false) {
    $_SESSION['num_login_fail']=0;
}

$username = $pwd  = '';
if (!empty($_POST)) {
    if ($_POST['username'] != '') {
        $username = $_POST['username'];
    }
    if ($_POST['pwd'] != '') {
        $pwd = $_POST['pwd'];
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
            $error_log['pwd'] = 'Please enter your Password';
        }
        if ($_POST['username'] != '' && $_POST['pwd'] != '') {
            $error_log['success'] = '<p class="success">Thank you we will contact you soon</p>';
            $name = '';
        }
    }
    return $error_log;
}
if (isset($error_log['success']) && !empty($error_log['success'])) {
    $error_log =  InsertValue();
    if($_SESSION['num_login_fail'] == 3) {
        $error_log['username'] = 'Please try again in a few minutes';
        $error_log['pwd'] = 'Please try again in a few minutes';
    } elseif ($_SESSION['num_login_fail'] > 0) {
        $error_log['username'] = 'Enter a valid username';
        $error_log['pwd'] = 'Enter a valid password';
    } else {
        $error_log['username'] = '';
        $error_log['pwd'] = '';
    }
    $name = $email = $mobile = $message = '';
}

function InsertValue() {
    $error_log = array();
    $error_log['username'] = $error_log['pwd']   = '';

    require "connect.php";

    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $decryption_iv = '1234567891011121';
    $decryption_key = "GeeksforGeeks";

    $sql = "select * from admin where username = '$_POST[username]'";
    $result = $conn->query($sql);


    if(isset($_SESSION['num_login_fail'])) {
        if($_SESSION['num_login_fail'] == 3) {
            if(time() - $_SESSION['last_login_time'] < 10*60*60 ){
                return; 
            } else {
                $_SESSION['num_login_fail'] = 0;
            }
        }      
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $decryption = openssl_decrypt(
                $row['pwd'],
                $ciphering,
                $decryption_key,
                $options,
                $decryption_iv
            );
            if ($_POST['pwd'] == $decryption) {
                $_SESSION['num_login_fail'] = 0;
                $_SESSION['user_id'] = $_POST['username'];
                header("Location: admin_dashboard.php");
                die();
            } else {
                $_SESSION['num_login_fail']++;
                $_SESSION['last_login_time'] = time();
                $error_log['pwd'] = 'Please verify the username and password.';
            }
        } else {
            $error_log['pwd'] = 'Please verify the username and password.';
        }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Log in</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="maindiv">
            <h2 class="success">Welcome to the admin log in page!</h2>
            <div class="col-6">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <label for="username">Username <span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="username" name="username" placeholder="username" value="<?php echo $username; ?>">
                    <p class="error-msg"><?php echo $error_log['username']; ?></p>

                    <label for="password">Password<span class="error-msg">*</label>
                    <input type="password" class="input-div-nn" id="pwd" name="pwd" placeholder="password" value="<?php echo $pwd; ?>">
                    <p class="error-msg"><?php echo $error_log['pwd']; ?></p>

                    <input type="submit" class="submit" value="Confirm">

                    <br>
                    <a href="admin_register.php" class="href">Create a new administrative account</a> <br>
                    <a href="index.php" class="href">Home</a> <br>
                </form>
            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>