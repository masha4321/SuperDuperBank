<?php

// NEED TO EDIT


// SELECT THE PARTICULAR ROW IN THE <</TABLE> AND CHECK THAT ID EXIST OR FT_NOT
// UPDATE
$url_value = explode('/', $_SERVER['PHP_SELF']);
// print_r($url_value);exit;
// print_r(array_key_exists('4',$url_value) == false?'yes':'no');
// exit;


if (!array_key_exists('4', $url_value) || $url_value[4] == '') {
    echo "PAGE NOT FOUND11";
    die;
} else {
    $array_result = CheckData($url_value);
}
$name = $email = $mobile = $message = '';
if (!empty($_POST)) {
    if ($_POST['name'] != '') {
        $name = $_POST['name'];
    }
    if ($_POST['email'] != '') {
        $email = $_POST['email'];
    }
    if ($_POST['mobile'] != '') {
        $mobile = $_POST['mobile'];
    }
    if ($_POST['message'] != '') {
        $message = $_POST['message'];
    }
}
$error_log = array();

function CheckData($url_value)
{
    // echo $url_value[4];
    // cross check 
    // $array_result = array();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "myDB";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Failed! " . $conn->connect_error);
    }
    // echo $encryption;exit;
    $sql = "select * from contactus where id = $url_value[4]";
    $result = $conn->query($sql);
    //    print_r($result);exit;

    if ($result->num_rows > 0) {
        // $_POST['username'] = 
        $array_result = $result->fetch_assoc();
    } else {
        echo "NO RECORD FOUND!";
        exit;
        // echo "error".$conn->connect_error;
    }
    $conn->close();
    return $array_result;
}


function UpdateData()
{

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "myDB";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Failed! " . $conn->connect_error);
    }
    // echo $encryption;exit;
    $sql = "update contactus set firstname='$_POST[name]', 
        mobile='$_POST[mobile]', email='$_POST[email]',message = '$_POST[message]' 
        where id='$_POST[update_confg_no]'";
    // print_r($sql);
    // exit;
    if ($conn->query($sql) === true) {
        // echo "done";
    } else {
        echo "error" . $conn->connect_error;
    }
    $conn->close();
}
$error_log = formValidation();


//requirement was to the get the error message
function formValidation()
{
    $error_log['name'] = $error_log['email'] = $error_log['mobile'] = $error_log['message'] = $error_log['success'] = '';

    if (isset($_POST) && !empty($_POST)) {

        if (trim($_POST['name']) == '') {

            $error_log['name'] = 'Please enter your Name';
            //push the string value for the key name and the array name is error_log
        }
        if ($_POST['email'] == '') {
            $error_log['email'] = 'Please enter your Email';
        }
        if ($_POST['mobile'] == '') {
            $error_log['mobile'] = 'Please enter your Mobile number';
        }
        if ($_POST['message'] == '') {
            $error_log['message'] = 'Please enter your Message';
        }
        if ($_POST['name'] != '' && $_POST['email'] != '' && $_POST['mobile'] != '' && $_POST['message'] != '') {
            UpdateData();
            $error_log['success'] = '<p class="success">Information Updated</p>';
            $name = '';
        }
    }

    return $error_log;
}
if (isset($error_log['success']) && !empty($error_log['success'])) {
    // $name = $email = $mobile = $message = '';
}
// print_r($array_result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us</title>

    <link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/database/admin/index.css">
</head>

<body>
    <div class="container">
        <div class="maindiv">
            <div class="col-6">

                <?php echo $error_log['success']; ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="name">Name <span class="error-msg">*<span></label>
                    <input type="text" class="input-div-nn" id="name" name="name" value="<?php echo $name == '' ? $array_result['firstname'] : $name; ?>">
                    <p class="error-msg"><?php echo $error_log['name']; ?></p>

                    <input type="hidden" name="update_confg_no" value="<?php echo  $array_result['id']; ?>">

                    <label for="email">Email<span class="error-msg">*</label>
                    <input type="email" class="input-div-nn" id="email" name="email" value="<?php echo $email == '' ? $array_result['email'] : $email; ?>">
                    <p class="error-msg"><?php echo $error_log['email']; ?></p>
                    <label for="mobile">Mobile Number<span class="error-msg">*</label>
                    <input type="text" class="input-div-nn" id="mobile" name="mobile" value="<?php echo $mobile == '' ? $array_result['mobile'] : $mobile; ?>">
                    <p class="error-msg"><?php echo $error_log['mobile']; ?></p>

                    <label for="message">Message<span class="error-msg">*</label>
                    <textarea name="message" id="message" cols="30" rows="10" class="input-div-nn"><?php echo $message == '' ? $array_result['message'] : $message; ?></textarea>
                    <p class="error-msg"><?php echo $error_log['message']; ?></p>
                    <a href="/database/admin/dashboard.php">Go Back</a>
                    <input type="submit" class="submit" value="Send Message">
                </form>
            </div>
            <div class="col-6"></div>
        </div>
    </div>
</body>

</html>

<!-- <script>

    $('.input-div-nn').click(function(){
        
        console.log($(this).data('id'));

        // create a url for the get request
        // ajax request here 
    });
</script> -->