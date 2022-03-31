<?php

//Avoid multiple sessions
if (!isset($_SESSION)) {
    session_start();
}

//Initialize variables
$pwd = $first_name = $last_name = $mobile = $address = $email = '';
if (!empty($_POST)) {
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
}

// Validate update form
$error_log = array();
$error_log = formValidation();
function formValidation()
{
    $error_log['pwd'] = $error_log['first_name'] = $error_log['last_name'] = $error_log['mobile'] = $error_log['address'] = $error_log['email'] = $error_log['success'] = '';
    if (isset($_POST) && !empty($_POST)) {
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
        if ($_POST['pwd'] != '' && $_POST['first_name'] != '' && $_POST['last_name'] != '' && $_POST['mobile'] != '' && $_POST['address'] != '' && $_POST['email'] != '') {
            $error_log['success'] = '<p class="success">You have been updated successfully!</p>';
            $username = $pwd = $first_name = $last_name = $mobile = $address = $email = '';
        }
    }

    return $error_log;
}

//If there are no empty/incorrect values in the form, then it will update the information of the user in the database
if (isset($error_log['success']) && !empty($error_log['success'])) {
    try {
        UpdateData();
        $pwd = $first_name = $last_name = $mobile = $address = $email = '';
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

//Update data SQL injection into database
function UpdateData()
{
    require "connect.php";

    if (isset($_SESSION['user_id'])) {
        $userID =  $_SESSION['user_id'];
    }

    $pwd = mysqli_real_escape_string($conn, $_POST["pwd"]);
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $mobile = mysqli_real_escape_string($conn, $_POST["mobile"]);
    $address = mysqli_real_escape_string($conn, $_POST["address"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    $sql = "UPDATE user_accounts 
            SET      pwd = '$pwd',
                    first_name = '$first_name',
                    last_name = '$last_name',
                    mobile = '$mobile',
                    address = '$address',
                    email = '$email'
            where username = '$userID'";

    if ($conn->query($sql) === true) {
    } else {
        echo "error" . $conn->connect_error;
    }
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

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
            <div class="col-6">
                <h2 class="success">Update Information</h2>
                <br>
                <?php echo $error_log['success']; ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                    <label for="password">Password<span class="error-msg"><span></label>
                    <input type="password" class="input-div-nn" id="pwd" name="pwd" placeholder="password" value="<?php echo $pwd; ?>">
                    <p class="error-msg"><?php echo $error_log['pwd']; ?></p>

                    <label for="first_name">First name<span class="error-msg"><span></label>
                    <input type="text" class="input-div-nn" id="first_name" name="first_name" placeholder="first name" value="<?php echo $first_name; ?>">
                    <p class="error-msg"><?php echo $error_log['first_name']; ?></p>

                    <label for="last_name">Last name<span class="error-msg"><span></label>
                    <input type="text" class="input-div-nn" id="last_name" name="last_name" placeholder="last name" value="<?php echo $last_name; ?>">
                    <p class="error-msg"><?php echo $error_log['last_name']; ?></p>

                    <label for="mobile">Mobile Number<span class="error-msg"></label>
                    <input type="number" class="input-div-nn" id="mobile" name="mobile" placeholder="mobile number" value="<?php echo $mobile; ?>">
                    <p class="error-msg"><?php echo $error_log['mobile']; ?></p>

                    <label for="address">Address <span class="error-msg"><span></label>
                    <input type="text" class="input-div-nn" id="address" name="address" placeholder="address" value="<?php echo $address; ?>">
                    <p class="error-msg"><?php echo $error_log['address']; ?></p>

                    <label for="email">Email<span class="error-msg"></label>
                    <input type="email" class="input-div-nn" id="email" name="email" placeholder="email" value="<?php echo $email; ?>">
                    <p class="error-msg"><?php echo $error_log['email']; ?></p>

                    <input type="submit" class="submit" value="Update">

                    <br>
                    <button><a type="button" href="dashboard.php">Cancel</a></button><br>

                </form>
            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>