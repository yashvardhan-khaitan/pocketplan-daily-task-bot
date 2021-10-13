<?php 
require_once "vendor/autoload.php";
use Twilio\Rest\Client;
date_default_timezone_set('America/Los_Angeles');
$database = mysqli_connect('hcm4e9frmbwfez47.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'i2mwrf74ip9zubek', 'p0yzpu5xyq4ybqjr', 'qt4nvdslegbdc6h6');

$select_reminder = "SELECT user.phone_number, user_task.task_id, user_task.task, user_task.reminder_time, user_task.date, user_task.task_type FROM user, user_task WHERE user.user_id = user_task.user_id AND user_task.reminder_sent = 'N'";
error_log("Executing query: " . $select_reminder);
error_log("Mysql error for query: " . mysqli_error($database));
$select_reminder_result = mysqli_query($database, $select_reminder);

while ($row = mysqli_fetch_assoc($select_reminder_result)) {
    $phoneNumber = $row['phone_number'];
    $taskName = $row['task'];
    $reminderTime = $row['reminder_time'];
    $task_id = $row['task_id'];
    $date = $row['date'];
    $task_type = $row['task_type'];

    $current_date = date('Y-m-d');
    $current_date_epoch = strtotime($current_date);
    error_log($current_date_epoch);

    $current_time = strtotime('now');
    $reminder_endtime = strtotime($reminderTime);

    if ($date > $current_date_epoch) {
        error_log("not date of remind");
    } elseif ($date <= $current_date_epoch) {

        if ($reminder_endtime > $current_time) {
            error_log("not ready to remind yet...reminder_endtime = " . $reminder_endtime . ", current time = " . $current_time);
        } else {
            
            if (strcasecmp($task_type, 'Daily') == 0) {

                error_log("ready to remind...reminder_endtime = " . $reminder_endtime . ", current time = " . $current_time);

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
                        'body' => "REMINDER from PocketPlan Daily Task Bot: ${taskName}"
                    )
                );

                $update_daily_query = "UPDATE user_task SET user_task.reminder_sent = 'Y' WHERE user_task.task_id = $task_id";
                error_log("Executing query: " . $update_daily_query);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_daily_query_result = mysqli_query($database, $update_daily_query);
    
                $currentDate = time();
                $daily_date = strtotime(date("Y-m-d", $currentDate) . "+1 days");
    
                $update_daily_date_query = "UPDATE user_task SET user_task.date = '$daily_date' WHERE user_task.task_id = $task_id";
                error_log("Executing query: " . $update_daily_date_query);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_date_query_result = mysqli_query($database, $update_daily_date_query);
    
                $update_daily_remind_query = "UPDATE user_task SET user_task.reminder_sent = 'N' WHERE user_task.task_id = $task_id";
                error_log("Executing query: " . $update_daily_remind_query);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_daily_remind_query_result = mysqli_query($database, $update_daily_remind_query);

            } elseif (strcasecmp($task_type, 'Weekly') == 0) {

                error_log("ready to remind...reminder_endtime = " . $reminder_endtime . ", current time = " . $current_time);

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
                        'body' => "REMINDER from PocketPlan Daily Task Bot: ${taskName}"
                    )
                );

                $update_weekly_query = "UPDATE user_task SET user_task.reminder_sent = 'Y' WHERE user_task.task_id = $task_id";
                error_log("Executing query: " . $update_weekly_query);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_weekly_query_result = mysqli_query($database, $update_weekly_query);
    
                $currentDate = time();
                $weekly_date = strtotime(date("Y-m-d", $currentDate) . "+7 days");
    
                $update_weekly_date_query = "UPDATE user_task SET user_task.date = '$weekly_date' WHERE user_task.task_id = $task_id";
                error_log("Executing query: " . $update_weekly_date_query);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_weekly_date_query_result = mysqli_query($database, $update_weekly_date_query);
    
                $update_weekly_remind_query = "UPDATE user_task SET user_task.reminder_sent = 'N' WHERE user_task.task_id = $task_id";
                error_log("Executing query: " . $update_weekly_remind_query);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_weekly_remind_query_result = mysqli_query($database, $update_weekly_remind_query);

            } else {

                error_log("ready to remind...reminder_endtime = " . $reminder_endtime . ", current time = " . $current_time);

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
                        'body' => "REMINDER from PocketPlan Daily Task Bot: ${taskName}"
                    )
                );

                $update_query = "UPDATE user_task SET user_task.reminder_sent = 'Y' WHERE user_task.task_id = $task_id";
                error_log("Executing query: " . $update_query);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_query_result = mysqli_query($database, $update_query);
            }
        }
    }
}

?>