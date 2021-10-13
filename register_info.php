<?php 
require_once "vendor/autoload.php";
use Twilio\Rest\Client;

$database = mysqli_connect('hcm4e9frmbwfez47.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'i2mwrf74ip9zubek', 'p0yzpu5xyq4ybqjr', 'qt4nvdslegbdc6h6');
$phoneNumber = $_GET['phoneNumber'];

$info = "SELECT user.activated FROM user WHERE user.phone_number = $phoneNumber";
$info_result = mysqli_query($database, $info);
$info_row = mysqli_fetch_assoc($info_result);
$activated = $info_row['activated'];

if ($activated == 'Y') {
    header('location: registered_already.html');
} else {
    
if (isset($_POST['submit'])) {

        $database = mysqli_connect('hcm4e9frmbwfez47.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'i2mwrf74ip9zubek', 'p0yzpu5xyq4ybqjr', 'qt4nvdslegbdc6h6');
        $name = $_POST['name'];
        $grade = $_POST['grade'];
        $school = $_POST['school'];
        $phoneNumber = $_GET['phoneNumber'];
        $password = $_POST['password'];

        $name = mysqli_real_escape_string($database, $name);
        $grade = mysqli_real_escape_string($database, $grade);
        $school = mysqli_real_escape_string($database, $school);
        $password = mysqli_real_escape_string($database, $password);
        $phoneNumber = mysqli_real_escape_string($database, $phoneNumber);

        $hashFormat = "$2y$10$";
        $salt = "7557ubIMMG08Hj16z9WVus";
        $hashF_and_salt = $hashFormat . $salt;
        $password = crypt($password,$hashF_and_salt);

        $insert_info = "INSERT INTO user (name,grade,school,phone_number,password) VALUES ('$name', '$grade', '$school', '$phoneNumber', '$password')";
        error_log("Executing query: " . $insert_info);
        error_log("Mysql error for query: " . mysqli_error($database));
        $insert_result = mysqli_query($database, $insert_info);

        $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = '$phoneNumber'";
        error_log("Executing query: " . $get_user_id);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_user_id_result = mysqli_query($database, $get_user_id);
        $row = mysqli_fetch_assoc($get_user_id_result);
        $user_id = $row['user_id'];

        $update_activation = "UPDATE user SET user.activated = 'Y' WHERE user.user_id = $user_id";
        error_log("Executing query: " . $update_activation);
        error_log("Mysql error for query: " . mysqli_error($database));
        $update_activation_result = mysqli_query($database, $update_activation);

        header('location: thankyou.html');
        
        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = 'AC848ed57ccc93438c2fea0bce283b9a50';
        $auth_token = '6e61c24a8c1c7e6b523bfa70b3018256';

        // A Twilio number you own with SMS capabilities
        $twilio_number = "+18302545295";

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $phoneNumber,
            array(
                'from' => $twilio_number,
                'body' => "Ah! Your name is ${name}. I love it. You can now start using the bot. Reply REMIND to start sending me your daily tasks."
            )
        );
    }

}

?>
<!DOCTYPE html>
    <title>PocketPlan - My Personal Information</title>
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
                    <h1 align="center" class="personal_label"><b>Personal Information</b></h1>
                    <br>
                    
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">Name</h4>
                        <input type="text" class="form-control" placeholder="Name" name="name" required>
                        <small id="passwordHelp" class="form-text text-muted">Name cannot be changed later</small>
                    </div>
                    
                    <br>
            
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">Grade</h4>
                        <input type="text" class="form-control" placeholder="Grade" name="grade" required>
                    </div>
                    
                    <br>
                
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">School</h4>
                        <input type="text" class="form-control" placeholder="School" name="school" required>
                    </div>

                    <br>

                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">Password</hr>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <small id="passwordHelp" class="form-text text-muted">For Upcoming Features</small>
                    </div>

                    <br>
                    <br>
                    <center><small id="passwordHelp" class="form-text text-muted">Passwords are Encrypted and Stored</small></center>
                    <br>
                    <center><input class="btn btn-primary" type="submit" name="submit" value="Submit" style="width: auto; height: auto; font-size: 25px; text-align: center;" required></center>
                </form>
            </div>
        </div>

    </body>
