<?php 
require_once "vendor/autoload.php";
use Twilio\Rest\Client;
$database = mysqli_connect('hcm4e9frmbwfez47.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'i2mwrf74ip9zubek', 'p0yzpu5xyq4ybqjr', 'qt4nvdslegbdc6h6');
$phoneNumber = $_GET['phoneNumber'];
date_default_timezone_set('America/Los_Angeles');

$info = "SELECT user.activated FROM user WHERE user.phone_number = $phoneNumber";
$info_result = mysqli_query($database, $info);
$info_row = mysqli_fetch_assoc($info_result);
$activated = $info_row['activated'];

if ($activated == 'N') {
    header('location: not_registered.html');
} else {

    $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $phoneNumber";
    error_log("Executing query: " . $get_user_id);
    error_log("Mysql error for query: " . mysqli_error($database));
    $get_user_id_result = mysqli_query($database, $get_user_id);
    $user_id_rows = mysqli_fetch_assoc($get_user_id_result);
    $user_id = $user_id_rows['user_id'];

    $select_task = "SELECT task FROM user_task WHERE user_task.user_id = $user_id";
    error_log("Executing query: " . $select_task);
    error_log("Mysql error for query: " . mysqli_error($database));
    $select_task_result = mysqli_query($database, $select_task);

    if(isset($_POST['delete'])) {

        $password = $_POST['password'];
        $password = mysqli_real_escape_string($database, $password);
        $hashFormat = "$2y$10$";
        $salt = "7557ubIMMG08Hj16z9WVus";
        $hashF_and_salt = $hashFormat . $salt;
        $password = crypt($password,$hashF_and_salt);

        $get_password_db = "SELECT user.password FROM user WHERE user.user_id = $user_id";
        error_log("Executing query: " . $get_password_db);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_password_db_result = mysqli_query($database, $get_password_db);
        $password_db_row = mysqli_fetch_assoc($get_password_db_result);
        $password_db = $password_db_row['password'];

        if ($password_db == $password) {
            $task = $_POST['task'];
            $delete_task = "DELETE FROM user_task WHERE user_task.task = '$task'";
            error_log("Executing query: " . $delete_task);
            error_log("Mysql error for query: " . mysqli_error($database));
            $delete_task_result = mysqli_query($database, $delete_task);

            header('location: task_deleted.html');
        } else {
            echo "<center><p style='color: white; font-family: 'Montserrat', sans-serif;'>Incorrect Password</p></center>";
        }   
    }
}
?>

<!DOCTYPE html>
    <title>PocketPlan - Delete Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>

    <body style="background-color:#24252A;">
        <h1 class="task_label">Delete an Incoming Task</h1>
        <hr>
        <div>
            <form method="post" id="container">
                <h2 align="center" class="personal_label">Password</h2>
                <center><input type="password" class="form-control" placeholder="Password" name="password" required></center>
                <center><small id="passwordHelp" class="form-text text-muted">Password used while registering</small></center>
                <br><br>
                <h3 align="center" class="personal_label">Task to Delete</h3>
                <center>
                    <select name="task" id="" class="custom-select">
                        <?php 
                            while ($row = mysqli_fetch_assoc($select_task_result)) {
                                $task = $row['task']; 
                                echo "<option value='$task'>$task</option>";
                            }
                        ?>
                    </select>
                    <hr>
                </center>
                <br>
            </form>
        </div>
        <center><input class="btn btn-danger" type="submit" form="container" name="delete" value="Delete" style="width: auto; height: auto; font-size: 25px; text-align: center;" required></center>
        <br>
    </body>