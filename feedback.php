<?php
require_once "vendor/autoload.php";
use Twilio\Rest\Client;

$database = mysqli_connect('hcm4e9frmbwfez47.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'i2mwrf74ip9zubek', 'p0yzpu5xyq4ybqjr', 'qt4nvdslegbdc6h6');

if (isset($_POST['submit'])) {
    $feedback = $_POST['feedback'];
    $feedback = mysqli_real_escape_string($database, $feedback);

    $insert_feedback = "INSERT INTO feedback (feedback) VALUES ('$feedback')";
    error_log("Executing query: " . $insert_feedback);
    error_log("Mysql error for query: " . mysqli_error($database));
    $insert_feedback_result = mysqli_query($database, $insert_feedback);

    header('location: thankyou_feedback.html');
}

?>
<!DOCTYPE html>
    <title>PocketPlan - Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <head>
        <link rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
    </head>

    <body style="background-color:#24252A;">
        <div class="boxed">
            <div class="col-xs-6">
                <form method="post">
                    <h1 align="center" class="personal_label"><b>Feedback</b></h1>
                    <br>
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">What do you have in mind?</h4>
                        <input type="text" class="form-control" placeholder="Feedback" name="feedback" required>
                        <small id="passwordHelp" class="form-text text-muted">Feedback is completely anonymous.</small>
                    </div>
                    <br>
                    <center><input class="btn btn-primary" type="submit" name="submit" value="Submit" style="width: auto; height: auto; font-size: 25px; text-align: center;" required></center>
                </form>
            </div>
        </div>
    </body>
