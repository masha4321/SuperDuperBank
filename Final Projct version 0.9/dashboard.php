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
    $array_result = InsertValue($userID);
} else {
    header("Location: session_error.php");
    die();
}

$array_result = InsertValue($userID);

foreach ($array_result as $value) {
    $userAccountNumber = $value['account_number'];
}

$array_result_banking = InsertBankingValue($userAccountNumber);

function InsertValue($userID)
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

function InsertBankingValue($userAccountNumber)
{
    require "connect.php";
    $sql = "select * from transactions WHERE account_number = '{$userAccountNumber}' order by date_created desc limit 15";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $array_result_banking = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "error" . $conn->connect_error;
    }
    $conn->close();
    return $array_result_banking;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <div id="top" class="container">
        <div class="maindiv">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Account Details
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <table id="customers">
                                <tr>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>Email</th>
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
                                        <td><a href="edit.php">Update</a>
                                    </tr>
                                <?php }
                                ?>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Banking Details
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <?php
                            foreach ($array_result as $value) {
                                $accountBalance = $value['balance'];
                                $accountNumber = $value['account_number'];
                            }
                            ?>

                            <p>
                                <?php
                                echo "Your account number is " . $accountNumber;
                                echo '<br>';
                                echo "Your account balance is " . $accountBalance . " $";

                                if ($accountBalance < 0) {
                                    echo "<br><br>Extra charges may be applied at the end of the month.";
                                }
                                ?>
                            </p>


                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Banking Actions
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body text-center">
                            <div class="btn-group" role="group" aria-label="Basic outlined example">
                                <a type="button" href="withdraw.php"><button class="btn btn-outline-primary">Withdraw</button></a>
                                <a type="button" href="deposit.php"><button class="btn btn-outline-primary">Deposit</button></a>
                                <a type="button" href="transfer.php"><button class="btn btn-outline-primary">Transfer</button></a>
                                <a type="button" href="contact.php"><button class="btn btn-outline-primary">Contact list</button></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Transaction History
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                        <div class="accordion-body text-center">
                            <table id="customers">
                                <tr>
                                    <th>Type of transaction</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Information</th>
                                    <th>Account traded with</th>
                                </tr>
                                <?php
                                    foreach ($array_result_banking as $value) { ?>
                                        <tr>
                                            <td><?php echo $value['type']; ?></td>
                                            <td><?php echo $value['amount']; ?></td>
                                            <td><?php echo $value['date_created']; ?></td>
                                            <td><?php echo $value['transaction_informations']; ?></td>
                                            <td><?php echo $value['account_traded_with']; ?></td>
                                        </tr>
                                    <?php }
                                            ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Logout
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                        <div class="accordion-body text-center">
                            <button id="logout_btn"><a href="log_out.php" class="href">Log out</a></button>

                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

</body>

</html>