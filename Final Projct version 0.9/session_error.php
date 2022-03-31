    <?php 
        session_start();
        session_destroy();
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
    <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
        <div class="container">
            <div class="maindiv">
            <div class="success">Your session has ended, please login again.</div>
            <div class="col-6">
                <br>
                <a href="index.php" class="href">Home</a> <br>
            </div>
            <div class="col-6"></div>
            </div>
        </div>
    </body>
    </html>

